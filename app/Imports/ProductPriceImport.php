<?php
namespace App\Imports;

use App\Models\CustomerLevelDiscout;
use App\Models\Currency;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductPriceImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // ดึงข้อมูล Currencies ทั้งหมดมาเก็บไว้ก่อนเพื่อเอา ID จาก Symbol
        $currencies = Currency::get();

        foreach ($rows as $row) {
            $productId = $row['product_id'];
            $levelId   = $row['level_id'];


            if (!$productId || !$levelId) continue;

            foreach ($currencies as $currency) {
                $headerKey = strtolower($currency->symbol);

                if (isset($row[$headerKey])) {
                    CustomerLevelDiscout::updateOrCreate(
                        [
                            'product_id'  => $productId,
                            'level_id'    => $levelId,
                            'currency_id' => $currency->id,
                        ],
                        [
                            'price' => (float) str_replace(',', '', $row[$headerKey])
                        ]
                    );
                }
            }
        }
    }
}
