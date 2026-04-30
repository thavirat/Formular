<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BotExchangeRateLine;
use App\Models\BotExchangeRateSnapshot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Proxy ไปยัง Bank of Thailand API Gateway — อัตราแลกเปลี่ยนเฉลี่ยรายวัน (DAILY_AVG_EXG_RATE) และบันทึกลงฐานข้อมูล
 */
class BotExchangeRateController extends Controller
{
    private const BOT_DAILY_AVG_URL = 'https://gateway.api.bot.or.th/Stat-ExchangeRate/v2/DAILY_AVG_EXG_RATE/';

    public function dailyAverage(Request $request): JsonResponse
    {
        $token = env('BOT_GATEWAY_API_TOKEN');
        if ($token === null || $token === '') {
            return response()->json([
                'error' => 'BOT gateway token is not configured',
                'hint' => 'Set BOT_GATEWAY_API_TOKEN in .env',
            ], 503);
        }

        $validated = $request->validate([
            'start_period' => ['nullable', 'date_format:Y-m-d'],
            'end_period' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $start = $validated['start_period'] ?? now()->format('Y-m-d');
        $end = $validated['end_period'] ?? $start;

        $client = Http::acceptJson()
            ->timeout(45)
            ->connectTimeout(15);

        $token = trim((string) $token);
        if (str_starts_with($token, 'Bearer ')) {
            $client = $client->withHeaders(['Authorization' => $token]);
        } else {
            $client = $client->withToken($token);
        }

        $response = $client->get(self::BOT_DAILY_AVG_URL, [
            'start_period' => $start,
            'end_period' => $end,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Upstream BOT API request failed',
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ], $response->status() >= 400 ? $response->status() : 502);
        }

        $payload = $response->json();
        if (!is_array($payload)) {
            return response()->json(['error' => 'Invalid JSON from upstream'], 502);
        }

        try {
            $persist = $this->persistBotDailyResponse($payload, $start, $end);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to persist exchange rate data',
                'message' => $e->getMessage(),
                'result' => $payload['result'] ?? $payload,
            ], 500);
        }

        return response()->json(array_merge($payload, [
            '_persisted' => $persist,
        ]));
    }

    /**
     * @return array{snapshot_id: int, lines_count: int}
     */
    private function persistBotDailyResponse(array $payload, string $queryStart, string $queryEnd): array
    {
        $result = $payload['result'] ?? null;
        if (!is_array($result)) {
            throw new \InvalidArgumentException('Missing result key in BOT response');
        }

        $data = $result['data'] ?? [];
        $header = is_array($data) ? ($data['data_header'] ?? []) : [];
        $details = is_array($data) ? ($data['data_detail'] ?? []) : [];
        if (!is_array($details)) {
            $details = [];
        }

        $resultTs = null;
        if (!empty($result['timestamp'])) {
            try {
                $resultTs = Carbon::parse($result['timestamp']);
            } catch (\Throwable) {
                $resultTs = null;
            }
        }

        $headerLastUpdated = null;
        if (!empty($header['last_updated'])) {
            try {
                $headerLastUpdated = Carbon::parse($header['last_updated'])->toDateString();
            } catch (\Throwable) {
                $headerLastUpdated = null;
            }
        }

        return DB::transaction(function () use (
            $result,
            $resultTs,
            $header,
            $headerLastUpdated,
            $details,
            $queryStart,
            $queryEnd
        ) {
            $snapshot = BotExchangeRateSnapshot::create([
                'result_timestamp' => $resultTs,
                'api_name' => $result['api'] ?? null,
                'query_start_period' => $queryStart,
                'query_end_period' => $queryEnd,
                'header_last_updated' => $headerLastUpdated,
                'report_name_eng' => $header['report_name_eng'] ?? null,
                'report_name_th' => $header['report_name_th'] ?? null,
                'report_uoq_name_eng' => $header['report_uoq_name_eng'] ?? null,
                'report_uoq_name_th' => $header['report_uoq_name_th'] ?? null,
                'report_source_of_data' => $header['report_source_of_data'] ?? null,
                'report_remark' => $header['report_remark'] ?? null,
                'raw_result_json' => $result,
            ]);

            $count = 0;
            foreach ($details as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $code = $row['currency_id'] ?? null;
                if ($code === null || $code === '') {
                    continue;
                }

                BotExchangeRateLine::create([
                    'bot_exchange_rate_snapshot_id' => $snapshot->id,
                    'period' => $this->emptyStringToNull($row['period'] ?? null),
                    'currency_code' => (string) $code,
                    'currency_name_th' => $this->emptyStringToNull($row['currency_name_th'] ?? null),
                    'currency_name_eng' => $this->emptyStringToNull($row['currency_name_eng'] ?? null),
                    'buying_sight' => $this->decimalOrNull($row['buying_sight'] ?? null),
                    'buying_transfer' => $this->decimalOrNull($row['buying_transfer'] ?? null),
                    'selling' => $this->decimalOrNull($row['selling'] ?? null),
                    'mid_rate' => $this->decimalOrNull($row['mid_rate'] ?? null),
                ]);
                $count++;
            }

            return [
                'snapshot_id' => $snapshot->id,
                'lines_count' => $count,
            ];
        });
    }

    private function emptyStringToNull(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_string($value) ? $value : (string) $value;
    }

    private function decimalOrNull(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return (string) $value;
        }
        $normalized = str_replace(',', '', (string) $value);
        if ($normalized === '' || !is_numeric($normalized)) {
            return null;
        }

        return $normalized;
    }
}
