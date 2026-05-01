<?php

namespace Database\Seeders;

use App\Models\ApprovalStep;
use App\Models\ApprovalWorkflow;
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

        $steps = [
            [
                'step_order' => 1,
                'role_id' => 11,
                'name' => 'request',
                'status' => 'active',
            ],
            [
                'step_order' => 2,
                'role_id' => 10,
                'name' => 'technical approver',
                'status' => 'active',
            ],
            [
                'step_order' => 3,
                'role_id' => 9,
                'name' => 'purchasing process po',
                'status' => 'active',
            ],
        ];

        foreach ($steps as $stepData) {
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
