<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginActivity extends Model
{
    use HasFactory;

    protected $table = 'login_activities';

    protected $fillable = [
        'user_id',
        'identifier',
        'ip_address',
        'user_agent',
        'location',
        'latitude',
        'longitude',
        'google_location',
        'is_successful',
        'login_at',
    ];

    protected $casts = [
        'is_successful' => 'boolean',
        'login_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
