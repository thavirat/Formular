<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Catalog Routes (หน้าบ้านสาธารณะ — catalog.fml)
|--------------------------------------------------------------------------
|
| ไฟล์นี้จะถูกโหลดก็ต่อเมื่อเซ็ต CATALOG_DOMAIN ใน .env แล้วเท่านั้น
| (ดู App\Providers\RouteServiceProvider::mapCatalogRoutes)
|
| กติกา:
|   - controller อยู่ใน App\Http\Controllers\Catalog เท่านั้น ห้ามเรียก controller ของ CRM
|   - ส่งข้อมูลออกผ่าน Resource ที่ whitelist field เสมอ (อย่า return model ดิบ)
|   - ไม่มี route ไหนเปิดเผย cost / ข้อมูลภายในของ CRM
|
*/

Route::name('catalog.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Catalog\CatalogController::class, 'index'])
        ->name('home');

    Route::get('/products', [\App\Http\Controllers\Catalog\CatalogController::class, 'products'])
        ->name('products');

    Route::get('/products/{product}', [\App\Http\Controllers\Catalog\CatalogController::class, 'show'])
        ->name('products.show');
});
