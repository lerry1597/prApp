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
     * - Validasi step terakhir secara dinamis dari workflow
     * - Update pr_status ke CONVERTED_TO_PO
     * - Set current_role_id dan current_step_id ke null
     * - Simpan po_number
     * - Snapshot: PrLog, PrHistory, ItemLog, ItemHistory
     */
    public function execute(PrHeader $record, string $poNumber, User $user): array
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
            return $this->fail('Step tidak valid', 'PR ini tidak memiliki step aktif yang dapat diproses.');
        }

        $workflow = $header->approvalWorkflow;
        if (! $workflow || $workflow->status !== 'active') {
            return $this->fail('Workflow tidak aktif', 'Approval workflow aktif untuk PR ini tidak ditemukan.');
        }

        // Validasi user memiliki role yang sesuai current step
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

        // Procurement officer convert to PO = step terakhir harus null (tidak ada next step)
        // Jika masih ada next step, tolak — bukan giliran procurement untuk finalisasi
        if ($nextStep !== null) {
            return $this->fail('Bukan step terakhir', 'PR ini belum berada di step akhir. Konversi ke PO hanya dapat dilakukan pada step terakhir.');
        }

        $items = $header->items()->orderBy('id')->get();

        if ($items->isEmpty()) {
            return $this->fail('Item tidak ditemukan', 'Tidak ada item yang dapat diproses untuk PR ini.');
        }

        $now  = now();
        $batchId = $header->batch_id;
        $detail  = $header->detail;

        try {
            DB::transaction(function () use (
                $batchId, $currentStep, $detail, $header, $items, $now, $poNumber, $user
            ): void {
                // 1. Update pr_headers
                DB::table('pr_headers')
                    ->where('id', $header->id)
                    ->update([
                        'pr_status'       => PrStatusConstant::CONVERTED_TO_PO,
                        'po_number'       => $poNumber,
                        'current_role_id' => null,
                        'current_step_id' => null,
                        'approver_id'     => $user->id,
                        'approved_at'     => $now,
                        'updated_at'      => $now,
                    ]);

                // 2. Snapshot header + detail ke pr_logs
                PrLog::create([
                    'batch_id'            => $batchId,
                    'action'              => 'CONVERT_TO_PO',
                    'status'              => 'SUCCESS',
                    'notes'               => 'Procurement Officer mengkonversi PR ke PO. Nomor PO: ' . $poNumber,
                    'user_id'             => $user->id,
                    'role_id'             => $currentStep->role_id,
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
                        'po_number'    => $poNumber,
                        'next_step_id' => null,
                        'next_role_id' => null,
                    ],
                ]);

                // 3. Snapshot header + detail ke pr_histories
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

                // 4. Snapshot setiap item ke items_log dan items_history
                foreach ($items as $item) {
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
                    ];
                    ItemLog::create($itemSnapshot);
                    ItemHistory::create($itemSnapshot);
                }
            });
        } catch (\Throwable $e) {
            return $this->fail('Gagal memproses', $e->getMessage());
        }

        return ['ok' => true, 'title' => null, 'message' => null];
    }

    private function fail(string $title, string $message): array
    {
        return ['ok' => false, 'title' => $title, 'message' => $message];
    }
}
