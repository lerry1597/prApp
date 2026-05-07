<?php

namespace App\Models;

use App\Models\Item;
use App\Constants\RoleConstant;
use App\Constants\PrStatusConstant;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
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
        'po_number',
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

    protected $casts = [
        'approved_at' => 'datetime',
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

    /**
     * Scope untuk memfilter PR yang boleh dilihat oleh user tertentu berdasarkan role-nya.
     */
    public function scopeVisibleToUser(Builder $query, User $user): Builder
    {
        // Super Admin & Admin melihat semua
        if ($user->roles()->whereIn('name', [RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])->exists()) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            // 1. Jika sebagai Crew: Lihat milik sendiri yang masih aktif (dalam proses)
            if ($user->roles()->where('name', RoleConstant::VESSEL_CREW_REQUESTER)->exists()) {
                $q->orWhere(function ($sq) use ($user) {
                    $sq->where('requester_id', $user->id)
                       ->whereIn('pr_status', [
                           PrStatusConstant::WAITING_APPROVAL, 
                           PrStatusConstant::DRAFT, 
                           PrStatusConstant::SUBMITTED,
                           PrStatusConstant::PENDING
                       ]);
                });
            }

            // 2. Jika sebagai Approver: Lihat yang sedang menunggu approval dari role mereka
            $userRoleIds = $user->roles()->pluck('roles.id')->toArray();
            $q->orWhereIn('current_role_id', $userRoleIds);

            // 3. Jika sebagai Procurement: Lihat yang di step procurement atau approved tanpa PO
            if ($user->roles()->where('name', RoleConstant::PROCUREMENT_OFFICER)->exists()) {
                $procurementRole = Role::where('name', RoleConstant::PROCUREMENT_OFFICER)->first();
                if ($procurementRole) {
                    $q->orWhere('current_role_id', $procurementRole->id)
                      ->orWhere(function ($sq) {
                          $sq->where('pr_status', PrStatusConstant::APPROVED)
                             ->whereHas('items', function ($iq) {
                                 $iq->whereNull('po_number')->orWhere('po_number', '');
                             });
                      });
                }
            }
        });
    }
}
