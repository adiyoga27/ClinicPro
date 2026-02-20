<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'plan',
        'price',
        'started_at',
        'expired_at',
        'status',
        'midtrans_order_id',
        'midtrans_transaction_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expired_at?->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expired_at?->isPast() ?? true;
    }

    public static function planPrices(): array
    {
        return [
            'basic' => 299000,
            'professional' => 599000,
            'enterprise' => 999000,
        ];
    }
}
