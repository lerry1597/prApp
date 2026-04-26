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

#[Fillable(['username', 'name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

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
                ->whereIn('name', ['super_admin', 'admin'])
                ->exists();
        }

        if ($panel->getId() === 'app') {
            return $this->roles()
                ->whereIn('name', [
                    'procurement_officer',
                    'technical_approver',
                    'vessel_crew_requester'
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
