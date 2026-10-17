# Catalog (catalog.fml) — Gap Analysis & Implementation Plan

> ดีไซน์อ้างอิง: `formula-catalog-design.html` (FORMULA — Automotive A/C Online Catalogue)
> สถานะ: **วางแผน** — ยังไม่เริ่ม build ฟีเจอร์ (route/middleware scaffold ทำไว้แล้วแบบ additive, ปิดอยู่)
> DB ที่อ้างอิง: `fml_demo` (local) / production = `fml_2569`

---

## 1. สรุปช่องว่าง (ดีไซน์ ↔ ข้อมูลในระบบ)

| ดีไซน์ต้องการ | ในระบบเรา | สถานะ |
|---|---|---|
| รหัสสินค้า | `products.code` | ✅ มีครบ |
| ชื่อ TH / EN | `name_th` / `name_en` | ✅ มีครบ (9,579 ตัว) |
| หมวดหมู่ (คอนเดนเซอร์/คอมเพรสเซอร์/อีวาฯ/ดรายเออร์) | `tb_sub_categories` | ✅ มีครบ & ละเอียดกว่า (26+ ชนิด) — map ไป **sub_category** ไม่ใช่ category |
| สเปก/ขนาด | `width/height/length/weight/cube` | ✅ มี |
| ยี่ห้อรถ | `brand_id` → `tb_brand_products` | ⚠️ ว่าง 63% + ปนยี่ห้อรถกับยี่ห้ออะไหล่ (DENSO/SANDEN/STD) |
| รุ่นรถ (model) | — | ❌ ไม่มี |
| ปีรถ (year) | — | ❌ ไม่มี |
| เลข OE | `part_no` (ว่างทั้งหมด) | ❌ ไม่มีข้อมูล |
| รูปสินค้า | — | ❌ ไม่มี field รูป |
| คำอธิบาย public | `content` = decimal (ไม่ใช่ข้อความ) | ❌ ไม่มี |
| flag เผยแพร่หน้าบ้าน | `active='T'` (= ภายใน, เกือบทุกตัว) | ⚠️ ไม่มี flag แยก |

### ประเด็นสำคัญ
1. **ฟีเจอร์เด่น "ค้นหาตามรุ่นรถ (ยี่ห้อ→รุ่น→ปี)" ทำเต็มรูปแบบไม่ได้** — มีแค่ยี่ห้อ (และว่าง 63%) ไม่มี model/year
2. **"เลข OE" ไม่มีข้อมูล** — ต้องเพิ่มตาราง + import
3. **ไม่มีรูปสินค้า** — ต้องเพิ่มที่เก็บรูป (มี `unisharp/laravel-filemanager` ใช้ต่อได้)
4. **9,579 รายการมีวัตถุดิบปน** (สแตนเลสเส้น, แป๊ปอลูมิเนียมม้วน) — ไม่เหมาะโชว์ลูกค้า ต้อง curate
5. Data quality: `tb_categories` แทบไม่ถูกใช้ (ทุกตัว = CAR AIR CONDITIONER PARTS); taxonomy จริงอยู่ที่ `sub_category`

---

## 2. สิ่งที่ต้องเพิ่ม (schema ใหม่ — additive ทั้งหมด)

| # | ตาราง/คอลัมน์ | รายละเอียด | ใช้โดย phase |
|---|---|---|---|
| A | `products.catalog_visible` (bool, default 0) | flag ว่าโชว์บนหน้าบ้านไหม — default ปิด = ไม่มีอะไรหลุดจนกว่าจะเลือก | 1 |
| B | `products.catalog_desc_th/en` (text, nullable) | คำอธิบายสำหรับ public | 1–2 |
| C | `tb_vehicle_models` (make_id→brand, name) | รุ่นรถต่อยี่ห้อ | 2 |
| D | `tb_product_fitments` (product_id, make_id, model_id, year_from, year_to) | mapping สินค้า↔รถ (many-to-many) | 2 |
| E | `tb_product_oe_numbers` (product_id, oe_number, maker nullable) | เลข OE (1 สินค้า : หลายเลข) | 3 |
| F | `tb_product_images` (product_id, path, is_primary, sort) | รูปสินค้า | 4 |

> ทุกอันเป็น **ตารางใหม่ / คอลัมน์ nullable** — ไม่แตะคอลัมน์เดิมของ CRM

---

## 3. โรดแมปเป็น Phase

### Phase 0 — Scaffold ✅ (ทำแล้ว, additive, ปิดอยู่)
- `config/app.php` → `catalog_domain`
- `RouteServiceProvider::mapCatalogRoutes()` (return ทันทีถ้าไม่เซ็ต env)
- Kernel middleware group `catalog` + `routes/catalog.php` + `Controllers/Catalog` + `CatalogProductResource`

### Phase 1 — MVP ด้วยข้อมูลที่มีอยู่ (ไม่ต้องรอข้อมูลใหม่)
- migration A (`catalog_visible`) + เครื่องมือ mark visible (bulk ตาม sub_category หรือเลือกเอง)
- Front-end: แปลงดีไซน์ → Blade + endpoint จริง
  - เรียกดู/กรองตาม **sub_category**, ค้นหา **code / name_th / name_en**, กรองตามยี่ห้อ (เท่าที่มี brand)
  - หน้า detail: ชื่อ/รหัส/หมวด/ขนาด
  - ซ่อน UI ส่วน รุ่น/ปี/OE/รูป ไว้ก่อน (degrade สวย ๆ)
- **ได้เว็บใช้งานจริงโดยไม่ต้องกรอกข้อมูลใหม่**

### Phase 2 — Vehicle fitment (ต้องมีข้อมูล)
- migration C+D + admin CRUD/import → เปิด "ค้นหาตามรุ่นรถ" make→model→year

### Phase 3 — OE cross-reference (ต้องมีข้อมูล)
- migration E + import → เปิดค้นหาด้วยเลข OE

### Phase 4 — รูปสินค้า (ต้องมีไฟล์รูป)
- migration F + อัปโหลดผ่าน filemanager → แทน SVG placeholder

### Phase 5 — spec/น้ำยา เพิ่มเติม, ช่องทางติดต่อจริง, SEO

---

## 4. ผลกระทบ prod & จังหวะทำ
- migration ทั้งหมด **additive** (ตารางใหม่/คอลัมน์ nullable) — ไม่ลบ ไม่แก้คอลัมน์เดิม
- `products` มี ~9,579 แถว: เพิ่มคอลัมน์ nullable บน MariaDB 10.6 เร็ว แต่ยัง**ควรรันช่วง maintenance (หลังเลิกงาน)** พร้อม:
  1. เพิ่ม Apache vhost `catalog.fml` → `public/` เดียวกับ CRM (reload Apache = กระทบ CRM ชั่วขณะ)
  2. `.env` prod: `CATALOG_DOMAIN=catalog.fml`
  3. `php artisan config:cache && php artisan route:cache` (ถ้า prod ใช้ cache)
- โค้ด (ไม่รวมขั้นเปิดสวิตช์) deploy เวลางานได้ เพราะ catalog ปิดจนกว่าจะเซ็ต env

---

## 5. คำถาม/สิ่งที่ต้องตัดสินใจ
1. **ขอบเขตสินค้าที่จะเผยแพร่** (curate ด้วย flag / โชว์ทั้งหมด / เฉพาะบางหมวด) — ยังไม่เคาะ
2. **แหล่งข้อมูล fitment (รุ่น/ปี) + OE** — มีไฟล์/Excel ให้ import, หรือให้สร้าง schema รอกรอก, หรือตัดฟีเจอร์ออกก่อน? *(รอคำตอบ)*
3. **brand_id** ควรแยก "ยี่ห้อรถ" ออกจาก "ยี่ห้ออะไหล่" ไหม (data quality)
4. แหล่งรูปสินค้า
