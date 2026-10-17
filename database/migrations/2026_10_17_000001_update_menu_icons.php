<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * ปรับไอคอนเมนูให้สื่อความหมายตรงกับแต่ละเมนู (เดิมส่วนใหญ่เป็น fa fa-th-large / ว่าง)
 * อ้างอิงด้วย url เป็นคีย์ (รองรับกรณีมีหลายแถว url เดียวกัน -> อัปเดตทุกแถว)
 */
return new class extends Migration
{
    /**
     * url => [ไอคอนใหม่ที่สื่อความหมาย, ไอคอนเดิม (ไว้ใช้ตอน rollback)]
     */
    private array $icons = [
        'Dashboard'           => ['fa fa-tachometer-alt',   'fa fa-th-large'],
        'Menu'                => ['fa fa-bars',              'fa fa-bars'],
        'Profile'             => ['fa fa-user-circle',       'fa fa-user-circle'],
        'AdminUser'           => ['fa fa-users-cog',         'fa fa-users'],
        'Setting'             => ['fa fa-cogs',              'fa fa-cogs'],
        'SettingSystem'       => ['fa fa-sliders-h',         'fa fa-cog'],
        'Permission'          => ['fa fa-user-shield',       'fa fa-lock'],
        'Logout'              => ['fa fa-sign-out-alt',      'fa fa-sign-out-alt'],
        'ProductCategory'     => ['fa fa-sitemap',           'fa fa-th-large'],
        'SubCategory'         => ['fa fa-stream',            'fa fa-th-large'],
        'ProductGroup'        => ['fa fa-object-group',      'fa fa-th-large'],
        'BrandProduct'        => ['fa fa-trademark',         'fa fa-th-large'],
        'DesignProduct'       => ['fa fa-pencil-ruler',      'fa fa-th-large'],
        'UnitProduct'         => ['fa fa-ruler-combined',    'fa fa-th-large'],
        'Product'             => ['fa fa-box',               'fa fa-th-large'],
        'CustomerCodeProduct' => ['fa fa-barcode',           'fa fa-th-large'],
        'Currency'            => ['fa fa-coins',             'fa fa-th-large'],
        'Customer'            => ['fa fa-user-tie',          'fa fa-th-large'],
        'CustomerLevel'       => ['fa fa-layer-group',       'fa fa-th-large'],
        'ContactChannel'      => ['fa fa-address-book',      'fa fa-th-large'],
        'AdminDepartment'     => ['fa fa-building',          'fa fa-th-large'],
        'Prefix'              => ['fa fa-heading',           ''],
        'QuotationStatus'     => ['fa fa-clipboard-check',   'fa fa-th-large'],
        'Incoterm'            => ['fa fa-file-contract',     'fa fa-th-large'],
        'CreditPayment'       => ['fa fa-credit-card',       'fa fa-th-large'],
        'Quotation'           => ['fa fa-file-invoice-dollar', 'fa fa-th-large'],
        'ApprovedQuotation'   => ['fa fa-check-double',      'fa fa-check-double'],
        'ProformaInvoice'     => ['fa fa-file-invoice',      'fa fa-th-large'],
        'Factory'             => ['fa fa-industry',          'fa fa-th-large'],
        'FactoryPayment'      => ['fa fa-money-check-alt',   'fa fa-industry'],
        'CustomerReceipt'     => ['fa fa-receipt',           'fa fa-hand-holding-usd'],
        'CustomerPayment'     => ['fa fa-hand-holding-usd',  'fa fa-th-large'],
        'Bank'                => ['fa fa-university',        'fa fa-th-large'],
        'BankAccount'         => ['fa fa-money-check',       'fa fa-th-large'],
        'PaymentMethod'       => ['fa fa-wallet',            'fa fa-th-large'],
        'PackingForm'         => ['fa fa-boxes',             ''],
        'AccountCredit'       => ['fa fa-piggy-bank',        'fa fa-th-large'],
        'ShipmentMethod'      => ['fa fa-ship',              'fa fa-ship'],
        'PriceSetting'        => ['fa fa-tags',              'fa fa-dollar-sign'],
        'CreditNote'          => ['fa fa-balance-scale',     'fa fa-file-invoice'],
        'AccountReceivable'   => ['fa fa-funnel-dollar',     'fa fa-hand-holding-usd'],
        'Report'              => ['fa fa-chart-line',        'fa fa-chart-line'],
    ];

    public function up(): void
    {
        foreach ($this->icons as $url => [$new, $old]) {
            DB::table('menus')
                ->where('url', $url)
                ->update(['icon' => $new, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        foreach ($this->icons as $url => [$new, $old]) {
            DB::table('menus')
                ->where('url', $url)
                ->update(['icon' => $old, 'updated_at' => now()]);
        }
    }
};
