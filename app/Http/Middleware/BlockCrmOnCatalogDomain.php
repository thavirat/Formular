<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * กันไม่ให้ route ของ CRM (web / admin / install / api) ตอบสนองเมื่อถูกเรียก
 * ผ่านโดเมน catalog สาธารณะ
 *
 * ที่มา: route ของ CRM ไม่มี domain constraint จึง match ทุก host รวมถึง
 * catalog.<domain> ด้วย ทำให้ /admin/login โผล่บนหน้าบ้าน catalog
 *
 * วิธีกัน: ถ้า host ที่เข้ามา = config('app.catalog_domain') ให้ตอบ 404
 * (เฉพาะ group ของ CRM — route 'catalog' ไม่ได้ใส่ middleware ตัวนี้ จึงยังทำงานปกติ)
 *
 * ปลอดภัย: ทำงานเฉพาะเมื่อ host ตรงกับโดเมน catalog เท่านั้น
 * โดเมนปกติของ CRM ไม่ได้รับผลกระทบใด ๆ
 */
class BlockCrmOnCatalogDomain
{
    public function handle(Request $request, Closure $next)
    {
        $catalogDomain = config('app.catalog_domain');

        if (!empty($catalogDomain) && $request->getHost() === $catalogDomain) {
            abort(404);
        }

        return $next($request);
    }
}
