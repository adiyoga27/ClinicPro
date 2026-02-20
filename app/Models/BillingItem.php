<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'name',
        'qty',
        'unit_price',
        'amount',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (BillingItem $item) {
            $item->amount = $item->qty * $item->unit_price;
        });
    }

    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class);
    }
}
