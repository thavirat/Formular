<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotExchangeRateLine extends Model
{
    protected $fillable = [
        'bot_exchange_rate_snapshot_id',
        'period',
        'currency_code',
        'currency_name_th',
        'currency_name_eng',
        'buying_sight',
        'buying_transfer',
        'selling',
        'mid_rate',
    ];

    protected $casts = [
        'buying_sight' => 'decimal:10',
        'buying_transfer' => 'decimal:10',
        'selling' => 'decimal:10',
        'mid_rate' => 'decimal:10',
    ];

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(BotExchangeRateSnapshot::class, 'bot_exchange_rate_snapshot_id');
    }
}
