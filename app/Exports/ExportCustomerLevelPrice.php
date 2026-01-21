<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportCustomerLevelPrice implements FromCollection, WithHeadings, WithMapping
{
    protected $products;
    protected $currencies;
    protected $customer_level;

    public function __construct($products, $currencies, $customer_level)
    {
        $this->products = $products;
        $this->currencies = $currencies;
        $this->customer_level = $customer_level;
    }

    public function collection()
    {
        return $this->products;
    }

    // กำหนดหัวตาราง (มี 5 คอลัมน์หลักก่อนเริ่มราคา)
    public function headings(): array
    {
        $header = [
            'Product_ID',
            'Drawing_Code',
            'Level_ID',
            'Level_Name',
            'Product_Name_EN',
            'Product_Name_TH'
        ];

        foreach ($this->currencies as $currency) {
            $header[] = $currency->symbol;
        }
        return $header;
    }

    // จับคู่ข้อมูลลงคอลัมน์ (ต้องเรียงให้ตรงกับ headings)
    public function map($product): array
    {
        // ดึงค่าจาก $this->customer_level ที่ส่งเข้ามาจาก Controller
        $data = [
            $product->id,
            $product->code,
            $this->customer_level->id,      // แสดง ID ของ Level
            $this->customer_level->name,    // แสดง ชื่อของ Level
            $product->name_en,
            $product->name_th,
        ];

        foreach ($this->currencies as $currency) {
            // ค้นหาราคาที่ตรงกับสกุลเงินนั้นๆ จาก Relationship
            $priceData = $product->CustomerLevelDiscouts
                ->where('currency_id', $currency->id)
                ->first();

            $data[] = $priceData ? $priceData->price : 0;
        }

        return $data;
    }
}
