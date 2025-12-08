<?php

use Illuminate\Database\Seeder;

class PrefixesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prefixes')->insert([
            [
                "name"=>"ด.ช.",
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
            ],
            [
                "name"=>"ด.ญ.",
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
            ],
            [
                "name"=>"นาย",
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
            ],
            [
                "name"=>"น.ส.",
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
            ],
            [
                "name"=>"นาง",
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
            ],
            [
                "name"=>"ว่าที่ ร.ต.",
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
            ]
        ]);
    }
}
