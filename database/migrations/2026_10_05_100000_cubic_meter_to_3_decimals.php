<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * CBM (cubic_meter) แสดง/เก็บเป็นทศนิยม 3 ตำแหน่ง — ขยาย column decimal(6,2) -> decimal(9,3)
     */
    public function up(): void
    {
        foreach (['packing_forms', 'packing_form_details'] as $t) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, 'cubic_meter')) {
                DB::statement('ALTER TABLE '.DB::getTablePrefix().$t.' MODIFY cubic_meter DECIMAL(9,3) NULL');
            }
        }
    }

    public function down(): void
    {
        foreach (['packing_forms', 'packing_form_details'] as $t) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, 'cubic_meter')) {
                DB::statement('ALTER TABLE '.DB::getTablePrefix().$t.' MODIFY cubic_meter DECIMAL(6,2) NULL');
            }
        }
    }
};
