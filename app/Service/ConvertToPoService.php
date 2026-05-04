<?php

namespace App\Service;

use App\Constants\PrStatusConstant;
use App\Models\ItemHistory;
use App\Models\ItemLog;
use App\Models\PrHeader;
use App\Models\PrHistory;
use App\Models\PrLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ConvertToPoService
{
    /**
     * Proses konversi PR ke PO oleh Procurement Officer.
     *
     * - PO number diberikan per item (partial atau full)
     * - Jika SEMUA item sudah memiliki po_number → status jadi CONVERTED_TO_PO
     * - Jika masih ada item tanpa po_number → status tetap APPROVED, PR tetap di daftar
     * - Snapshot: PrLog, PrHistory, ItemLog, ItemHistory
     *
     * @param  PrHeader          $record
     * @param  array<int,string> $poNumbers  [item_id => po_number]
     * @param  User              $user
     */
    public function execute(PrHeader $record, array $poNumbers, User $user): array
    {
        $header = $record->fresh([
            'detail',
            'detail.items',
            'currentStep',
            'approvalWorkflow',
            'approvalWorkflow.steps',
        ]);

        if (! $header || ! $header->detail) {
            return $this->fail('Data tidak lengkap', 'Data PR tidak ditemukan atau belum memiliki detail.');
        }

        if (! $header->current_step_id || ! $header->current_role_id) {
            // Jika sudah pernah diproses sebagian (step null tapi status masih approved), izinkan
            if ($header->pr_status !== PrStatusConstant::APPROVED) {
                return $this->fail('Step tidak valid', 'PR ini tidak memiliki step aktif yang dapat diproses.');
            }
        }

        $workflow = $header->approvalWorkflow;
        if (! $workflow || $workflow->status !== 'active') {
            return $this->fail('Workflow tidak aktif', 'Approval workflow aktif untuk PR ini tidak ditemukan.');
        }

        // Validasi user memiliki role yang sesuai current step (jika masih ada step aktif)
        if ($header->current_step_id) {
            $currentStep = $workflow->steps()
                ->whereKey($header->current_step_id)
                ->first();

            if (! $currentStep) {
                return $this->fail('Step tidak ditemukan', 'Step saat ini tidak ditemukan pada workflow aktif.');
            }

            if (! $user->roles()->where('roles.id', $currentStep->role_id)->exists()) {
                return $this->fail('Akses Ditolak', 'Role Anda tidak sesuai dengan step approval saat ini.');
            }

            // Cek secara dinamis apakah ada next step
            $nextStep = $workflow->steps()
                ->where('step_order', '>', $currentStep->step_order)
                ->orderBy('step_order', 'asc')
                ->first();

            if ($nextStep !== null) {
                return $this->fail('Bukan step terakhir', 'PR ini belum berada di step akhir.');
            }
        } else {
            // Jika step sudah null (pernah diproses partial), cari role dari step terakhir workflow
            $lastStep = $workflow->steps()->orderBy('step_order', 'desc')->first();
            $currentStep = $lastStep;

            if ($lastStep && ! $user->roles()->where('roles.id', $lastStep->role_id)->exists()) {
                return $this->fail('Akses Ditolak', 'Role Anda tidak sesuai dengan step terakhir.');
            }
        }

        $items = $header->items()->orderBy('id')->get();

        if ($items->isEmpty()) {
            return $this->fail('Item tidak ditemukan', 'Tidak ada item yang dapat diproses untuk PR ini.');
        }

        // Filter hanya PO numbers yang valid (non-empty)
        $validPoNumbers = collect($poNumbers)
            ->filter(fn($po) => is_string($po) && trim($po) !== '')
            ->mapWithKeys(fn($po, $id) => [(int)$id => trim($po)]);

        if ($validPoNumbers->isEmpty()) {
            return $this->fail('Nomor PO kosong', 'Minimal satu item harus diisi nomor PO.');
        }

        // --- DELTA DETECTION ---
        // Kita mengecek apakah ada nomor PO yang berubah dibanding data di database.
        // Jika tidak ada perubahan, kita langsung return sukses tanpa membuat log baru/snapshot
        // untuk menjaga kebersihan data riwayat (audit trail).
        $hasChanges = false;
        foreach ($validPoNumbers as $itemId => $poNum) {
            $existingItem = $items->firstWhere('id', $itemId);
            if (!$existingItem || trim((string)$existingItem->po_number) !== (string)$poNum) {
                $hasChanges = true;
                break;
            }
        }

        if (!$hasChanges) {
            return [
                'ok'           => true,
                'all_assigned' => $items->every(fn($i) => !empty($i->po_number)),
                'title'        => 'Data Identik',
                'message'      => 'Nomor PO yang Anda masukkan sudah sama dengan data yang tersimpan sebelumnya.',
            ];
        }

        $now     = now();
        $batchId = $header->batch_id;
        $detail  = $header->detail;

        try {
            DB::transaction(function () use (
                $batchId,
                $currentStep,
                $detail,
                $header,
                $items,
                $now,
                $validPoNumbers,
                $user
            ): void {
                // 1. Update po_number per item
                foreach ($validPoNumbers as $itemId => $poNum) {
                    DB::table('items')
                        ->where('id', $itemId)
                        ->update([
                            'po_number'  => $poNum,
                            'updated_at' => $now,
                        ]);
                }

                // 2. Cek apakah SEMUA item sudah punya po_number
                $itemsWithoutPo = $header->items()
                    ->whereNull('po_number')
                    ->orWhere('po_number', '')
                    ->count();

                $allAssigned = $itemsWithoutPo === 0;

                // 3. Update pr_headers
                $headerUpdate = [
                    'approver_id' => $user->id,
                    'approved_at' => $now,
                    'updated_at'  => $now,
                ];

                if ($allAssigned) {
                    $headerUpdate['pr_status']       = PrStatusConstant::CONVERTED_TO_PO;
                    $headerUpdate['current_role_id']  = null;
                    $headerUpdate['current_step_id']  = null;
                }

                DB::table('pr_headers')
                    ->where('id', $header->id)
                    ->update($headerUpdate);                // --- LOGIC: SNAPSHOT HANYA SAAT SEMUA PO TERPENUHI ---
                // Sesuai permintaan terbaru: Kita tidak membuat snapshot/log selama proses masih parsial.
                // Snapshot baru dibuat saat seluruh item sudah memiliki nomor PO (status CONVERTED_TO_PO).

                if ($allAssigned) {
                    $poNumberList = $validPoNumbers->values()->unique()->implode(', ');

                    // 1. Snapshot ke pr_logs
                    PrLog::create([
                        'batch_id'            => $batchId,
                        'action'              => 'CONVERT_TO_PO',
                        'status'              => 'SUCCESS',
                        'notes'               => 'Semua item telah di-assign PO. PR selesai.',
                        'user_id'             => $user->id,
                        'role_id'             => $currentStep?->role_id,
                        'pr_header_id'        => $header->id,
                        'pr_number'           => $header->pr_number,
                        'pr_status'           => PrStatusConstant::CONVERTED_TO_PO,
                        'requester_id'        => $header->requester_id,
                        'department_id'       => $header->department_id,
                        'approver_id'         => $user->id,
                        'approval_workflow_id' => $header->approval_workflow_id,
                        'current_role_id'     => null,
                        'current_step_id'     => null,
                        'header_description'  => $header->description,
                        'priority'            => $detail?->priority,
                        'document_no'         => $detail?->document_no,
                        'title'               => $detail?->title,
                        'issue_date'          => $detail?->issue_date,
                        'rev_no'              => $detail?->rev_no,
                        'ref_date'            => $detail?->ref_date,
                        'document_type'       => $detail?->document_type,
                        'no'                  => $detail?->no,
                        'needs'               => $detail?->needs,
                        'vessel_id'           => $detail?->vessel_id,
                        'request_date'        => $detail?->request_date,
                        'required_date'       => $detail?->required_date,
                        'expired_date'        => $detail?->expired_date,
                        'detail_description'  => $detail?->description,
                        'payload'             => [
                            'po_numbers'   => $validPoNumbers->toArray(),
                            'all_assigned' => true,
                            'next_step_id' => null,
                            'next_role_id' => null,
                        ],
                    ]);

                    // 2. Snapshot ke pr_histories
                    PrHistory::create([
                        'batch_id'            => $batchId,
                        'pr_header_id'        => $header->id,
                        'pr_number'           => $header->pr_number,
                        'pr_status'           => PrStatusConstant::CONVERTED_TO_PO,
                        'requester_id'        => $header->requester_id,
                        'department_id'       => $header->department_id,
                        'approver_id'         => $user->id,
                        'approval_workflow_id' => $header->approval_workflow_id,
                        'current_role_id'     => null,
                        'current_step_id'     => null,
                        'header_description'  => $header->description,
                        'priority'            => $detail?->priority,
                        'document_no'         => $detail?->document_no,
                        'title'               => $detail?->title,
                        'issue_date'          => $detail?->issue_date,
                        'rev_no'              => $detail?->rev_no,
                        'ref_date'            => $detail?->ref_date,
                        'document_type'       => $detail?->document_type,
                        'no'                  => $detail?->no,
                        'needs'               => $detail?->needs,
                        'vessel_id'           => $detail?->vessel_id,
                        'request_date'        => $detail?->request_date,
                        'required_date'       => $detail?->required_date,
                        'expired_date'        => $detail?->expired_date,
                        'detail_description'  => $detail?->description,
                    ]);

                    // 3. Snapshot setiap item ke items_log dan items_history
                    // Kita refresh snapshot item agar data po_number akhir terekam secara permanen di history.
                    ItemLog::where('batch_id', $batchId)->whereIn('pr_detail_id', $header->items()->pluck('pr_detail_id'))->delete();
                    ItemHistory::where('batch_id', $batchId)->whereIn('pr_detail_id', $header->items()->pluck('pr_detail_id'))->delete();

                    $freshItems = $header->items()->orderBy('id')->get();
                    foreach ($freshItems as $item) {
                        $itemSnapshot = [
                            'batch_id'         => $batchId,
                            'pr_detail_id'     => $item->pr_detail_id,
                            'vessel_id'        => $item->vessel_id,
                            'item_category_id' => $item->item_category_id,
                            'no'               => $item->no,
                            'type'             => $item->type,
                            'size'             => $item->size,
                            'description'      => $item->description,
                            'quantity'         => $item->quantity,
                            'unit'             => $item->unit,
                            'remaining'        => $item->remaining,
                            'po_number'        => $item->po_number,
                        ];
                        ItemLog::create($itemSnapshot);
                        ItemHistory::create($itemSnapshot);
                    }
                }
            });
        } catch (\Throwable $e) {
            return $this->fail('Gagal memproses', $e->getMessage());
        }

        // Cek ulang apakah semua sudah punya PO
        $remainingCount = $header->items()->where(function ($q) {
            $q->whereNull('po_number')->orWhere('po_number', '');
        })->count();

        return [
            'ok'           => true,
            'all_assigned' => $remainingCount === 0,
            'title'        => null,
            'message'      => null,
        ];
    }

    private function fail(string $title, string $message): array
    {
        return ['ok' => false, 'title' => $title, 'message' => $message];
    }
}
