<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BotExchangeRateSnapshot extends Model
{
    protected $fillable = [
        'result_timestamp',
        'api_name',
        'query_start_period',
        'query_end_period',
        'header_last_updated',
        'report_name_eng',
        'report_name_th',
        'report_uoq_name_eng',
        'report_uoq_name_th',
        'report_source_of_data',
        'report_remark',
        'raw_result_json',
    ];

    protected $casts = [
        'result_timestamp' => 'datetime',
        'query_start_period' => 'date',
        'query_end_period' => 'date',
        'header_last_updated' => 'date',
        'report_source_of_data' => 'array',
        'report_remark' => 'array',
        'raw_result_json' => 'array',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(BotExchangeRateLine::class, 'bot_exchange_rate_snapshot_id');
    }
}
