<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatalogProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Controller ของหน้าบ้าน catalog (catalog.fml)
 *
 * ขอบเขต (boundary):
 *   - ใช้ได้เฉพาะ model/relation ที่จำเป็นต่อการโชว์สินค้า
 *   - ส่งข้อมูลออกผ่าน CatalogProductResource เสมอ (whitelist field)
 *   - query เฉพาะสินค้าที่ active = 'T' เท่านั้น
 *   - ห้ามเรียก service/controller ของ CRM (App\Http\Controllers\Admin\*)
 */
class CatalogController extends Controller
{
    public function index()
    {
        return view('catalog.index');
    }

    public function products(Request $request)
    {
        $products = Product::query()
            ->where('active', 'T')
            ->with('category')
            ->orderBy('name_en')
            ->paginate(24);

        // ส่งออกผ่าน Resource — ไม่หลุด cost/ข้อมูลภายใน
        return CatalogProductResource::collection($products);
    }

    public function show(Product $product)
    {
        abort_unless($product->active === 'T', 404);

        $product->load('category');

        return new CatalogProductResource($product);
    }
}
