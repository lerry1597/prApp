<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('approval_steps', function (Blueprint $table) {
            $table->string('pr_status')->nullable()->after('condition');
        });

        // Normalize legacy values if any rows already contain manual values.
        DB::table('approval_steps')
            ->whereNotNull('pr_status')
            ->update([
                'pr_status' => DB::raw('LOWER(TRIM(pr_status))'),
            ]);

        $validStatuses = [
            'draft',
            'submitted',
            'waiting_approval',
            'approved',
            'rejected',
            'converted_to_po',
            'closed',
            'pending',
        ];

        $maxStepByWorkflow = DB::table('approval_steps')
            ->select('approval_workflow_id', DB::raw('MAX(step_order) as max_step_order'))
            ->groupBy('approval_workflow_id')
            ->pluck('max_step_order', 'approval_workflow_id');

        DB::table('approval_steps')
            ->select('id', 'approval_workflow_id', 'step_order', 'pr_status')
            ->orderBy('id')
            ->chunkById(200, function ($steps) use ($maxStepByWorkflow, $validStatuses): void {
                foreach ($steps as $step) {
                    $status = is_string($step->pr_status) ? strtolower(trim($step->pr_status)) : null;
                    $isMissing = $status === null || $status === '';
                    $isInvalid = ! $isMissing && ! in_array($status, $validStatuses, true);

                    if (! $isMissing && ! $isInvalid) {
                        continue;
                    }

                    $maxOrder = (int) ($maxStepByWorkflow[$step->approval_workflow_id] ?? 0);
                    $fallbackStatus = match (true) {
                        (int) $step->step_order === 1 => 'submitted',
                        $maxOrder > 0 && (int) $step->step_order === $maxOrder => 'converted_to_po',
                        default => 'waiting_approval',
                    };

                    DB::table('approval_steps')
                        ->where('id', $step->id)
                        ->update(['pr_status' => $fallbackStatus]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approval_steps', function (Blueprint $table) {
            $table->dropColumn('pr_status');
        });
    }
};
