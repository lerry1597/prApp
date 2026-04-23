<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailsUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'details_user';

    protected $fillable = [
        'user_id',
        'department_id',
        'position_id',
        'phone_number',
        'address',
        'date_of_birth',
        'gender',
        'bio',
        'profile_picture',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
