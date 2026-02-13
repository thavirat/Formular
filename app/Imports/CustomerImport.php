<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class CustomerImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        // 1. จัดการ Code: explode ด้วย - แล้วเอาตัวแรกมาเช็คซ้ำ
        // ตัวอย่าง: "A020-01" -> "A020"
        $custCode = trim($data['custcode'] ?? '');
        if (empty($custCode)) return null;

        $codeArray = explode('-', $custCode);
        $cleanCode = trim($codeArray[0]);

        // 2. รวมที่อยู่ (Address) จากหลายคอลัมน์
        $addressParts = [
            $data['custaddr1'] ?? '',
            $data['district'] ?? '',
            $data['amphur'] ?? '',
            $data['province'] ?? ''
        ];
        // เชื่อมด้วยช่องว่าง และกรองค่าว่างออก
        $fullAddress = implode(' ', array_filter($addressParts));

        // 3. ใช้ updateOrCreate เพื่อเช็คซ้ำจากคอลัมน์ code
        return Customer::updateOrCreate(
            [
                'code' => $cleanCode // เงื่อนไขในการเช็คซ้ำ
            ],
            [
                'company_name' => $data['custnameeng'] ?? null,
                'address'      => $fullAddress,
                'tax_id'       => $data['taxid'] ?? null,
                'phone'        => $data['conttel'] ?? null,
                'fax'          => $data['contfax'] ?? null,
                'short_name'   => $data['shortname'] ?? null,
                'credit_day'   => $data['creditdays'] ?? 0,
                'bill_name'    => $data['custbillname'] ?? null,
                'contact_name' => $data['custname'] ?? null
            ]
        );
    }
}
