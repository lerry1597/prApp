<?php

namespace App\Service;

use App\Models\ApprovalStep;
use App\Models\ApprovalWorkflow;
use App\Models\PrHeader;
use App\Models\User;

class ApprovalFlowService
{
    public function getActiveWorkflow(): ?ApprovalWorkflow
    {
        return ApprovalWorkflow::query()
            ->where('status', 'active')
            ->first();
    }

    /**
     * Logic approval untuk proses submit PR (requester).
     * Tugasnya hanya memvalidasi bahwa user boleh submit dan mengembalikan
     * step awal (step pertama). Caller yang menentukan ke step mana PR akan diteruskan.
     */
    public function resolveSubmissionContext(?User $user): array
    {
        if (! $user) {
            return $this->fail('Data tidak lengkap', 'User tidak ditemukan. Silakan login ulang.');
        }

        $workflow = $this->getActiveWorkflow();
        if (! $workflow) {
            return $this->fail('Error Workflow', 'Alur persetujuan (approval workflow) aktif tidak ditemukan. Silakan hubungi admin.');
        }

        // Langsung ambil step awal (step pertama dari workflow)
        $initialStep = $workflow->steps()
            ->orderBy('step_order', 'asc')
            ->first();

        if (! $initialStep) {
            return $this->fail('Step tidak ditemukan', 'Step awal approval tidak ditemukan pada workflow aktif.');
        }

        if (! $user->roles()->where('roles.id', $initialStep->role_id)->exists()) {
            return $this->fail('Akses Ditolak', 'Role Anda tidak memiliki otoritas untuk melakukan pengajuan pada alur ini.');
        }

        return $this->success($workflow, $initialStep, null);
    }

    /**
     * Logic approval untuk proses approver (approve PR).
     */
    public function resolveApprovalContext(?User $user, ?PrHeader $header): array
    {
        if (! $user || ! $header || ! $header->detail) {
            return $this->fail('Data tidak lengkap', 'Data PR tidak ditemukan atau belum memiliki detail.');
        }

        $workflow = ApprovalWorkflow::query()
            ->whereKey($header->approval_workflow_id)
            ->where('status', 'active')
            ->first();

        if (! $workflow) {
            return $this->fail('Workflow tidak aktif', 'Approval workflow aktif untuk PR ini tidak ditemukan.');
        }

        $currentStep = $this->resolveCurrentStep($workflow, $header->current_step_id);
        if (! $currentStep) {
            return $this->fail('Current step tidak ditemukan', 'Step approval saat ini tidak ditemukan pada workflow aktif.');
        }

        if (! $user->roles()->where('roles.id', $currentStep->role_id)->exists()) {
            return $this->fail('Role tidak sesuai', 'Role Anda tidak cocok dengan step approval saat ini.');
        }

        // nextStep bisa null jika ini step terakhir (final approval)
        $nextStep = $workflow->steps()
            ->where('step_order', '>', $currentStep->step_order)
            ->orderBy('step_order', 'asc')
            ->first();

        return $this->success($workflow, $currentStep, $nextStep);
    }

    private function resolveCurrentStep(ApprovalWorkflow $workflow, ?int $currentStepId): ?ApprovalStep
    {
        if (! $currentStepId) {
            return null;
        }

        return $workflow->steps()->whereKey($currentStepId)->first();
    }

    private function success(ApprovalWorkflow $workflow, ApprovalStep $currentStep, ?ApprovalStep $nextStep): array
    {
        return [
            'ok' => true,
            'title' => null,
            'message' => null,
            'workflow' => $workflow,
            'currentStep' => $currentStep,
            'nextStep' => $nextStep,
        ];
    }

    private function fail(string $title, string $message): array
    {
        return [
            'ok' => false,
            'title' => $title,
            'message' => $message,
            'workflow' => null,
            'currentStep' => null,
            'nextStep' => null,
        ];
    }
}
