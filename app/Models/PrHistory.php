<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;

class PrHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'batch_id',
        'payload',
        'pr_header_id',
        'pr_number',
        'pr_status',
        'requester_id',
        'department_id',
        'approver_id',
        'approval_workflow_id',
        'current_role_id',
        'current_step_id',
        'header_description',
        'priority',
        'document_no',
        'title',
        'issue_date',
        'rev_no',
        'ref_date',
        'document_type',
        'no',
        'needs',
        'vessel_id',
        'request_date',
        'required_date',
        'expired_date',
        'detail_description',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function prHeader(): BelongsTo
    {
        return $this->belongsTo(PrHeader::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function currentRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(ApprovalStep::class, 'current_step_id');
    }
}
