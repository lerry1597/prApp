<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Constants\RoleConstant;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['username', 'name', 'email', 'password', 'vessel_id', 'id_employee', 'is_primary', 'user_code'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)
            ->withTimestamps();
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->isForceDeleting()) {
                $user->detailsUser()->forceDelete();
            } else {
                $user->detailsUser()->delete();
            }
        });

        static::restoring(function ($user) {
            $user->detailsUser()->restore();
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 3. Tambahkan method wajib ini
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->roles()
                ->whereIn('name', [
                    RoleConstant::SUPER_ADMIN,
                    RoleConstant::ADMIN,
                ])
                ->exists();
        }

        if ($panel->getId() === 'app') {
            return $this->roles()
                ->whereIn('name', [
                    RoleConstant::PROCUREMENT_OFFICER,
                    RoleConstant::TECHNICAL_APPROVER,
                    RoleConstant::VESSEL_CREW_REQUESTER,
                ])
                ->exists();
        }

        return true;
    }

    public function detailsUser()
    {
        return $this->hasOne(DetailsUser::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withPivot(['assigned_by', 'assigned_at'])
            ->withTimestamps();
    }
}
