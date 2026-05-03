<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrHeader extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'batch_id',
        'pr_number',
        'pr_status',
        'requester_id',
        'department_id',
        'approver_id',
        'approval_workflow_id',
        'current_role_id',
        'current_step_id',
        'approved_at',
        'description',
    ];

    public function currentRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(ApprovalStep::class, 'current_step_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function approvalWorkflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class);
    }

    public function detail(): HasOne
    {
        return $this->hasOne(PrDetail::class);
    }

    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(
            Item::class,
            PrDetail::class,
            'pr_header_id', // FK on pr_details
            'pr_detail_id', // FK on items
        );
    }
}
