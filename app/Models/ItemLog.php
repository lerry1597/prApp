<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items_log';

    protected $fillable = [
        'batch_id',
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
        'item_priority',
        'step_order',
    ];

    public function itemCategory(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }
}
