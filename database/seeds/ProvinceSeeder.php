<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinces')->insert([
            [
    "id"=>"1",
    "name"=>"กรุงเทพมหานคร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"2",
    "name"=>"สมุทรปราการ",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"3",
    "name"=>"นนทบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"4",
    "name"=>"ปทุมธานี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"5",
    "name"=>"พระนครศรีอยุธยา",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"6",
    "name"=>"อ่างทอง",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"7",
    "name"=>"ลพบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"8",
    "name"=>"สิงห์บุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"9",
    "name"=>"ชัยนาท",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"10",
    "name"=>"สระบุร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"11",
    "name"=>"ชลบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"12",
    "name"=>"ระยอง",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"13",
    "name"=>"จันทบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"14",
    "name"=>"ตราด",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"15",
    "name"=>"ฉะเชิงเทรา",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"16",
    "name"=>"ปราจีนบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"17",
    "name"=>"นครนายก",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"18",
    "name"=>"สระแก้ว",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"19",
    "name"=>"นครราชสีมา",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"20",
    "name"=>"บุรีรัมย์",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"21",
    "name"=>"สุรินทร์",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"22",
    "name"=>"ศรีสะเกษ",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"23",
    "name"=>"อุบลราชธานี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"24",
    "name"=>"ยโสธร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"25",
    "name"=>"ชัยภูมิ",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"26",
    "name"=>"อำนาจเจริญ",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"27",
    "name"=>"หนองบัวลำภู",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"28",
    "name"=>"ขอนแก่น",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"29",
    "name"=>"อุดรธานี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"30",
    "name"=>"เลย",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"31",
    "name"=>"หนองคาย",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"32",
    "name"=>"มหาสารคาม",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"33",
    "name"=>"ร้อยเอ็ด",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"34",
    "name"=>"กาฬสินธุ์",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"35",
    "name"=>"สกลนคร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"36",
    "name"=>"นครพนม",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"37",
    "name"=>"มุกดาหาร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"38",
    "name"=>"เชียงใหม่",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"39",
    "name"=>"ลำพูน",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"40",
    "name"=>"ลำปาง",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"41",
    "name"=>"อุตรดิตถ์",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"42",
    "name"=>"แพร่",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"43",
    "name"=>"น่าน",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"44",
    "name"=>"พะเยา",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"45",
    "name"=>"เชียงราย",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"46",
    "name"=>"แม่ฮ่องสอน",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"47",
    "name"=>"นครสวรรค์",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"48",
    "name"=>"อุทัยธานี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"49",
    "name"=>"กำแพงเพชร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"50",
    "name"=>"ตาก",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"51",
    "name"=>"สุโขทัย",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"52",
    "name"=>"พิษณุโลก",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"53",
    "name"=>"พิจิตร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"54",
    "name"=>"เพชรบูรณ์",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"55",
    "name"=>"ราชบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"56",
    "name"=>"กาญจนบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"57",
    "name"=>"สุพรรณบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"58",
    "name"=>"นครปฐม",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"59",
    "name"=>"สมุทรสาคร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"60",
    "name"=>"สมุทรสงคราม",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"61",
    "name"=>"เพชรบุรี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"62",
    "name"=>"ประจวบคีรีขันธ์",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"63",
    "name"=>"นครศรีธรรมราช",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"64",
    "name"=>"กระบี่",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"65",
    "name"=>"พังงา",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"66",
    "name"=>"ภูเก็ต",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"67",
    "name"=>"สุราษฎร์ธานี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"68",
    "name"=>"ระนอง",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"69",
    "name"=>"ชุมพร",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"70",
    "name"=>"สงขลา",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"71",
    "name"=>"สตูล",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"72",
    "name"=>"ตรัง",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"73",
    "name"=>"พัทลุง",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"74",
    "name"=>"ปัตตานี",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"75",
    "name"=>"ยะลา",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"76",
    "name"=>"นราธิวาส",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"77",
    "name"=>"บึงกาฬ",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
]


        ]);
    }
}
