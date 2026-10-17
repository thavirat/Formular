<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

/**
 * Controller ของหน้าบ้าน catalog (catalog.fmlintertrade.in.th)
 *
 * ขอบเขต (boundary):
 *   - query เฉพาะสินค้า active = 'T'
 *   - โชว์เฉพาะ field ปลอดภัย: code, name_th/en, หมวด (sub_category), ขนาด
 *     ไม่โชว์ cost / price / ข้อมูลภายใน CRM
 *   - ห้ามเรียก service/controller ของ CRM (App\Http\Controllers\Admin\*)
 */
class CatalogController extends Controller
{
    /** หน้าแรก: hero + ค้นหา + หมวดหมู่ + สินค้าตัวอย่าง */
    public function index()
    {
        $categories = $this->categoriesWithCount(8);

        $featured = Product::where('active', 'T')
            ->with('subCategory')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('catalog.index', [
            'categories' => $categories,
            'featured'   => $featured,
        ]);
    }

    /** หน้ารายการ: ค้นหา (รหัส/ชื่อ) + กรองหมวด + แบ่งหน้า */
    public function products(Request $request)
    {
        $q   = trim((string) $request->query('q', ''));
        $sub = $request->query('sub');

        $query = Product::where('active', 'T')->with(['subCategory', 'brand']);

        if ($sub) {
            $query->where('sub_category_id', $sub);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('code', 'like', "%{$q}%")
                    ->orWhere('name_th', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%");
            });
        }

        $products = $query->orderBy('name_en')->paginate(24)->withQueryString();

        return view('catalog.list', [
            'products'   => $products,
            'categories' => $this->categoriesWithCount(30),
            'q'          => $q,
            'sub'        => $sub ? (int) $sub : null,
        ]);
    }

    /** หน้ารายละเอียดสินค้า */
    public function show(Product $product)
    {
        abort_unless($product->active === 'T', 404);

        $product->load(['subCategory', 'brand', 'fitments.make', 'fitments.model', 'oeNumbers']);

        $related = Product::where('active', 'T')
            ->where('sub_category_id', $product->sub_category_id)
            ->where('id', '!=', $product->id)
            ->limit(3)
            ->get();

        return view('catalog.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }

    /** หมวด (sub_category) ที่มีสินค้า active พร้อมจำนวน เรียงจากมากไปน้อย */
    private function categoriesWithCount(int $limit)
    {
        return SubCategory::withCount(['products' => fn ($q) => $q->where('active', 'T')])
            // ข้ามหมวดที่ไม่มีชื่อ (ค่าว่าง หรือสตริง "NULL" ในดีบี)
            ->where(function ($w) {
                $w->whereNotNull('name_th')->whereNotIn('name_th', ['', 'NULL'])
                    ->orWhere(function ($x) {
                        $x->whereNotNull('name_en')->whereNotIn('name_en', ['', 'NULL']);
                    });
            })
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();
    }
}
