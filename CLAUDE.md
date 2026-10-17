# Formular (FML Intertrade CRM) — Project Memory

ระบบ CRM/เทรด-ส่งออก (Laravel). โฟลว์หลัก: ใบเสนอราคา → Proforma Invoice (PI) →
Packing (import จากโรงงาน) → แยกเป็น Invoice (บัญชี) + Packing List (จัดส่ง) → เก็บเงินตามเรต.

## ⭐ Workflow (กฎสำคัญ — ห้ามลืม)
- **ทำงานบน branch แยกเสมอ** ห้าม commit/push ตรงเข้า `main`
- **เมื่อทำเสร็จต้องถามทุกครั้ง** ว่าจะ merge เข้า `main` ไหม — **ห้าม merge เอง**
- **ก่อน merge ต้องเช็ค divergence กับ main ทุกครั้ง**: `main` เดินหน้าเร็วมาก (มีหลายเซสชันทำคู่ขนาน + hotfix production) — การ merge branch เก่าทับอาจย้อน hotfix/งานใหม่ → เว็บพัง (เพราะ auto-deploy)
- **เริ่มงานใหม่ ให้ branch จาก `main` ล่าสุดเสมอ** (`git fetch origin main` → `git checkout -b <ชื่อ> origin/main`) ไม่ต่อยอดจาก branch เก่า
- ก่อนลงมือ ให้ `git log --oneline origin/main` ดูของใหม่/hotfix ก่อน เพราะงานอาจถูกทำไปแล้วบน main

## Stack
- Laravel 12, PHP **8.4**
- PDF: `niklasravnsborg/laravel-pdf` (เป็น **mPDF** — ใช้ `{PAGENO}/{nbpg}`, `<htmlpageheader>` ได้)
  - ระวัง: อย่าใส่ nested `<table>` ในเซลล์ที่ตัด border-top/bottom (เคยทำ Invoice PDF 500 บน production)
- DataTables (yajra) สำหรับตาราง, Blade + layout `admin.layouts.default`
- ตัวแปลงเลขเป็นตัวอักษร: `App\Help::numberToWords($n, $symbol)` (อังกฤษ, uppercase)
- Seeder อยู่ใน `database/seeds/` (classmap, **ไม่มี namespace**) — รัน `composer dump-autoload` หลังเพิ่ม seeder ใหม่

## การ Deploy (สำคัญ — server เป็น Plesk)
- **Webhook auto-pull จาก branch `main`** → deploy อัตโนมัติไปที่ `/crm.fmlintertrade.in.th`
- Deployment mode = Automatic (ทุก commit บน `main`)
- **Post-deploy actions รันอัตโนมัติ** (ไม่ต้องสั่งให้ user รันเอง):
  `artisan config:clear`, `cache:clear`, `view:clear`, `route:clear`, **`migrate`**
  (แต่ **ไม่รัน `db:seed`** — seeder ต้องสั่งเองบน server ครั้งเดียว)
- ⇒ ของขึ้น `main` แล้ว migration + clear cache รันเอง — เวลาบอกวิธีใช้ **ไม่ต้องสั่ง user รัน migrate/clear เอง**
- push แค่ branch อื่น = ยังไม่ขึ้น production (ต้อง merge เข้า main ก่อน)

## ระบบเมนู/สิทธิ์ (DB-driven)
- สิทธิ์ผูกกับ `menus.url` ผ่าน `crud_menus` (ต่อ user): c/r/u/d/p/export
- `Menu::scopeActive()` กรองเมนูในแถบข้างตาม crud_menus ที่ `readed='T'`
- `UserPermission::getMyPermissions()` cache `user_perms_{id}` 3600s → แก้สิทธิ์แล้วต้อง `cache:clear`
- `Help::CheckPermissionMenu($menuUrl, 'r'|'u'|...)` ใช้เช็คในทุก controller (`$current_menu`)
- menus ไม่มี timestamps; crud_menus มี timestamps

## หมายเหตุสถานะโค้ด
`main` มีงานหลักครบและเดินหน้าเร็ว (เช่น CreditNote, PI import แบบ CSV, hotfix PDF หลายรอบ)
— **ก่อนทำอะไร ให้ดู git log + ไฟล์จริงบน main เสมอ** อย่าเชื่อว่าอะไรยังไม่ถูกทำ

## โจทย์ที่ยังค้าง (เช็คกับ main ก่อนว่าทำแล้วหรือยัง)
- Flow "ปิดงาน" (state ปิดงานหลังผลิต/ส่ง/เก็บเงินครบ)
- ระบบล็อคเรตเงิน / เลือกเรตตอนเก็บเงิน (BOT rate เก็บแล้ว แต่หน้าเก็บเงินอาจยังพิมพ์เรตมือ)
