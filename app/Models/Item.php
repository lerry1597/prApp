<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pr_detail_id',
        'vessel_id',
        'item_category_id',
        'no',
        'type',
        'size',
        'description',
        'quantity',
        'unit',
        'remaining',
    ];

    public function itemCategory(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function detail(): BelongsTo
    {
        return $this->belongsTo(PrDetail::class, 'pr_detail_id');
    }
}
