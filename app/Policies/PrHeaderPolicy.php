<?php

namespace App\Policies;

use App\Models\PrHeader;
use App\Models\User;
use App\Constants\RoleConstant;
use App\Constants\PrStatusConstant;

class PrHeaderPolicy
{
    /**
     * Tentukan apakah user bisa melihat daftar PR secara umum.
     */
    public function viewAny(User $user): bool
    {
        // Semua role di App Panel (Crew, Approver, Procurement) bisa melihat daftar (sesuai filternya masing-masing)
        return $user->roles()->whereIn('name', [
            RoleConstant::VESSEL_CREW_REQUESTER,
            RoleConstant::TECHNICAL_APPROVER,
            RoleConstant::PROCUREMENT_OFFICER,
            RoleConstant::SUPER_ADMIN,
            RoleConstant::ADMIN,
        ])->exists();
    }

    /**
     * Tentukan apakah user bisa melihat detail PR tertentu.
     */
    public function view(User $user, PrHeader $prHeader): bool
    {
        // Super Admin & Admin bisa lihat semua
        if ($user->roles()->whereIn('name', [RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])->exists()) {
            return true;
        }

        // Crew hanya bisa lihat PR miliknya
        if ($user->roles()->where('name', RoleConstant::VESSEL_CREW_REQUESTER)->exists()) {
            return $prHeader->requester_id === $user->id;
        }

        // Approver & Procurement bisa lihat jika PR tersebut sedang dalam tahap mereka (atau pernah mereka proses)
        // Untuk saat ini kita izinkan jika mereka memiliki role yang relevan
        return $user->roles()->whereIn('name', [
            RoleConstant::TECHNICAL_APPROVER,
            RoleConstant::PROCUREMENT_OFFICER,
        ])->exists();
    }

    /**
     * Tentukan apakah user bisa mengedit/update PR tertentu.
     */
    public function update(User $user, PrHeader $prHeader): bool
    {
        // Crew hanya bisa edit jika status masih Draft/Awal (jika ada logic draft)
        // Approver hanya bisa edit/approve jika current_role_id cocok dengan role mereka
        
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();
        
        return in_array($prHeader->current_role_id, $userRoleIds);
    }

    /**
     * Tentukan apakah user bisa menghapus PR.
     */
    public function delete(User $user, PrHeader $prHeader): bool
    {
        // Hanya pemilik (Crew) yang bisa hapus, dan hanya jika belum diproses (masih di tangan mereka)
        return $prHeader->requester_id === $user->id && $prHeader->pr_status === PrStatusConstant::WAITING_APPROVAL;
    }
}
