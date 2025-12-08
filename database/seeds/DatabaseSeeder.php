<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProvinceSeeder::class);
        $this->call(AmphurSeeder::class);
        $this->call(seed_menu::class);
        $this->call(seed_user_admin::class);
        $this->call(PrefixesTableSeeder::class);
        $this->call(CrudMenuSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AdminPermissionSeeder::class);
    }
}
