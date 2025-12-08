<?php

use Illuminate\Database\Seeder;
use \App\Models\Setting;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting;
        $setting->meta = 'company_name';
        $setting->meta_value = 'Workbythai Internet And Marketing Co., Ltd.';
        $setting->remark = 'ชื่อบริษัท';
        $setting->save();

        $setting = new Setting;
        $setting->meta = 'logo';
        $setting->meta_value = '["logo.png"]';
        $setting->remark = 'โลโก้';
        $setting->save();

        $setting = new Setting;
        $setting->meta = 'program_name';
        $setting->meta_value = 'Package 2022';
        $setting->remark = 'ชื่อโปรแกรม';
        $setting->save();

        $setting = new Setting;
        $setting->meta = 'bg_login';
        $setting->meta_value = '["logo_with_text.png"]';
        $setting->remark = 'พื้นหลังหน้าล็อกอิน';
        $setting->save();

        $setting = new Setting;
        $setting->meta = 'dump_password';
        $setting->meta_value = bcrypt('123456789');
        $setting->remark = 'รหัสผ่าน Dump SQL';
        $setting->save();
    }
}
