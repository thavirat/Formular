<?php

use App\Http\Controllers\Api\BotExchangeRateController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
| อัตราแลกเปลี่ยนเฉลี่ยรายวัน (BOT Stat-ExchangeRate v2)
| GET /api/exchange-rates/daily-average?start_period=2026-04-30&end_period=2026-04-30
| Token: .env BOT_GATEWAY_API_TOKEN (หรือใส่ "Bearer ..." ทั้งสตริงก็ได้)
*/
Route::get('/exchange-rates/daily-average', [BotExchangeRateController::class, 'dailyAverage']);
