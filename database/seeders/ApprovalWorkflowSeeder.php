<?php

namespace Database\Seeders;

use App\Constants\PrStatusConstant;
use App\Models\ApprovalStep;
use App\Models\ApprovalWorkflow;
use App\Models\Role;
use App\Constants\RoleConstant;
use Illuminate\Database\Seeder;

class ApprovalWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workflow = ApprovalWorkflow::updateOrCreate(
            ['name' => 'flow pr init'],
            [
                'description' => 'Initial Purchase Requisition Flow',
                'version' => 1,
                'status' => 'active',
            ]
        );

        $requesterRole = Role::where('name', RoleConstant::VESSEL_CREW_REQUESTER)->first();
        $technicalRole = Role::where('name', RoleConstant::TECHNICAL_APPROVER)->first();
        $procurementRole = Role::where('name', RoleConstant::PROCUREMENT_OFFICER)->first();

        $steps = [
            [
                'step_order' => 1,
                'role_id' => $requesterRole?->id,
                'name' => 'request',
                'pr_status' => PrStatusConstant::SUBMITTED,
                'status' => 'active',
            ],
            [
                'step_order' => 2,
                'role_id' => $technicalRole?->id,
                'name' => 'technical approver',
                'pr_status' => PrStatusConstant::WAITING_APPROVAL,
                'status' => 'active',
            ],
            [
                'step_order' => 3,
                'role_id' => $procurementRole?->id,
                'name' => 'purchasing process po',
                'pr_status' => PrStatusConstant::CONVERTED_TO_PO,
                'status' => 'active',
            ],
        ];

        // Cleansing master data: remove obsolete step orders from this workflow.
        ApprovalStep::where('approval_workflow_id', $workflow->id)
            ->whereNotIn('step_order', collect($steps)->pluck('step_order')->all())
            ->delete();

        foreach ($steps as $stepData) {
            if ($stepData['role_id']) {
                ApprovalStep::updateOrCreate(
                    [
                        'approval_workflow_id' => $workflow->id,
                        'step_order' => $stepData['step_order'],
                    ],
                    $stepData
                );
            }
        }
    }
}
