<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pr_header_id',
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
        'request_date_client',
        'required_date',
        'expired_date',
        'latitude',
        'longitude',
        'delivery_address',
        'description',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'request_date_client' => 'datetime',
        'required_date' => 'datetime',
        'expired_date' => 'datetime',
        'issue_date' => 'datetime',
        'ref_date' => 'datetime',
    ];

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(PrHeader::class, 'pr_header_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
