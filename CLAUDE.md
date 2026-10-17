# Formular (FML Intertrade CRM) — Project Memory

ระบบ CRM/เทรด-ส่งออก (Laravel). โฟลว์หลัก: ใบเสนอราคา → Proforma Invoice (PI) →
Packing (import จากโรงงาน) → แยกเป็น Invoice (บัญชี) + Packing List (จัดส่ง) → เก็บเงินตามเรต.

## Stack
- Laravel 12, PHP **8.4**
- PDF: `niklasravnsborg/laravel-pdf` (เป็น **mPDF** — ใช้ `{PAGENO}/{nbpg}`, `<htmlpageheader>` ได้)
- DataTables (yajra) สำหรับตาราง, Blade + layout `admin.layouts.default`
- ตัวแปลงเลขเป็นตัวอักษร: `App\Help::numberToWords($n, $symbol)` (อังกฤษ, uppercase)
- Seeder อยู่ใน `database/seeds/` (classmap, **ไม่มี namespace**) — รัน `composer dump-autoload` หลังเพิ่ม seeder ใหม่

## การ Deploy (สำคัญ — server เป็น Plesk)
- **Webhook auto-pull จาก branch `main`** → deploy อัตโนมัติไปที่ `/crm.fmlintertrade.in.th`
- Deployment mode = Automatic (ทุก commit บน `main`)
- **Post-deploy actions รันอัตโนมัติ** (ไม่ต้องสั่งให้ user รันเอง):
  `artisan config:clear`, `cache:clear`, `view:clear`, `route:clear`, **`migrate`**
- ⇒ migration + clear cache รันเองตอน deploy จาก `main` — เวลาบอกวิธีใช้ **ไม่ต้องสั่ง user รัน migrate/clear เอง** ถ้าของขึ้น `main` แล้ว
- Branch งานพัฒนา: `claude/project-workflow-issues-CvslC` — **ต้อง merge เข้า `main` ก่อนถึงจะ deploy จริง**
  (push แค่ feature branch = ยังไม่ขึ้น production)

## ระบบเมนู/สิทธิ์ (DB-driven)
- สิทธิ์ผูกกับ `menus.url` ผ่าน `crud_menus` (ต่อ user): c/r/u/d/p/export
- `Menu::scopeActive()` กรองเมนูในแถบข้างตาม crud_menus ที่ `readed='T'`
- `UserPermission::getMyPermissions()` cache `user_perms_{id}` 3600s → แก้สิทธิ์แล้วต้อง `cache:clear`
- `Help::CheckPermissionMenu($menuUrl, 'r'|'u'|...)` ใช้เช็คในทุก controller (`$current_menu`)
- menus ไม่มี timestamps; crud_menus มี timestamps

## จุดที่แก้ไปแล้ว (บน feature branch)
- PI PDF (`pdf_factory.blade.php`) + FA/EXPORT ORDER FORM (`proforma_invoice_pdf.blade.php`) ทำใหม่ให้ตรงตัวอย่าง
- Packing import (`PackingListImportService`): คอลัมน์ QTY=T, CBM=S, UOM=U, auto-detect แถวเริ่ม, normalize เลข PI (FA-/PI-/ขีด)
- Invoice/Packing List PDF (`_pdf_pl_document.blade.php`): 2 variant (accounting=Invoice, customer=Packing List), ตารางเส้นเต็มกริด, marks ในคอลัมน์, จัดกลุ่ม category, หัวซ้ำทุกหน้า
- หน้า Invoice แยก (`InvoiceController` + `admin/Invoice/*`): แก้ราคา (`unit_price` ใน packing_form_details) + ดูเฉพาะใบ Invoice
- แก้ FK `proforma_invoices.quotation_id` (เดิมผูกผิดไป admin_users → แก้เป็น quotations)
- `DemoDocumentsSeeder` (ข้อมูลจำลอง QO/PI), `AddInvoiceMenuSeeder` (เมนู Invoice)

## โจทย์ที่ยังค้าง
- Flow "ปิดงาน" (ยังไม่มี state ปิดงานหลังผลิต/ส่ง/เก็บเงินครบ)
- ระบบล็อคเรตเงิน / เลือกเรตตอนเก็บเงิน (BOT rate มีเก็บแล้วแต่หน้าเก็บเงินยังพิมพ์เรตมือ)
- แก้ export Fic2Fi (customer_part→cus_code, ชื่อ sale, doc no เป็น FA)
