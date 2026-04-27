<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalWorkflow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'version',
        'status',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class);
    }

    public function prHeaders(): HasMany
    {
        return $this->hasMany(PrHeader::class);
    }
}
