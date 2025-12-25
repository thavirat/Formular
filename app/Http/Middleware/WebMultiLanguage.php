<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WebMultiLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // ดึงค่าภาษาจาก URL segment ที่ 2
        $locale = $request->segment(2);
        $availableLocales = config('app.available_locales', ['en', 'th']); // แนะนำให้ใช้ชื่ออื่นที่ไม่ใช่ locales รวม

        // ตรวจสอบว่ามีภาษานี้ในระบบไหม
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.fallback_locale', 'en');
            $langUrl = '';
        } else {
            $langUrl = $locale . '/';
        }

        // ตั้งค่า Locale ให้กับระบบ Laravel
        app()->setLocale($locale);

        // เก็บค่าไว้ใน config (ตัวที่ Laravel ใช้หลักๆ คือ app.locale ไม่มี s)
        config(['app.locale' => $locale]);

        // แชร์ตัวแปรไปที่ View
        view()->share('lang', $locale);
        view()->share('lang_url', $langUrl);

        return $next($request);
    }
}
