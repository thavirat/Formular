<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * ตั้งภาษาของหน้าบ้าน catalog
 *
 * - ค่าเริ่มต้น = อังกฤษ (en)
 * - สลับได้ด้วย ?lang=en|th แล้วจำไว้ใน session (catalog_locale)
 * - แยกจากระบบภาษาของ CRM (WebMultiLanguage) โดยสิ้นเชิง
 */
class SetCatalogLocale
{
    private const AVAILABLE = ['en', 'th'];
    private const DEFAULT   = 'en';

    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->query('lang'), self::AVAILABLE, true)) {
            $request->session()->put('catalog_locale', $request->query('lang'));
        }

        $locale = $request->session()->get('catalog_locale', self::DEFAULT);
        if (!in_array($locale, self::AVAILABLE, true)) {
            $locale = self::DEFAULT;
        }

        app()->setLocale($locale);
        view()->share('lang', $locale);

        return $next($request);
    }
}
