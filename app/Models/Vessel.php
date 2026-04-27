<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vessel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'vessel_category_id',
        'name',
        'code',
        'vessel_type',
        'flag',
        'status',
        'description',
    ];

    public function vesselCategory(): BelongsTo
    {
        return $this->belongsTo(VesselCategory::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function prDetails(): HasMany
    {
        return $this->hasMany(PrDetail::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
