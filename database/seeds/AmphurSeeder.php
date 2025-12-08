<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmphurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('amphurs')->insert([
            [
    "id"=>"1",
    "province_id"=>"1",
    "name"=>"เขตพระนคร",
    "zipcode"=>"10200",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"2",
    "province_id"=>"1",
    "name"=>"เขตดุสิต",
    "zipcode"=>"10300",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"3",
    "province_id"=>"1",
    "name"=>"เขตหนองจอก",
    "zipcode"=>"10530",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"4",
    "province_id"=>"1",
    "name"=>"เขตบางรัก",
    "zipcode"=>"10500",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"5",
    "province_id"=>"1",
    "name"=>"เขตบางเขน",
    "zipcode"=>"10220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"6",
    "province_id"=>"1",
    "name"=>"เขตบางกะปิ",
    "zipcode"=>"10240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"7",
    "province_id"=>"1",
    "name"=>"เขตปทุมวัน",
    "zipcode"=>"10330",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"8",
    "province_id"=>"1",
    "name"=>"เขตป้อมปราบศัตรูพ่าย",
    "zipcode"=>"10100",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"9",
    "province_id"=>"1",
    "name"=>"เขตพระโขนง",
    "zipcode"=>"10260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"10",
    "province_id"=>"1",
    "name"=>"เขตมีนบุรี",
    "zipcode"=>"10510",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"11",
    "province_id"=>"1",
    "name"=>"เขตลาดกระบัง",
    "zipcode"=>"10520",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"12",
    "province_id"=>"1",
    "name"=>"เขตยานนาวา",
    "zipcode"=>"10120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"13",
    "province_id"=>"1",
    "name"=>"เขตสัมพันธวงศ์",
    "zipcode"=>"10100",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"14",
    "province_id"=>"1",
    "name"=>"เขตพญาไท",
    "zipcode"=>"10400",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"15",
    "province_id"=>"1",
    "name"=>"เขตธนบุรี",
    "zipcode"=>"10600",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"16",
    "province_id"=>"1",
    "name"=>"เขตบางกอกใหญ่",
    "zipcode"=>"10600",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"17",
    "province_id"=>"1",
    "name"=>"เขตห้วยขวาง",
    "zipcode"=>"10310",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"18",
    "province_id"=>"1",
    "name"=>"เขตคลองสาน",
    "zipcode"=>"10600",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"19",
    "province_id"=>"1",
    "name"=>"เขตตลิ่งชัน",
    "zipcode"=>"10170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"20",
    "province_id"=>"1",
    "name"=>"เขตบางกอกน้อย",
    "zipcode"=>"10700",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"21",
    "province_id"=>"1",
    "name"=>"เขตบางขุนเทียน",
    "zipcode"=>"10150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"22",
    "province_id"=>"1",
    "name"=>"เขตภาษีเจริญ",
    "zipcode"=>"10160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"23",
    "province_id"=>"1",
    "name"=>"เขตหนองแขม",
    "zipcode"=>"10160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"24",
    "province_id"=>"1",
    "name"=>"เขตราษฎร์บูรณะ",
    "zipcode"=>"10140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"25",
    "province_id"=>"1",
    "name"=>"เขตบางพลัด",
    "zipcode"=>"10700",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"26",
    "province_id"=>"1",
    "name"=>"เขตดินแดง",
    "zipcode"=>"10400",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"27",
    "province_id"=>"1",
    "name"=>"เขตบึงกุ่ม",
    "zipcode"=>"10240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"28",
    "province_id"=>"1",
    "name"=>"เขตสาทร",
    "zipcode"=>"10120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"29",
    "province_id"=>"1",
    "name"=>"เขตบางซื่อ",
    "zipcode"=>"10800",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"30",
    "province_id"=>"1",
    "name"=>"เขตจตุจักร",
    "zipcode"=>"10900",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"31",
    "province_id"=>"1",
    "name"=>"เขตบางคอแหลม",
    "zipcode"=>"10120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"32",
    "province_id"=>"1",
    "name"=>"เขตประเวศ",
    "zipcode"=>"10250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"33",
    "province_id"=>"1",
    "name"=>"เขตคลองเตย",
    "zipcode"=>"10110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"34",
    "province_id"=>"1",
    "name"=>"เขตสวนหลวง",
    "zipcode"=>"10250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"35",
    "province_id"=>"1",
    "name"=>"เขตจอมทอง",
    "zipcode"=>"10150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"36",
    "province_id"=>"1",
    "name"=>"เขตดอนเมือง",
    "zipcode"=>"10210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"37",
    "province_id"=>"1",
    "name"=>"เขตราชเทวี",
    "zipcode"=>"10400",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"38",
    "province_id"=>"1",
    "name"=>"เขตลาดพร้าว",
    "zipcode"=>"10230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"39",
    "province_id"=>"1",
    "name"=>"เขตวัฒนา",
    "zipcode"=>"10110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"40",
    "province_id"=>"1",
    "name"=>"เขตบางแค",
    "zipcode"=>"10160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"41",
    "province_id"=>"1",
    "name"=>"เขตหลักสี่",
    "zipcode"=>"10210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"42",
    "province_id"=>"1",
    "name"=>"เขตสายไหม",
    "zipcode"=>"10220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"43",
    "province_id"=>"1",
    "name"=>"เขตคันนายาว",
    "zipcode"=>"10230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"44",
    "province_id"=>"1",
    "name"=>"เขตสะพานสูง",
    "zipcode"=>"10240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"45",
    "province_id"=>"1",
    "name"=>"เขตวังทองหลาง",
    "zipcode"=>"10310",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"46",
    "province_id"=>"1",
    "name"=>"เขตคลองสามวา",
    "zipcode"=>"10510",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"47",
    "province_id"=>"1",
    "name"=>"เขตบางนา",
    "zipcode"=>"10260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"48",
    "province_id"=>"1",
    "name"=>"เขตทวีวัฒนา",
    "zipcode"=>"10170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"49",
    "province_id"=>"1",
    "name"=>"เขตทุ่งครุ",
    "zipcode"=>"10140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"50",
    "province_id"=>"1",
    "name"=>"เขตบางบอน",
    "zipcode"=>"10150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"52",
    "province_id"=>"2",
    "name"=>"เมืองสมุทรปราการ",
    "zipcode"=>"10270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"53",
    "province_id"=>"2",
    "name"=>"บางบ่อ",
    "zipcode"=>"10560",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"54",
    "province_id"=>"2",
    "name"=>"บางพลี",
    "zipcode"=>"10540",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"55",
    "province_id"=>"2",
    "name"=>"พระประแดง",
    "zipcode"=>"10130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"56",
    "province_id"=>"2",
    "name"=>"พระสมุทรเจดีย์",
    "zipcode"=>"10290",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"57",
    "province_id"=>"2",
    "name"=>"บางเสาธง",
    "zipcode"=>"10540",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"58",
    "province_id"=>"3",
    "name"=>"เมืองนนทบุรี",
    "zipcode"=>"11000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"59",
    "province_id"=>"3",
    "name"=>"บางกรวย",
    "zipcode"=>"11130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"60",
    "province_id"=>"3",
    "name"=>"บางใหญ่",
    "zipcode"=>"11140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"61",
    "province_id"=>"3",
    "name"=>"บางบัวทอง",
    "zipcode"=>"11110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"62",
    "province_id"=>"3",
    "name"=>"ไทรน้อย",
    "zipcode"=>"11150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"63",
    "province_id"=>"3",
    "name"=>"ปากเกร็ด",
    "zipcode"=>"11120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"66",
    "province_id"=>"4",
    "name"=>"เมืองปทุมธานี",
    "zipcode"=>"12000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"67",
    "province_id"=>"4",
    "name"=>"คลองหลวง",
    "zipcode"=>"12120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"68",
    "province_id"=>"4",
    "name"=>"ธัญบุรี",
    "zipcode"=>"12130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"69",
    "province_id"=>"4",
    "name"=>"หนองเสือ",
    "zipcode"=>"12170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"70",
    "province_id"=>"4",
    "name"=>"ลาดหลุมแก้ว",
    "zipcode"=>"12140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"71",
    "province_id"=>"4",
    "name"=>"ลำลูกกา",
    "zipcode"=>"12130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"72",
    "province_id"=>"4",
    "name"=>"สามโคก",
    "zipcode"=>"12160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"74",
    "province_id"=>"5",
    "name"=>"พระนครศรีอยุธยา",
    "zipcode"=>"13000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"75",
    "province_id"=>"5",
    "name"=>"ท่าเรือ",
    "zipcode"=>"13130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"76",
    "province_id"=>"5",
    "name"=>"นครหลวง",
    "zipcode"=>"13260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"77",
    "province_id"=>"5",
    "name"=>"บางไทร",
    "zipcode"=>"13190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"78",
    "province_id"=>"5",
    "name"=>"บางบาล",
    "zipcode"=>"13250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"79",
    "province_id"=>"5",
    "name"=>"บางปะอิน",
    "zipcode"=>"13160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"80",
    "province_id"=>"5",
    "name"=>"บางปะหัน",
    "zipcode"=>"13220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"81",
    "province_id"=>"5",
    "name"=>"ผักไห่",
    "zipcode"=>"13120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"82",
    "province_id"=>"5",
    "name"=>"ภาชี",
    "zipcode"=>"13140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"83",
    "province_id"=>"5",
    "name"=>"ลาดบัวหลวง",
    "zipcode"=>"13230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"84",
    "province_id"=>"5",
    "name"=>"วังน้อย",
    "zipcode"=>"13170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"85",
    "province_id"=>"5",
    "name"=>"เสนา",
    "zipcode"=>"13110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"86",
    "province_id"=>"5",
    "name"=>"บางซ้าย",
    "zipcode"=>"13270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"87",
    "province_id"=>"5",
    "name"=>"อุทัย",
    "zipcode"=>"13210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"88",
    "province_id"=>"5",
    "name"=>"มหาราช",
    "zipcode"=>"13150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"89",
    "province_id"=>"5",
    "name"=>"บ้านแพรก",
    "zipcode"=>"13240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"90",
    "province_id"=>"6",
    "name"=>"เมืองอ่างทอง",
    "zipcode"=>"14000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"91",
    "province_id"=>"6",
    "name"=>"ไชโย",
    "zipcode"=>"14140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"92",
    "province_id"=>"6",
    "name"=>"ป่าโมก",
    "zipcode"=>"14130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"93",
    "province_id"=>"6",
    "name"=>"โพธิ์ทอง",
    "zipcode"=>"14120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"94",
    "province_id"=>"6",
    "name"=>"แสวงหา",
    "zipcode"=>"14150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"95",
    "province_id"=>"6",
    "name"=>"วิเศษชัยชาญ",
    "zipcode"=>"14110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"96",
    "province_id"=>"6",
    "name"=>"สามโก้",
    "zipcode"=>"14160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"97",
    "province_id"=>"7",
    "name"=>"เมืองลพบุรี",
    "zipcode"=>"15000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"98",
    "province_id"=>"7",
    "name"=>"พัฒนานิคม",
    "zipcode"=>"15140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"99",
    "province_id"=>"7",
    "name"=>"โคกสำโรง",
    "zipcode"=>"15120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"100",
    "province_id"=>"7",
    "name"=>"ชัยบาดาล",
    "zipcode"=>"15130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"101",
    "province_id"=>"7",
    "name"=>"ท่าวุ้ง",
    "zipcode"=>"15150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"102",
    "province_id"=>"7",
    "name"=>"บ้านหมี่",
    "zipcode"=>"15110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"103",
    "province_id"=>"7",
    "name"=>"ท่าหลวง",
    "zipcode"=>"15230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"104",
    "province_id"=>"7",
    "name"=>"สระโบสถ์",
    "zipcode"=>"15240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"105",
    "province_id"=>"7",
    "name"=>"โคกเจริญ",
    "zipcode"=>"15250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"106",
    "province_id"=>"7",
    "name"=>"ลำสนธิ",
    "zipcode"=>"15190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"107",
    "province_id"=>"7",
    "name"=>"หนองม่วง",
    "zipcode"=>"15170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"109",
    "province_id"=>"8",
    "name"=>"เมืองสิงห์บุรี",
    "zipcode"=>"16000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"110",
    "province_id"=>"8",
    "name"=>"บางระจัน",
    "zipcode"=>"16130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"111",
    "province_id"=>"8",
    "name"=>"ค่ายบางระจัน",
    "zipcode"=>"16150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"112",
    "province_id"=>"8",
    "name"=>"พรหมบุรี",
    "zipcode"=>"16120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"113",
    "province_id"=>"8",
    "name"=>"ท่าช้าง",
    "zipcode"=>"16140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"114",
    "province_id"=>"8",
    "name"=>"อินทร์บุรี",
    "zipcode"=>"16110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"115",
    "province_id"=>"9",
    "name"=>"เมืองชัยนาท",
    "zipcode"=>"17000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"116",
    "province_id"=>"9",
    "name"=>"มโนรมย์",
    "zipcode"=>"17110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"117",
    "province_id"=>"9",
    "name"=>"วัดสิงห์",
    "zipcode"=>"17120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"118",
    "province_id"=>"9",
    "name"=>"สรรพยา",
    "zipcode"=>"17150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"119",
    "province_id"=>"9",
    "name"=>"สรรคบุรี",
    "zipcode"=>"17140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"120",
    "province_id"=>"9",
    "name"=>"หันคา",
    "zipcode"=>"17130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"121",
    "province_id"=>"9",
    "name"=>"หนองมะโมง",
    "zipcode"=>"17120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"122",
    "province_id"=>"9",
    "name"=>"เนินขาม",
    "zipcode"=>"17130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"123",
    "province_id"=>"10",
    "name"=>"เมืองสระบุรี",
    "zipcode"=>"18000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"124",
    "province_id"=>"10",
    "name"=>"แก่งคอย",
    "zipcode"=>"18110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"125",
    "province_id"=>"10",
    "name"=>"หนองแค",
    "zipcode"=>"18140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"126",
    "province_id"=>"10",
    "name"=>"วิหารแดง",
    "zipcode"=>"18150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"127",
    "province_id"=>"10",
    "name"=>"หนองแซง",
    "zipcode"=>"18170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"128",
    "province_id"=>"10",
    "name"=>"บ้านหมอ",
    "zipcode"=>"18130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"129",
    "province_id"=>"10",
    "name"=>"ดอนพุด",
    "zipcode"=>"18210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"130",
    "province_id"=>"10",
    "name"=>"หนองโดน",
    "zipcode"=>"18190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"131",
    "province_id"=>"10",
    "name"=>"พระพุทธบาท",
    "zipcode"=>"18120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"132",
    "province_id"=>"10",
    "name"=>"เสาไห้",
    "zipcode"=>"18160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"133",
    "province_id"=>"10",
    "name"=>"มวกเหล็ก",
    "zipcode"=>"18180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"134",
    "province_id"=>"10",
    "name"=>"วังม่วง",
    "zipcode"=>"18220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"135",
    "province_id"=>"10",
    "name"=>"เฉลิมพระเกียรติ",
    "zipcode"=>"18000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"136",
    "province_id"=>"11",
    "name"=>"เมืองชลบุรี",
    "zipcode"=>"20000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"137",
    "province_id"=>"11",
    "name"=>"บ้านบึง",
    "zipcode"=>"20170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"138",
    "province_id"=>"11",
    "name"=>"หนองใหญ่",
    "zipcode"=>"20190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"139",
    "province_id"=>"11",
    "name"=>"บางละมุง",
    "zipcode"=>"20150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"140",
    "province_id"=>"11",
    "name"=>"พานทอง",
    "zipcode"=>"20160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"141",
    "province_id"=>"11",
    "name"=>"พนัสนิคม",
    "zipcode"=>"20140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"142",
    "province_id"=>"11",
    "name"=>"ศรีราชา",
    "zipcode"=>"20110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"143",
    "province_id"=>"11",
    "name"=>"เกาะสีชัง",
    "zipcode"=>"20120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"144",
    "province_id"=>"11",
    "name"=>"สัตหีบ",
    "zipcode"=>"20180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"145",
    "province_id"=>"11",
    "name"=>"บ่อทอง",
    "zipcode"=>"20270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"146",
    "province_id"=>"11",
    "name"=>"เกาะจันทร์",
    "zipcode"=>"20240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"151",
    "province_id"=>"12",
    "name"=>"เมืองระยอง",
    "zipcode"=>"21000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"152",
    "province_id"=>"12",
    "name"=>"บ้านฉาง",
    "zipcode"=>"21130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"153",
    "province_id"=>"12",
    "name"=>"แกลง",
    "zipcode"=>"21110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"154",
    "province_id"=>"12",
    "name"=>"วังจันทร์",
    "zipcode"=>"21210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"155",
    "province_id"=>"12",
    "name"=>"บ้านค่าย",
    "zipcode"=>"21120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"156",
    "province_id"=>"12",
    "name"=>"ปลวกแดง",
    "zipcode"=>"21140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"157",
    "province_id"=>"12",
    "name"=>"เขาชะเมา",
    "zipcode"=>"21110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"158",
    "province_id"=>"12",
    "name"=>"นิคมพัฒนา",
    "zipcode"=>"21180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"160",
    "province_id"=>"13",
    "name"=>"เมืองจันทบุรี",
    "zipcode"=>"22000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"161",
    "province_id"=>"13",
    "name"=>"ขลุง",
    "zipcode"=>"22110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"162",
    "province_id"=>"13",
    "name"=>"ท่าใหม่",
    "zipcode"=>"22120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"163",
    "province_id"=>"13",
    "name"=>"โป่งน้ำร้อน",
    "zipcode"=>"22140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"164",
    "province_id"=>"13",
    "name"=>"มะขาม",
    "zipcode"=>"22150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"165",
    "province_id"=>"13",
    "name"=>"แหลมสิงห์",
    "zipcode"=>"22130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"166",
    "province_id"=>"13",
    "name"=>"สอยดาว",
    "zipcode"=>"22180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"167",
    "province_id"=>"13",
    "name"=>"แก่งหางแมว",
    "zipcode"=>"22160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"168",
    "province_id"=>"13",
    "name"=>"นายายอาม",
    "zipcode"=>"22160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"169",
    "province_id"=>"13",
    "name"=>"เขาคิชฌกูฏ",
    "zipcode"=>"22210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"171",
    "province_id"=>"14",
    "name"=>"เมืองตราด",
    "zipcode"=>"23000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"172",
    "province_id"=>"14",
    "name"=>"คลองใหญ่",
    "zipcode"=>"23110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"173",
    "province_id"=>"14",
    "name"=>"เขาสมิง",
    "zipcode"=>"23130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"174",
    "province_id"=>"14",
    "name"=>"บ่อไร่",
    "zipcode"=>"23140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"175",
    "province_id"=>"14",
    "name"=>"แหลมงอบ",
    "zipcode"=>"23120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"176",
    "province_id"=>"14",
    "name"=>"เกาะกูด",
    "zipcode"=>"23000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"177",
    "province_id"=>"14",
    "name"=>"เกาะช้าง",
    "zipcode"=>"23170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"178",
    "province_id"=>"15",
    "name"=>"เมืองฉะเชิงเทรา",
    "zipcode"=>"24000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"179",
    "province_id"=>"15",
    "name"=>"บางคล้า",
    "zipcode"=>"24110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"180",
    "province_id"=>"15",
    "name"=>"บางน้ำเปรี้ยว",
    "zipcode"=>"24150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"181",
    "province_id"=>"15",
    "name"=>"บางปะกง",
    "zipcode"=>"24130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"182",
    "province_id"=>"15",
    "name"=>"บ้านโพธิ์",
    "zipcode"=>"24140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"183",
    "province_id"=>"15",
    "name"=>"พนมสารคาม",
    "zipcode"=>"24120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"184",
    "province_id"=>"15",
    "name"=>"ราชสาส์น",
    "zipcode"=>"24120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"185",
    "province_id"=>"15",
    "name"=>"สนามชัยเขต",
    "zipcode"=>"24160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"186",
    "province_id"=>"15",
    "name"=>"แปลงยาว",
    "zipcode"=>"24190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"187",
    "province_id"=>"15",
    "name"=>"ท่าตะเกียบ",
    "zipcode"=>"24160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"188",
    "province_id"=>"15",
    "name"=>"คลองเขื่อน",
    "zipcode"=>"24000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"189",
    "province_id"=>"16",
    "name"=>"เมืองปราจีนบุรี",
    "zipcode"=>"25000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"190",
    "province_id"=>"16",
    "name"=>"กบินทร์บุรี",
    "zipcode"=>"25110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"191",
    "province_id"=>"16",
    "name"=>"นาดี",
    "zipcode"=>"25220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"194",
    "province_id"=>"16",
    "name"=>"บ้านสร้าง",
    "zipcode"=>"25150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"195",
    "province_id"=>"16",
    "name"=>"ประจันตคาม",
    "zipcode"=>"25130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"196",
    "province_id"=>"16",
    "name"=>"ศรีมหาโพธิ",
    "zipcode"=>"25140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"197",
    "province_id"=>"16",
    "name"=>"ศรีมโหสถ",
    "zipcode"=>"25190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"202",
    "province_id"=>"17",
    "name"=>"เมืองนครนายก",
    "zipcode"=>"26000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"203",
    "province_id"=>"17",
    "name"=>"ปากพลี",
    "zipcode"=>"26130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"204",
    "province_id"=>"17",
    "name"=>"บ้านนา",
    "zipcode"=>"26110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"205",
    "province_id"=>"17",
    "name"=>"องครักษ์",
    "zipcode"=>"26120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"206",
    "province_id"=>"18",
    "name"=>"เมืองสระแก้ว",
    "zipcode"=>"27000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"207",
    "province_id"=>"18",
    "name"=>"คลองหาด",
    "zipcode"=>"27260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"208",
    "province_id"=>"18",
    "name"=>"ตาพระยา",
    "zipcode"=>"27180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"209",
    "province_id"=>"18",
    "name"=>"วังน้ำเย็น",
    "zipcode"=>"27210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"210",
    "province_id"=>"18",
    "name"=>"วัฒนานคร",
    "zipcode"=>"27160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"211",
    "province_id"=>"18",
    "name"=>"อรัญประเทศ",
    "zipcode"=>"27120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"212",
    "province_id"=>"18",
    "name"=>"เขาฉกรรจ์",
    "zipcode"=>"27000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"213",
    "province_id"=>"18",
    "name"=>"โคกสูง",
    "zipcode"=>"27120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"214",
    "province_id"=>"18",
    "name"=>"วังสมบูรณ์",
    "zipcode"=>"27250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"215",
    "province_id"=>"19",
    "name"=>"เมืองนครราชสีมา",
    "zipcode"=>"30000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"216",
    "province_id"=>"19",
    "name"=>"ครบุรี",
    "zipcode"=>"30250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"217",
    "province_id"=>"19",
    "name"=>"เสิงสาง",
    "zipcode"=>"30330",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"218",
    "province_id"=>"19",
    "name"=>"คง",
    "zipcode"=>"30260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"219",
    "province_id"=>"19",
    "name"=>"บ้านเหลื่อม",
    "zipcode"=>"30350",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"220",
    "province_id"=>"19",
    "name"=>"จักราช",
    "zipcode"=>"30230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"221",
    "province_id"=>"19",
    "name"=>"โชคชัย",
    "zipcode"=>"30190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"222",
    "province_id"=>"19",
    "name"=>"ด่านขุนทด",
    "zipcode"=>"30210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"223",
    "province_id"=>"19",
    "name"=>"โนนไทย",
    "zipcode"=>"30220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"224",
    "province_id"=>"19",
    "name"=>"โนนสูง",
    "zipcode"=>"30160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"225",
    "province_id"=>"19",
    "name"=>"ขามสะแกแสง",
    "zipcode"=>"30290",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"226",
    "province_id"=>"19",
    "name"=>"บัวใหญ่",
    "zipcode"=>"30120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"227",
    "province_id"=>"19",
    "name"=>"ประทาย",
    "zipcode"=>"30180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"228",
    "province_id"=>"19",
    "name"=>"ปักธงชัย",
    "zipcode"=>"30150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"229",
    "province_id"=>"19",
    "name"=>"พิมาย",
    "zipcode"=>"30110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"230",
    "province_id"=>"19",
    "name"=>"ห้วยแถลง",
    "zipcode"=>"30240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"231",
    "province_id"=>"19",
    "name"=>"ชุมพวง",
    "zipcode"=>"30270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"232",
    "province_id"=>"19",
    "name"=>"สูงเนิน",
    "zipcode"=>"30170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"233",
    "province_id"=>"19",
    "name"=>"ขามทะเลสอ",
    "zipcode"=>"30280",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"234",
    "province_id"=>"19",
    "name"=>"สีคิ้ว",
    "zipcode"=>"30140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"235",
    "province_id"=>"19",
    "name"=>"ปากช่อง",
    "zipcode"=>"30130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"236",
    "province_id"=>"19",
    "name"=>"หนองบุญมาก",
    "zipcode"=>"30410",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"237",
    "province_id"=>"19",
    "name"=>"แก้งสนามนาง",
    "zipcode"=>"30440",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"238",
    "province_id"=>"19",
    "name"=>"โนนแดง",
    "zipcode"=>"30360",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"239",
    "province_id"=>"19",
    "name"=>"วังน้ำเขียว",
    "zipcode"=>"30370",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"240",
    "province_id"=>"19",
    "name"=>"เทพารักษ์",
    "zipcode"=>"30210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"241",
    "province_id"=>"19",
    "name"=>"เมืองยาง",
    "zipcode"=>"30270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"242",
    "province_id"=>"19",
    "name"=>"พระทองคำ",
    "zipcode"=>"30220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"243",
    "province_id"=>"19",
    "name"=>"ลำทะเมนชัย",
    "zipcode"=>"30270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"244",
    "province_id"=>"19",
    "name"=>"บัวลาย",
    "zipcode"=>"30120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"245",
    "province_id"=>"19",
    "name"=>"สีดา",
    "zipcode"=>"30430",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"246",
    "province_id"=>"19",
    "name"=>"เฉลิมพระเกียรติ",
    "zipcode"=>"30230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"250",
    "province_id"=>"20",
    "name"=>"เมืองบุรีรัมย์",
    "zipcode"=>"31000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"251",
    "province_id"=>"20",
    "name"=>"คูเมือง",
    "zipcode"=>"31190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"252",
    "province_id"=>"20",
    "name"=>"กระสัง",
    "zipcode"=>"31160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"253",
    "province_id"=>"20",
    "name"=>"นางรอง",
    "zipcode"=>"31110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"254",
    "province_id"=>"20",
    "name"=>"หนองกี่",
    "zipcode"=>"31210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"255",
    "province_id"=>"20",
    "name"=>"ละหานทราย",
    "zipcode"=>"31170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"256",
    "province_id"=>"20",
    "name"=>"ประโคนชัย",
    "zipcode"=>"31140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"257",
    "province_id"=>"20",
    "name"=>"บ้านกรวด",
    "zipcode"=>"31180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"258",
    "province_id"=>"20",
    "name"=>"พุทไธสง",
    "zipcode"=>"31120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"259",
    "province_id"=>"20",
    "name"=>"ลำปลายมาศ",
    "zipcode"=>"31130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"260",
    "province_id"=>"20",
    "name"=>"สตึก",
    "zipcode"=>"31150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"261",
    "province_id"=>"20",
    "name"=>"ปะคำ",
    "zipcode"=>"31220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"262",
    "province_id"=>"20",
    "name"=>"นาโพธิ์",
    "zipcode"=>"31230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"263",
    "province_id"=>"20",
    "name"=>"หนองหงส์",
    "zipcode"=>"31240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"264",
    "province_id"=>"20",
    "name"=>"พลับพลาชัย",
    "zipcode"=>"31250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"265",
    "province_id"=>"20",
    "name"=>"ห้วยราช",
    "zipcode"=>"31000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"266",
    "province_id"=>"20",
    "name"=>"โนนสุวรรณ",
    "zipcode"=>"31110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"267",
    "province_id"=>"20",
    "name"=>"ชำนิ",
    "zipcode"=>"31110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"268",
    "province_id"=>"20",
    "name"=>"บ้านใหม่ไชยพจน์",
    "zipcode"=>"31120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"269",
    "province_id"=>"20",
    "name"=>"โนนดินแดง",
    "zipcode"=>"31260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"270",
    "province_id"=>"20",
    "name"=>"บ้านด่าน",
    "zipcode"=>"31000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"271",
    "province_id"=>"20",
    "name"=>"แคนดง",
    "zipcode"=>"31150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"272",
    "province_id"=>"20",
    "name"=>"เฉลิมพระเกียรติ",
    "zipcode"=>"31110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"273",
    "province_id"=>"21",
    "name"=>"เมืองสุรินทร์",
    "zipcode"=>"32000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"274",
    "province_id"=>"21",
    "name"=>"ชุมพลบุรี",
    "zipcode"=>"32190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"275",
    "province_id"=>"21",
    "name"=>"ท่าตูม",
    "zipcode"=>"32120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"276",
    "province_id"=>"21",
    "name"=>"จอมพระ",
    "zipcode"=>"32180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"277",
    "province_id"=>"21",
    "name"=>"ปราสาท",
    "zipcode"=>"32140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"278",
    "province_id"=>"21",
    "name"=>"กาบเชิง",
    "zipcode"=>"32210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"279",
    "province_id"=>"21",
    "name"=>"รัตนบุรี",
    "zipcode"=>"32130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"280",
    "province_id"=>"21",
    "name"=>"สนม",
    "zipcode"=>"32160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"281",
    "province_id"=>"21",
    "name"=>"ศีขรภูมิ",
    "zipcode"=>"32110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"282",
    "province_id"=>"21",
    "name"=>"สังขะ",
    "zipcode"=>"32150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"283",
    "province_id"=>"21",
    "name"=>"ลำดวน",
    "zipcode"=>"32220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"284",
    "province_id"=>"21",
    "name"=>"สำโรงทาบ",
    "zipcode"=>"32170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"285",
    "province_id"=>"21",
    "name"=>"บัวเชด",
    "zipcode"=>"32230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"286",
    "province_id"=>"21",
    "name"=>"พนมดงรัก",
    "zipcode"=>"32140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"287",
    "province_id"=>"21",
    "name"=>"ศรีณรงค์",
    "zipcode"=>"32150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"288",
    "province_id"=>"21",
    "name"=>"เขวาสินรินทร์",
    "zipcode"=>"32000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"289",
    "province_id"=>"21",
    "name"=>"โนนนารายณ์",
    "zipcode"=>"32130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"290",
    "province_id"=>"22",
    "name"=>"เมืองศรีสะเกษ",
    "zipcode"=>"33000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"291",
    "province_id"=>"22",
    "name"=>"ยางชุมน้อย",
    "zipcode"=>"33190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"292",
    "province_id"=>"22",
    "name"=>"กันทรารมย์",
    "zipcode"=>"33130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"293",
    "province_id"=>"22",
    "name"=>"กันทรลักษ์",
    "zipcode"=>"33110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"294",
    "province_id"=>"22",
    "name"=>"ขุขันธ์",
    "zipcode"=>"33140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"295",
    "province_id"=>"22",
    "name"=>"ไพรบึง",
    "zipcode"=>"33180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"296",
    "province_id"=>"22",
    "name"=>"ปรางค์กู่",
    "zipcode"=>"33170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"297",
    "province_id"=>"22",
    "name"=>"ขุนหาญ",
    "zipcode"=>"33150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"298",
    "province_id"=>"22",
    "name"=>"ราษีไศล",
    "zipcode"=>"33160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"299",
    "province_id"=>"22",
    "name"=>"อุทุมพรพิสัย",
    "zipcode"=>"33120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"300",
    "province_id"=>"22",
    "name"=>"บึงบูรพ์",
    "zipcode"=>"33220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"301",
    "province_id"=>"22",
    "name"=>"ห้วยทับทัน",
    "zipcode"=>"33210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"302",
    "province_id"=>"22",
    "name"=>"โนนคูณ",
    "zipcode"=>"33250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"303",
    "province_id"=>"22",
    "name"=>"ศรีรัตนะ",
    "zipcode"=>"33240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"304",
    "province_id"=>"22",
    "name"=>"น้ำเกลี้ยง",
    "zipcode"=>"33130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"305",
    "province_id"=>"22",
    "name"=>"วังหิน",
    "zipcode"=>"33270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"306",
    "province_id"=>"22",
    "name"=>"ภูสิงห์",
    "zipcode"=>"33140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"307",
    "province_id"=>"22",
    "name"=>"เมืองจันทร์",
    "zipcode"=>"33120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"308",
    "province_id"=>"22",
    "name"=>"เบญจลักษ์",
    "zipcode"=>"33110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"309",
    "province_id"=>"22",
    "name"=>"พยุห์",
    "zipcode"=>"33230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"310",
    "province_id"=>"22",
    "name"=>"โพธิ์ศรีสุวรรณ",
    "zipcode"=>"33120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"311",
    "province_id"=>"22",
    "name"=>"ศิลาลาด",
    "zipcode"=>"33160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"312",
    "province_id"=>"23",
    "name"=>"เมืองอุบลราชธานี",
    "zipcode"=>"34000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"313",
    "province_id"=>"23",
    "name"=>"ศรีเมืองใหม่",
    "zipcode"=>"34250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"314",
    "province_id"=>"23",
    "name"=>"โขงเจียม",
    "zipcode"=>"34220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"315",
    "province_id"=>"23",
    "name"=>"เขื่องใน",
    "zipcode"=>"34150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"316",
    "province_id"=>"23",
    "name"=>"เขมราฐ",
    "zipcode"=>"34170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"318",
    "province_id"=>"23",
    "name"=>"เดชอุดม",
    "zipcode"=>"34160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"319",
    "province_id"=>"23",
    "name"=>"นาจะหลวย",
    "zipcode"=>"34280",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"320",
    "province_id"=>"23",
    "name"=>"น้ำยืน",
    "zipcode"=>"34260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"321",
    "province_id"=>"23",
    "name"=>"บุณฑริก",
    "zipcode"=>"34230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"322",
    "province_id"=>"23",
    "name"=>"ตระการพืชผล",
    "zipcode"=>"34130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"323",
    "province_id"=>"23",
    "name"=>"กุดข้าวปุ้น",
    "zipcode"=>"34270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"325",
    "province_id"=>"23",
    "name"=>"ม่วงสามสิบ",
    "zipcode"=>"34140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"326",
    "province_id"=>"23",
    "name"=>"วารินชำราบ",
    "zipcode"=>"34190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"330",
    "province_id"=>"23",
    "name"=>"พิบูลมังสาหาร",
    "zipcode"=>"34110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"331",
    "province_id"=>"23",
    "name"=>"ตาลสุม",
    "zipcode"=>"34330",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"332",
    "province_id"=>"23",
    "name"=>"โพธิ์ไทร",
    "zipcode"=>"34340",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"333",
    "province_id"=>"23",
    "name"=>"สำโรง",
    "zipcode"=>"34360",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"335",
    "province_id"=>"23",
    "name"=>"ดอนมดแดง",
    "zipcode"=>"34000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"336",
    "province_id"=>"23",
    "name"=>"สิรินธร",
    "zipcode"=>"34350",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"337",
    "province_id"=>"23",
    "name"=>"ทุ่งศรีอุดม",
    "zipcode"=>"34160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"340",
    "province_id"=>"23",
    "name"=>"นาเยีย",
    "zipcode"=>"34160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"341",
    "province_id"=>"23",
    "name"=>"นาตาล",
    "zipcode"=>"34170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"342",
    "province_id"=>"23",
    "name"=>"เหล่าเสือโก้ก",
    "zipcode"=>"34000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"343",
    "province_id"=>"23",
    "name"=>"สว่างวีระวงศ์",
    "zipcode"=>"34190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"344",
    "province_id"=>"23",
    "name"=>"น้ำขุ่น",
    "zipcode"=>"34260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"346",
    "province_id"=>"24",
    "name"=>"เมืองยโสธร",
    "zipcode"=>"35000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"347",
    "province_id"=>"24",
    "name"=>"ทรายมูล",
    "zipcode"=>"35170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"348",
    "province_id"=>"24",
    "name"=>"กุดชุม",
    "zipcode"=>"35140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"349",
    "province_id"=>"24",
    "name"=>"คำเขื่อนแก้ว",
    "zipcode"=>"35110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"350",
    "province_id"=>"24",
    "name"=>"ป่าติ้ว",
    "zipcode"=>"35150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"351",
    "province_id"=>"24",
    "name"=>"มหาชนะชัย",
    "zipcode"=>"35130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"352",
    "province_id"=>"24",
    "name"=>"ค้อวัง",
    "zipcode"=>"35160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"353",
    "province_id"=>"24",
    "name"=>"เลิงนกทา",
    "zipcode"=>"35120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"354",
    "province_id"=>"24",
    "name"=>"ไทยเจริญ",
    "zipcode"=>"35120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"355",
    "province_id"=>"25",
    "name"=>"เมืองชัยภูมิ",
    "zipcode"=>"36000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"356",
    "province_id"=>"25",
    "name"=>"บ้านเขว้า",
    "zipcode"=>"36170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"357",
    "province_id"=>"25",
    "name"=>"คอนสวรรค์",
    "zipcode"=>"36140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"358",
    "province_id"=>"25",
    "name"=>"เกษตรสมบูรณ์",
    "zipcode"=>"36120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"359",
    "province_id"=>"25",
    "name"=>"หนองบัวแดง",
    "zipcode"=>"36210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"360",
    "province_id"=>"25",
    "name"=>"จัตุรัส",
    "zipcode"=>"36130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"361",
    "province_id"=>"25",
    "name"=>"บำเหน็จณรงค์",
    "zipcode"=>"36160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"362",
    "province_id"=>"25",
    "name"=>"หนองบัวระเหว",
    "zipcode"=>"36250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"363",
    "province_id"=>"25",
    "name"=>"เทพสถิต",
    "zipcode"=>"36230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"364",
    "province_id"=>"25",
    "name"=>"ภูเขียว",
    "zipcode"=>"36110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"365",
    "province_id"=>"25",
    "name"=>"บ้านแท่น",
    "zipcode"=>"36190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"366",
    "province_id"=>"25",
    "name"=>"แก้งคร้อ",
    "zipcode"=>"36150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"367",
    "province_id"=>"25",
    "name"=>"คอนสาร",
    "zipcode"=>"36180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"368",
    "province_id"=>"25",
    "name"=>"ภักดีชุมพล",
    "zipcode"=>"36260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"369",
    "province_id"=>"25",
    "name"=>"เนินสง่า",
    "zipcode"=>"36130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"370",
    "province_id"=>"25",
    "name"=>"ซับใหญ่",
    "zipcode"=>"36130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"380",
    "province_id"=>"26",
    "name"=>"เมืองอำนาจเจริญ",
    "zipcode"=>"37000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"381",
    "province_id"=>"26",
    "name"=>"ชานุมาน",
    "zipcode"=>"37210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"382",
    "province_id"=>"26",
    "name"=>"ปทุมราชวงศา",
    "zipcode"=>"37110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"383",
    "province_id"=>"26",
    "name"=>"พนา",
    "zipcode"=>"37180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"384",
    "province_id"=>"26",
    "name"=>"เสนางคนิคม",
    "zipcode"=>"37290",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"385",
    "province_id"=>"26",
    "name"=>"หัวตะพาน",
    "zipcode"=>"37240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"386",
    "province_id"=>"26",
    "name"=>"ลืออำนาจ",
    "zipcode"=>"37000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"387",
    "province_id"=>"27",
    "name"=>"เมืองหนองบัวลำภู",
    "zipcode"=>"39000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"388",
    "province_id"=>"27",
    "name"=>"นากลาง",
    "zipcode"=>"39170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"389",
    "province_id"=>"27",
    "name"=>"โนนสัง",
    "zipcode"=>"39140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"390",
    "province_id"=>"27",
    "name"=>"ศรีบุญเรือง",
    "zipcode"=>"39180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"391",
    "province_id"=>"27",
    "name"=>"สุวรรณคูหา",
    "zipcode"=>"39270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"392",
    "province_id"=>"27",
    "name"=>"นาวัง",
    "zipcode"=>"39170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"393",
    "province_id"=>"28",
    "name"=>"เมืองขอนแก่น",
    "zipcode"=>"40000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"394",
    "province_id"=>"28",
    "name"=>"บ้านฝาง",
    "zipcode"=>"40270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"395",
    "province_id"=>"28",
    "name"=>"พระยืน",
    "zipcode"=>"40320",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"396",
    "province_id"=>"28",
    "name"=>"หนองเรือ",
    "zipcode"=>"40210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"397",
    "province_id"=>"28",
    "name"=>"ชุมแพ",
    "zipcode"=>"40130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"398",
    "province_id"=>"28",
    "name"=>"สีชมพู",
    "zipcode"=>"40220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"399",
    "province_id"=>"28",
    "name"=>"น้ำพอง",
    "zipcode"=>"40140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"400",
    "province_id"=>"28",
    "name"=>"อุบลรัตน์",
    "zipcode"=>"40250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"401",
    "province_id"=>"28",
    "name"=>"กระนวน",
    "zipcode"=>"40170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"402",
    "province_id"=>"28",
    "name"=>"บ้านไผ่",
    "zipcode"=>"40110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"403",
    "province_id"=>"28",
    "name"=>"เปือยน้อย",
    "zipcode"=>"40340",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"404",
    "province_id"=>"28",
    "name"=>"พล",
    "zipcode"=>"40120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"405",
    "province_id"=>"28",
    "name"=>"แวงใหญ่",
    "zipcode"=>"40330",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"406",
    "province_id"=>"28",
    "name"=>"แวงน้อย",
    "zipcode"=>"40230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"407",
    "province_id"=>"28",
    "name"=>"หนองสองห้อง",
    "zipcode"=>"40190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"408",
    "province_id"=>"28",
    "name"=>"ภูเวียง",
    "zipcode"=>"40150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"409",
    "province_id"=>"28",
    "name"=>"มัญจาคีรี",
    "zipcode"=>"40160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"410",
    "province_id"=>"28",
    "name"=>"ชนบท",
    "zipcode"=>"40180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"411",
    "province_id"=>"28",
    "name"=>"เขาสวนกวาง",
    "zipcode"=>"40280",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"412",
    "province_id"=>"28",
    "name"=>"ภูผาม่าน",
    "zipcode"=>"40350",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"413",
    "province_id"=>"28",
    "name"=>"ซำสูง",
    "zipcode"=>"40170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"414",
    "province_id"=>"28",
    "name"=>"โคกโพธิ์ไชย",
    "zipcode"=>"40160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"415",
    "province_id"=>"28",
    "name"=>"หนองนาคำ",
    "zipcode"=>"40150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"416",
    "province_id"=>"28",
    "name"=>"บ้านแฮด",
    "zipcode"=>"40110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"417",
    "province_id"=>"28",
    "name"=>"โนนศิลา",
    "zipcode"=>"40110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"421",
    "province_id"=>"29",
    "name"=>"เมืองอุดรธานี",
    "zipcode"=>"41000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"422",
    "province_id"=>"29",
    "name"=>"กุดจับ",
    "zipcode"=>"41250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"423",
    "province_id"=>"29",
    "name"=>"หนองวัวซอ",
    "zipcode"=>"41360",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"424",
    "province_id"=>"29",
    "name"=>"กุมภวาปี",
    "zipcode"=>"41110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"425",
    "province_id"=>"29",
    "name"=>"โนนสะอาด",
    "zipcode"=>"41240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"426",
    "province_id"=>"29",
    "name"=>"หนองหาน",
    "zipcode"=>"41130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"427",
    "province_id"=>"29",
    "name"=>"ทุ่งฝน",
    "zipcode"=>"41310",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"428",
    "province_id"=>"29",
    "name"=>"ไชยวาน",
    "zipcode"=>"41290",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"429",
    "province_id"=>"29",
    "name"=>"ศรีธาตุ",
    "zipcode"=>"41230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"430",
    "province_id"=>"29",
    "name"=>"วังสามหมอ",
    "zipcode"=>"41280",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"431",
    "province_id"=>"29",
    "name"=>"บ้านดุง",
    "zipcode"=>"41190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"437",
    "province_id"=>"29",
    "name"=>"บ้านผือ",
    "zipcode"=>"41160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"438",
    "province_id"=>"29",
    "name"=>"น้ำโสม",
    "zipcode"=>"41210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"439",
    "province_id"=>"29",
    "name"=>"เพ็ญ",
    "zipcode"=>"41150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"440",
    "province_id"=>"29",
    "name"=>"สร้างคอม",
    "zipcode"=>"41260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"441",
    "province_id"=>"29",
    "name"=>"หนองแสง",
    "zipcode"=>"41340",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"442",
    "province_id"=>"29",
    "name"=>"นายูง",
    "zipcode"=>"41380",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"443",
    "province_id"=>"29",
    "name"=>"พิบูลย์รักษ์",
    "zipcode"=>"41130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"444",
    "province_id"=>"29",
    "name"=>"กู่แก้ว",
    "zipcode"=>"41130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"445",
    "province_id"=>"29",
    "name"=>"ประจักษ์ศิลปาคม",
    "zipcode"=>"41110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"446",
    "province_id"=>"30",
    "name"=>"เมืองเลย",
    "zipcode"=>"42000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"447",
    "province_id"=>"30",
    "name"=>"นาด้วง",
    "zipcode"=>"42210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"448",
    "province_id"=>"30",
    "name"=>"เชียงคาน",
    "zipcode"=>"42110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"449",
    "province_id"=>"30",
    "name"=>"ปากชม",
    "zipcode"=>"42150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"450",
    "province_id"=>"30",
    "name"=>"ด่านซ้าย",
    "zipcode"=>"42120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"451",
    "province_id"=>"30",
    "name"=>"นาแห้ว",
    "zipcode"=>"42170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"452",
    "province_id"=>"30",
    "name"=>"ภูเรือ",
    "zipcode"=>"42160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"453",
    "province_id"=>"30",
    "name"=>"ท่าลี่",
    "zipcode"=>"42140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"454",
    "province_id"=>"30",
    "name"=>"วังสะพุง",
    "zipcode"=>"42130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"455",
    "province_id"=>"30",
    "name"=>"ภูกระดึง",
    "zipcode"=>"42180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"456",
    "province_id"=>"30",
    "name"=>"ภูหลวง",
    "zipcode"=>"42230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"457",
    "province_id"=>"30",
    "name"=>"ผาขาว",
    "zipcode"=>"42240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"458",
    "province_id"=>"30",
    "name"=>"เอราวัณ",
    "zipcode"=>"42220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"459",
    "province_id"=>"30",
    "name"=>"หนองหิน",
    "zipcode"=>"42190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"460",
    "province_id"=>"31",
    "name"=>"เมืองหนองคาย",
    "zipcode"=>"43000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"461",
    "province_id"=>"31",
    "name"=>"ท่าบ่อ",
    "zipcode"=>"43110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"462",
    "province_id"=>"77",
    "name"=>"เมืองบึงกาฬ",
    "zipcode"=>"43140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"463",
    "province_id"=>"77",
    "name"=>"พรเจริญ",
    "zipcode"=>"43180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"464",
    "province_id"=>"31",
    "name"=>"โพนพิสัย",
    "zipcode"=>"43120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"465",
    "province_id"=>"77",
    "name"=>"โซ่พิสัย",
    "zipcode"=>"43170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"466",
    "province_id"=>"31",
    "name"=>"ศรีเชียงใหม่",
    "zipcode"=>"43130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"467",
    "province_id"=>"31",
    "name"=>"สังคม",
    "zipcode"=>"43160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"468",
    "province_id"=>"77",
    "name"=>"เซกา",
    "zipcode"=>"43150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"469",
    "province_id"=>"77",
    "name"=>"ปากคาด",
    "zipcode"=>"43190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"470",
    "province_id"=>"77",
    "name"=>"บึงโขงหลง",
    "zipcode"=>"43220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"471",
    "province_id"=>"77",
    "name"=>"ศรีวิไล",
    "zipcode"=>"43210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"472",
    "province_id"=>"77",
    "name"=>"บุ่งคล้า",
    "zipcode"=>"43140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"473",
    "province_id"=>"31",
    "name"=>"สระใคร",
    "zipcode"=>"43100",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"474",
    "province_id"=>"31",
    "name"=>"เฝ้าไร่",
    "zipcode"=>"43120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"475",
    "province_id"=>"31",
    "name"=>"รัตนวาปี",
    "zipcode"=>"43120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"476",
    "province_id"=>"31",
    "name"=>"โพธิ์ตาก",
    "zipcode"=>"43130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"477",
    "province_id"=>"32",
    "name"=>"เมืองมหาสารคาม",
    "zipcode"=>"44000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"478",
    "province_id"=>"32",
    "name"=>"แกดำ",
    "zipcode"=>"44190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"479",
    "province_id"=>"32",
    "name"=>"โกสุมพิสัย",
    "zipcode"=>"44140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"480",
    "province_id"=>"32",
    "name"=>"กันทรวิชัย",
    "zipcode"=>"44150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"481",
    "province_id"=>"32",
    "name"=>"เชียงยืน",
    "zipcode"=>"44160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"482",
    "province_id"=>"32",
    "name"=>"บรบือ",
    "zipcode"=>"44130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"483",
    "province_id"=>"32",
    "name"=>"นาเชือก",
    "zipcode"=>"44170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"484",
    "province_id"=>"32",
    "name"=>"พยัคฆภูมิพิสัย",
    "zipcode"=>"44110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"485",
    "province_id"=>"32",
    "name"=>"วาปีปทุม",
    "zipcode"=>"44120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"486",
    "province_id"=>"32",
    "name"=>"นาดูน",
    "zipcode"=>"44180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"487",
    "province_id"=>"32",
    "name"=>"ยางสีสุราช",
    "zipcode"=>"44210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"488",
    "province_id"=>"32",
    "name"=>"กุดรัง",
    "zipcode"=>"44130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"489",
    "province_id"=>"32",
    "name"=>"ชื่นชม",
    "zipcode"=>"44160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"491",
    "province_id"=>"33",
    "name"=>"เมืองร้อยเอ็ด",
    "zipcode"=>"45000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"492",
    "province_id"=>"33",
    "name"=>"เกษตรวิสัย",
    "zipcode"=>"45150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"493",
    "province_id"=>"33",
    "name"=>"ปทุมรัตต์",
    "zipcode"=>"45190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"494",
    "province_id"=>"33",
    "name"=>"จตุรพักตรพิมาน",
    "zipcode"=>"45180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"495",
    "province_id"=>"33",
    "name"=>"ธวัชบุรี",
    "zipcode"=>"45170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"496",
    "province_id"=>"33",
    "name"=>"พนมไพร",
    "zipcode"=>"45140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"497",
    "province_id"=>"33",
    "name"=>"โพนทอง",
    "zipcode"=>"45110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"498",
    "province_id"=>"33",
    "name"=>"โพธิ์ชัย",
    "zipcode"=>"45230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"499",
    "province_id"=>"33",
    "name"=>"หนองพอก",
    "zipcode"=>"45210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"500",
    "province_id"=>"33",
    "name"=>"เสลภูมิ",
    "zipcode"=>"45120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"501",
    "province_id"=>"33",
    "name"=>"สุวรรณภูมิ",
    "zipcode"=>"45130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"502",
    "province_id"=>"33",
    "name"=>"เมืองสรวง",
    "zipcode"=>"45220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"503",
    "province_id"=>"33",
    "name"=>"โพนทราย",
    "zipcode"=>"45240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"504",
    "province_id"=>"33",
    "name"=>"อาจสามารถ",
    "zipcode"=>"45160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"505",
    "province_id"=>"33",
    "name"=>"เมยวดี",
    "zipcode"=>"45250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"506",
    "province_id"=>"33",
    "name"=>"ศรีสมเด็จ",
    "zipcode"=>"45000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"507",
    "province_id"=>"33",
    "name"=>"จังหาร",
    "zipcode"=>"45000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"508",
    "province_id"=>"33",
    "name"=>"เชียงขวัญ",
    "zipcode"=>"45000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"509",
    "province_id"=>"33",
    "name"=>"หนองฮี",
    "zipcode"=>"45140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"510",
    "province_id"=>"33",
    "name"=>"ทุ่งเขาหลวง",
    "zipcode"=>"45170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"511",
    "province_id"=>"34",
    "name"=>"เมืองกาฬสินธุ์",
    "zipcode"=>"46000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"512",
    "province_id"=>"34",
    "name"=>"นามน",
    "zipcode"=>"46230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"513",
    "province_id"=>"34",
    "name"=>"กมลาไสย",
    "zipcode"=>"46130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"514",
    "province_id"=>"34",
    "name"=>"ร่องคำ",
    "zipcode"=>"46210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"515",
    "province_id"=>"34",
    "name"=>"กุฉินารายณ์",
    "zipcode"=>"46110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"516",
    "province_id"=>"34",
    "name"=>"เขาวง",
    "zipcode"=>"46160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"517",
    "province_id"=>"34",
    "name"=>"ยางตลาด",
    "zipcode"=>"46120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"518",
    "province_id"=>"34",
    "name"=>"ห้วยเม็ก",
    "zipcode"=>"46170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"519",
    "province_id"=>"34",
    "name"=>"สหัสขันธ์",
    "zipcode"=>"46140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"520",
    "province_id"=>"34",
    "name"=>"คำม่วง",
    "zipcode"=>"46180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"521",
    "province_id"=>"34",
    "name"=>"ท่าคันโท",
    "zipcode"=>"46190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"522",
    "province_id"=>"34",
    "name"=>"หนองกุงศรี",
    "zipcode"=>"46220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"523",
    "province_id"=>"34",
    "name"=>"สมเด็จ",
    "zipcode"=>"46150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"524",
    "province_id"=>"34",
    "name"=>"ห้วยผึ้ง",
    "zipcode"=>"46240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"525",
    "province_id"=>"34",
    "name"=>"สามชัย",
    "zipcode"=>"46180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"526",
    "province_id"=>"34",
    "name"=>"นาคู",
    "zipcode"=>"46160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"527",
    "province_id"=>"34",
    "name"=>"ดอนจาน",
    "zipcode"=>"46000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"528",
    "province_id"=>"34",
    "name"=>"ฆ้องชัย",
    "zipcode"=>"46130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"529",
    "province_id"=>"35",
    "name"=>"เมืองสกลนคร",
    "zipcode"=>"47000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"530",
    "province_id"=>"35",
    "name"=>"กุสุมาลย์",
    "zipcode"=>"47210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"531",
    "province_id"=>"35",
    "name"=>"กุดบาก",
    "zipcode"=>"47180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"532",
    "province_id"=>"35",
    "name"=>"พรรณานิคม",
    "zipcode"=>"47130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"533",
    "province_id"=>"35",
    "name"=>"พังโคน",
    "zipcode"=>"47160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"534",
    "province_id"=>"35",
    "name"=>"วาริชภูมิ",
    "zipcode"=>"47150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"535",
    "province_id"=>"35",
    "name"=>"นิคมน้ำอูน",
    "zipcode"=>"47270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"536",
    "province_id"=>"35",
    "name"=>"วานรนิวาส",
    "zipcode"=>"47120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"537",
    "province_id"=>"35",
    "name"=>"คำตากล้า",
    "zipcode"=>"47250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"538",
    "province_id"=>"35",
    "name"=>"บ้านม่วง",
    "zipcode"=>"47140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"539",
    "province_id"=>"35",
    "name"=>"อากาศอำนวย",
    "zipcode"=>"47170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"540",
    "province_id"=>"35",
    "name"=>"สว่างแดนดิน",
    "zipcode"=>"47110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"541",
    "province_id"=>"35",
    "name"=>"ส่องดาว",
    "zipcode"=>"47190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"542",
    "province_id"=>"35",
    "name"=>"เต่างอย",
    "zipcode"=>"47260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"543",
    "province_id"=>"35",
    "name"=>"โคกศรีสุพรรณ",
    "zipcode"=>"47280",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"544",
    "province_id"=>"35",
    "name"=>"เจริญศิลป์",
    "zipcode"=>"47290",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"545",
    "province_id"=>"35",
    "name"=>"โพนนาแก้ว",
    "zipcode"=>"47230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"546",
    "province_id"=>"35",
    "name"=>"ภูพาน",
    "zipcode"=>"47180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"549",
    "province_id"=>"36",
    "name"=>"เมืองนครพนม",
    "zipcode"=>"48000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"550",
    "province_id"=>"36",
    "name"=>"ปลาปาก",
    "zipcode"=>"48160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"551",
    "province_id"=>"36",
    "name"=>"ท่าอุเทน",
    "zipcode"=>"48120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"552",
    "province_id"=>"36",
    "name"=>"บ้านแพง",
    "zipcode"=>"48140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"553",
    "province_id"=>"36",
    "name"=>"ธาตุพนม",
    "zipcode"=>"48110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"554",
    "province_id"=>"36",
    "name"=>"เรณูนคร",
    "zipcode"=>"48170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"555",
    "province_id"=>"36",
    "name"=>"นาแก",
    "zipcode"=>"48130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"556",
    "province_id"=>"36",
    "name"=>"ศรีสงคราม",
    "zipcode"=>"48150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"557",
    "province_id"=>"36",
    "name"=>"นาหว้า",
    "zipcode"=>"48180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"558",
    "province_id"=>"36",
    "name"=>"โพนสวรรค์",
    "zipcode"=>"48190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"559",
    "province_id"=>"36",
    "name"=>"นาทม",
    "zipcode"=>"48140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"560",
    "province_id"=>"36",
    "name"=>"วังยาง",
    "zipcode"=>"48130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"561",
    "province_id"=>"37",
    "name"=>"เมืองมุกดาหาร",
    "zipcode"=>"49000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"562",
    "province_id"=>"37",
    "name"=>"นิคมคำสร้อย",
    "zipcode"=>"49130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"563",
    "province_id"=>"37",
    "name"=>"ดอนตาล",
    "zipcode"=>"49120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"564",
    "province_id"=>"37",
    "name"=>"ดงหลวง",
    "zipcode"=>"49140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"565",
    "province_id"=>"37",
    "name"=>"คำชะอี",
    "zipcode"=>"49110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"566",
    "province_id"=>"37",
    "name"=>"หว้านใหญ่",
    "zipcode"=>"49150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"567",
    "province_id"=>"37",
    "name"=>"หนองสูง",
    "zipcode"=>"49160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"568",
    "province_id"=>"38",
    "name"=>"เมืองเชียงใหม่",
    "zipcode"=>"50200",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"569",
    "province_id"=>"38",
    "name"=>"จอมทอง",
    "zipcode"=>"50160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"570",
    "province_id"=>"38",
    "name"=>"แม่แจ่ม",
    "zipcode"=>"50270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"571",
    "province_id"=>"38",
    "name"=>"เชียงดาว",
    "zipcode"=>"50170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"572",
    "province_id"=>"38",
    "name"=>"ดอยสะเก็ด",
    "zipcode"=>"50220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"573",
    "province_id"=>"38",
    "name"=>"แม่แตง",
    "zipcode"=>"50150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"574",
    "province_id"=>"38",
    "name"=>"แม่ริม",
    "zipcode"=>"50180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"575",
    "province_id"=>"38",
    "name"=>"สะเมิง",
    "zipcode"=>"50250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"576",
    "province_id"=>"38",
    "name"=>"ฝาง",
    "zipcode"=>"50110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"577",
    "province_id"=>"38",
    "name"=>"แม่อาย",
    "zipcode"=>"50280",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"578",
    "province_id"=>"38",
    "name"=>"พร้าว",
    "zipcode"=>"50190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"579",
    "province_id"=>"38",
    "name"=>"สันป่าตอง",
    "zipcode"=>"50120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"580",
    "province_id"=>"38",
    "name"=>"สันกำแพง",
    "zipcode"=>"50130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"581",
    "province_id"=>"38",
    "name"=>"สันทราย",
    "zipcode"=>"50210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"582",
    "province_id"=>"38",
    "name"=>"หางดง",
    "zipcode"=>"50230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"583",
    "province_id"=>"38",
    "name"=>"ฮอด",
    "zipcode"=>"50240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"584",
    "province_id"=>"38",
    "name"=>"ดอยเต่า",
    "zipcode"=>"50260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"585",
    "province_id"=>"38",
    "name"=>"อมก๋อย",
    "zipcode"=>"50310",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"586",
    "province_id"=>"38",
    "name"=>"สารภี",
    "zipcode"=>"50140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"587",
    "province_id"=>"38",
    "name"=>"เวียงแหง",
    "zipcode"=>"50350",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"588",
    "province_id"=>"38",
    "name"=>"ไชยปราการ",
    "zipcode"=>"50320",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"589",
    "province_id"=>"38",
    "name"=>"แม่วาง",
    "zipcode"=>"50360",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"590",
    "province_id"=>"38",
    "name"=>"แม่ออน",
    "zipcode"=>"50130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"591",
    "province_id"=>"38",
    "name"=>"ดอยหล่อ",
    "zipcode"=>"50160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"595",
    "province_id"=>"39",
    "name"=>"เมืองลำพูน",
    "zipcode"=>"51000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"596",
    "province_id"=>"39",
    "name"=>"แม่ทา",
    "zipcode"=>"51140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"597",
    "province_id"=>"39",
    "name"=>"บ้านโฮ่ง",
    "zipcode"=>"51130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"598",
    "province_id"=>"39",
    "name"=>"ลี้",
    "zipcode"=>"51110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"599",
    "province_id"=>"39",
    "name"=>"ทุ่งหัวช้าง",
    "zipcode"=>"51160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"600",
    "province_id"=>"39",
    "name"=>"ป่าซาง",
    "zipcode"=>"51120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"601",
    "province_id"=>"39",
    "name"=>"บ้านธิ",
    "zipcode"=>"51180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"602",
    "province_id"=>"39",
    "name"=>"เวียงหนองล่อง",
    "zipcode"=>"51120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"603",
    "province_id"=>"40",
    "name"=>"เมืองลำปาง",
    "zipcode"=>"52000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"604",
    "province_id"=>"40",
    "name"=>"แม่เมาะ",
    "zipcode"=>"52220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"605",
    "province_id"=>"40",
    "name"=>"เกาะคา",
    "zipcode"=>"52130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"606",
    "province_id"=>"40",
    "name"=>"เสริมงาม",
    "zipcode"=>"52210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"607",
    "province_id"=>"40",
    "name"=>"งาว",
    "zipcode"=>"52110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"608",
    "province_id"=>"40",
    "name"=>"แจ้ห่ม",
    "zipcode"=>"52120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"609",
    "province_id"=>"40",
    "name"=>"วังเหนือ",
    "zipcode"=>"52140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"610",
    "province_id"=>"40",
    "name"=>"เถิน",
    "zipcode"=>"52160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"611",
    "province_id"=>"40",
    "name"=>"แม่พริก",
    "zipcode"=>"52180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"612",
    "province_id"=>"40",
    "name"=>"แม่ทะ",
    "zipcode"=>"52150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"613",
    "province_id"=>"40",
    "name"=>"สบปราบ",
    "zipcode"=>"52170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"614",
    "province_id"=>"40",
    "name"=>"ห้างฉัตร",
    "zipcode"=>"52190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"615",
    "province_id"=>"40",
    "name"=>"เมืองปาน",
    "zipcode"=>"52240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"616",
    "province_id"=>"41",
    "name"=>"เมืองอุตรดิตถ์",
    "zipcode"=>"53000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"617",
    "province_id"=>"41",
    "name"=>"ตรอน",
    "zipcode"=>"53140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"618",
    "province_id"=>"41",
    "name"=>"ท่าปลา",
    "zipcode"=>"53150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"619",
    "province_id"=>"41",
    "name"=>"น้ำปาด",
    "zipcode"=>"53110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"620",
    "province_id"=>"41",
    "name"=>"ฟากท่า",
    "zipcode"=>"53160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"621",
    "province_id"=>"41",
    "name"=>"บ้านโคก",
    "zipcode"=>"53180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"622",
    "province_id"=>"41",
    "name"=>"พิชัย",
    "zipcode"=>"53120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"623",
    "province_id"=>"41",
    "name"=>"ลับแล",
    "zipcode"=>"53130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"624",
    "province_id"=>"41",
    "name"=>"ทองแสนขัน",
    "zipcode"=>"53230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"625",
    "province_id"=>"42",
    "name"=>"เมืองแพร่",
    "zipcode"=>"54000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"626",
    "province_id"=>"42",
    "name"=>"ร้องกวาง",
    "zipcode"=>"54140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"627",
    "province_id"=>"42",
    "name"=>"ลอง",
    "zipcode"=>"54150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"628",
    "province_id"=>"42",
    "name"=>"สูงเม่น",
    "zipcode"=>"54130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"629",
    "province_id"=>"42",
    "name"=>"เด่นชัย",
    "zipcode"=>"54110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"630",
    "province_id"=>"42",
    "name"=>"สอง",
    "zipcode"=>"54120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"631",
    "province_id"=>"42",
    "name"=>"วังชิ้น",
    "zipcode"=>"54160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"632",
    "province_id"=>"42",
    "name"=>"หนองม่วงไข่",
    "zipcode"=>"54170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"633",
    "province_id"=>"43",
    "name"=>"เมืองน่าน",
    "zipcode"=>"55000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"634",
    "province_id"=>"43",
    "name"=>"แม่จริม",
    "zipcode"=>"55170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"635",
    "province_id"=>"43",
    "name"=>"บ้านหลวง",
    "zipcode"=>"55190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"636",
    "province_id"=>"43",
    "name"=>"นาน้อย",
    "zipcode"=>"55150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"637",
    "province_id"=>"43",
    "name"=>"ปัว",
    "zipcode"=>"55120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"638",
    "province_id"=>"43",
    "name"=>"ท่าวังผา",
    "zipcode"=>"55140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"639",
    "province_id"=>"43",
    "name"=>"เวียงสา",
    "zipcode"=>"55110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"640",
    "province_id"=>"43",
    "name"=>"ทุ่งช้าง",
    "zipcode"=>"55130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"641",
    "province_id"=>"43",
    "name"=>"เชียงกลาง",
    "zipcode"=>"55160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"642",
    "province_id"=>"43",
    "name"=>"นาหมื่น",
    "zipcode"=>"55180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"643",
    "province_id"=>"43",
    "name"=>"สันติสุข",
    "zipcode"=>"55210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"644",
    "province_id"=>"43",
    "name"=>"บ่อเกลือ",
    "zipcode"=>"55220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"645",
    "province_id"=>"43",
    "name"=>"สองแคว",
    "zipcode"=>"55160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"646",
    "province_id"=>"43",
    "name"=>"ภูเพียง",
    "zipcode"=>"55000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"647",
    "province_id"=>"43",
    "name"=>"เฉลิมพระเกียรติ",
    "zipcode"=>"55130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"648",
    "province_id"=>"44",
    "name"=>"เมืองพะเยา",
    "zipcode"=>"56000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"649",
    "province_id"=>"44",
    "name"=>"จุน",
    "zipcode"=>"56150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"650",
    "province_id"=>"44",
    "name"=>"เชียงคำ",
    "zipcode"=>"56110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"651",
    "province_id"=>"44",
    "name"=>"เชียงม่วน",
    "zipcode"=>"56160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"652",
    "province_id"=>"44",
    "name"=>"ดอกคำใต้",
    "zipcode"=>"56120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"653",
    "province_id"=>"44",
    "name"=>"ปง",
    "zipcode"=>"56140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"654",
    "province_id"=>"44",
    "name"=>"แม่ใจ",
    "zipcode"=>"56130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"655",
    "province_id"=>"44",
    "name"=>"ภูซาง",
    "zipcode"=>"56110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"656",
    "province_id"=>"44",
    "name"=>"ภูกามยาว",
    "zipcode"=>"56000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"657",
    "province_id"=>"45",
    "name"=>"เมืองเชียงราย",
    "zipcode"=>"57000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"658",
    "province_id"=>"45",
    "name"=>"เวียงชัย",
    "zipcode"=>"57210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"659",
    "province_id"=>"45",
    "name"=>"เชียงของ",
    "zipcode"=>"57140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"660",
    "province_id"=>"45",
    "name"=>"เทิง",
    "zipcode"=>"57160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"661",
    "province_id"=>"45",
    "name"=>"พาน",
    "zipcode"=>"57120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"662",
    "province_id"=>"45",
    "name"=>"ป่าแดด",
    "zipcode"=>"57190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"663",
    "province_id"=>"45",
    "name"=>"แม่จัน",
    "zipcode"=>"57110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"664",
    "province_id"=>"45",
    "name"=>"เชียงแสน",
    "zipcode"=>"57150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"665",
    "province_id"=>"45",
    "name"=>"แม่สาย",
    "zipcode"=>"57130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"666",
    "province_id"=>"45",
    "name"=>"แม่สรวย",
    "zipcode"=>"57180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"667",
    "province_id"=>"45",
    "name"=>"เวียงป่าเป้า",
    "zipcode"=>"57170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"668",
    "province_id"=>"45",
    "name"=>"พญาเม็งราย",
    "zipcode"=>"57290",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"669",
    "province_id"=>"45",
    "name"=>"เวียงแก่น",
    "zipcode"=>"57310",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"670",
    "province_id"=>"45",
    "name"=>"ขุนตาล",
    "zipcode"=>"57340",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"671",
    "province_id"=>"45",
    "name"=>"แม่ฟ้าหลวง",
    "zipcode"=>"57240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"672",
    "province_id"=>"45",
    "name"=>"แม่ลาว",
    "zipcode"=>"57250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"673",
    "province_id"=>"45",
    "name"=>"เวียงเชียงรุ้ง",
    "zipcode"=>"57210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"674",
    "province_id"=>"45",
    "name"=>"ดอยหลวง",
    "zipcode"=>"57110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"675",
    "province_id"=>"46",
    "name"=>"เมืองแม่ฮ่องสอน",
    "zipcode"=>"58000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"676",
    "province_id"=>"46",
    "name"=>"ขุนยวม",
    "zipcode"=>"58140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"677",
    "province_id"=>"46",
    "name"=>"ปาย",
    "zipcode"=>"58130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"678",
    "province_id"=>"46",
    "name"=>"แม่สะเรียง",
    "zipcode"=>"58110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"679",
    "province_id"=>"46",
    "name"=>"แม่ลาน้อย",
    "zipcode"=>"58120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"680",
    "province_id"=>"46",
    "name"=>"สบเมย",
    "zipcode"=>"58110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"681",
    "province_id"=>"46",
    "name"=>"ปางมะผ้า",
    "zipcode"=>"58150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"683",
    "province_id"=>"47",
    "name"=>"เมืองนครสวรรค์",
    "zipcode"=>"60000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"684",
    "province_id"=>"47",
    "name"=>"โกรกพระ",
    "zipcode"=>"60170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"685",
    "province_id"=>"47",
    "name"=>"ชุมแสง",
    "zipcode"=>"60120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"686",
    "province_id"=>"47",
    "name"=>"หนองบัว",
    "zipcode"=>"60110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"687",
    "province_id"=>"47",
    "name"=>"บรรพตพิสัย",
    "zipcode"=>"60180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"688",
    "province_id"=>"47",
    "name"=>"เก้าเลี้ยว",
    "zipcode"=>"60230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"689",
    "province_id"=>"47",
    "name"=>"ตาคลี",
    "zipcode"=>"60140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"690",
    "province_id"=>"47",
    "name"=>"ท่าตะโก",
    "zipcode"=>"60160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"691",
    "province_id"=>"47",
    "name"=>"ไพศาลี",
    "zipcode"=>"60220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"692",
    "province_id"=>"47",
    "name"=>"พยุหะคีรี",
    "zipcode"=>"60130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"693",
    "province_id"=>"47",
    "name"=>"ลาดยาว",
    "zipcode"=>"60150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"694",
    "province_id"=>"47",
    "name"=>"ตากฟ้า",
    "zipcode"=>"60190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"695",
    "province_id"=>"47",
    "name"=>"แม่วงก์",
    "zipcode"=>"60150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"696",
    "province_id"=>"47",
    "name"=>"แม่เปิน",
    "zipcode"=>"60150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"697",
    "province_id"=>"47",
    "name"=>"ชุมตาบง",
    "zipcode"=>"60150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"701",
    "province_id"=>"48",
    "name"=>"เมืองอุทัยธานี",
    "zipcode"=>"61000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"702",
    "province_id"=>"48",
    "name"=>"ทัพทัน",
    "zipcode"=>"61120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"703",
    "province_id"=>"48",
    "name"=>"สว่างอารมณ์",
    "zipcode"=>"61150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"704",
    "province_id"=>"48",
    "name"=>"หนองฉาง",
    "zipcode"=>"61110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"705",
    "province_id"=>"48",
    "name"=>"หนองขาหย่าง",
    "zipcode"=>"61130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"706",
    "province_id"=>"48",
    "name"=>"บ้านไร่",
    "zipcode"=>"61140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"707",
    "province_id"=>"48",
    "name"=>"ลานสัก",
    "zipcode"=>"61160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"708",
    "province_id"=>"48",
    "name"=>"ห้วยคต",
    "zipcode"=>"61170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"709",
    "province_id"=>"49",
    "name"=>"เมืองกำแพงเพชร",
    "zipcode"=>"62000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"710",
    "province_id"=>"49",
    "name"=>"ไทรงาม",
    "zipcode"=>"62150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"711",
    "province_id"=>"49",
    "name"=>"คลองลาน",
    "zipcode"=>"62180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"712",
    "province_id"=>"49",
    "name"=>"ขาณุวรลักษบุรี",
    "zipcode"=>"62130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"713",
    "province_id"=>"49",
    "name"=>"คลองขลุง",
    "zipcode"=>"62120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"714",
    "province_id"=>"49",
    "name"=>"พรานกระต่าย",
    "zipcode"=>"62110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"715",
    "province_id"=>"49",
    "name"=>"ลานกระบือ",
    "zipcode"=>"62170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"716",
    "province_id"=>"49",
    "name"=>"ทรายทองวัฒนา",
    "zipcode"=>"62190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"717",
    "province_id"=>"49",
    "name"=>"ปางศิลาทอง",
    "zipcode"=>"62120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"718",
    "province_id"=>"49",
    "name"=>"บึงสามัคคี",
    "zipcode"=>"62210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"719",
    "province_id"=>"49",
    "name"=>"โกสัมพีนคร",
    "zipcode"=>"62000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"720",
    "province_id"=>"50",
    "name"=>"เมืองตาก",
    "zipcode"=>"63000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"721",
    "province_id"=>"50",
    "name"=>"บ้านตาก",
    "zipcode"=>"63120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"722",
    "province_id"=>"50",
    "name"=>"สามเงา",
    "zipcode"=>"63130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"723",
    "province_id"=>"50",
    "name"=>"แม่ระมาด",
    "zipcode"=>"63140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"724",
    "province_id"=>"50",
    "name"=>"ท่าสองยาง",
    "zipcode"=>"63150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"725",
    "province_id"=>"50",
    "name"=>"แม่สอด",
    "zipcode"=>"63110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"726",
    "province_id"=>"50",
    "name"=>"พบพระ",
    "zipcode"=>"63160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"727",
    "province_id"=>"50",
    "name"=>"อุ้มผาง",
    "zipcode"=>"63170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"728",
    "province_id"=>"50",
    "name"=>"วังเจ้า",
    "zipcode"=>"63000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"730",
    "province_id"=>"51",
    "name"=>"เมืองสุโขทัย",
    "zipcode"=>"64000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"731",
    "province_id"=>"51",
    "name"=>"บ้านด่านลานหอย",
    "zipcode"=>"64140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"732",
    "province_id"=>"51",
    "name"=>"คีรีมาศ",
    "zipcode"=>"64160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"733",
    "province_id"=>"51",
    "name"=>"กงไกรลาศ",
    "zipcode"=>"64170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"734",
    "province_id"=>"51",
    "name"=>"ศรีสัชนาลัย",
    "zipcode"=>"64130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"735",
    "province_id"=>"51",
    "name"=>"ศรีสำโรง",
    "zipcode"=>"64120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"736",
    "province_id"=>"51",
    "name"=>"สวรรคโลก",
    "zipcode"=>"64110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"737",
    "province_id"=>"51",
    "name"=>"ศรีนคร",
    "zipcode"=>"64180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"738",
    "province_id"=>"51",
    "name"=>"ทุ่งเสลี่ยม",
    "zipcode"=>"64230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"739",
    "province_id"=>"52",
    "name"=>"เมืองพิษณุโลก",
    "zipcode"=>"65000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"740",
    "province_id"=>"52",
    "name"=>"นครไทย",
    "zipcode"=>"65120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"741",
    "province_id"=>"52",
    "name"=>"ชาติตระการ",
    "zipcode"=>"65170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"742",
    "province_id"=>"52",
    "name"=>"บางระกำ",
    "zipcode"=>"65140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"743",
    "province_id"=>"52",
    "name"=>"บางกระทุ่ม",
    "zipcode"=>"65110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"744",
    "province_id"=>"52",
    "name"=>"พรหมพิราม",
    "zipcode"=>"65150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"745",
    "province_id"=>"52",
    "name"=>"วัดโบสถ์",
    "zipcode"=>"65160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"746",
    "province_id"=>"52",
    "name"=>"วังทอง",
    "zipcode"=>"65130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"747",
    "province_id"=>"52",
    "name"=>"เนินมะปราง",
    "zipcode"=>"65190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"748",
    "province_id"=>"53",
    "name"=>"เมืองพิจิตร",
    "zipcode"=>"66000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"749",
    "province_id"=>"53",
    "name"=>"วังทรายพูน",
    "zipcode"=>"66180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"750",
    "province_id"=>"53",
    "name"=>"โพธิ์ประทับช้าง",
    "zipcode"=>"66190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"751",
    "province_id"=>"53",
    "name"=>"ตะพานหิน",
    "zipcode"=>"66110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"752",
    "province_id"=>"53",
    "name"=>"บางมูลนาก",
    "zipcode"=>"66120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"753",
    "province_id"=>"53",
    "name"=>"โพทะเล",
    "zipcode"=>"66130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"754",
    "province_id"=>"53",
    "name"=>"สามง่าม",
    "zipcode"=>"66140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"755",
    "province_id"=>"53",
    "name"=>"ทับคล้อ",
    "zipcode"=>"66150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"756",
    "province_id"=>"53",
    "name"=>"สากเหล็ก",
    "zipcode"=>"66160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"757",
    "province_id"=>"53",
    "name"=>"บึงนาราง",
    "zipcode"=>"66130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"758",
    "province_id"=>"53",
    "name"=>"ดงเจริญ",
    "zipcode"=>"66210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"759",
    "province_id"=>"53",
    "name"=>"วชิรบารมี",
    "zipcode"=>"66140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"760",
    "province_id"=>"54",
    "name"=>"เมืองเพชรบูรณ์",
    "zipcode"=>"67000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"761",
    "province_id"=>"54",
    "name"=>"ชนแดน",
    "zipcode"=>"67150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"762",
    "province_id"=>"54",
    "name"=>"หล่มสัก",
    "zipcode"=>"67110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"763",
    "province_id"=>"54",
    "name"=>"หล่มเก่า",
    "zipcode"=>"67120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"764",
    "province_id"=>"54",
    "name"=>"วิเชียรบุรี",
    "zipcode"=>"67130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"765",
    "province_id"=>"54",
    "name"=>"ศรีเทพ",
    "zipcode"=>"67170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"766",
    "province_id"=>"54",
    "name"=>"หนองไผ่",
    "zipcode"=>"67140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"767",
    "province_id"=>"54",
    "name"=>"บึงสามพัน",
    "zipcode"=>"67160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"768",
    "province_id"=>"54",
    "name"=>"น้ำหนาว",
    "zipcode"=>"67260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"769",
    "province_id"=>"54",
    "name"=>"วังโป่ง",
    "zipcode"=>"67240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"770",
    "province_id"=>"54",
    "name"=>"เขาค้อ",
    "zipcode"=>"67270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"771",
    "province_id"=>"55",
    "name"=>"เมืองราชบุรี",
    "zipcode"=>"70000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"772",
    "province_id"=>"55",
    "name"=>"จอมบึง",
    "zipcode"=>"70150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"773",
    "province_id"=>"55",
    "name"=>"สวนผึ้ง",
    "zipcode"=>"70180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"774",
    "province_id"=>"55",
    "name"=>"ดำเนินสะดวก",
    "zipcode"=>"70130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"775",
    "province_id"=>"55",
    "name"=>"บ้านโป่ง",
    "zipcode"=>"70110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"776",
    "province_id"=>"55",
    "name"=>"บางแพ",
    "zipcode"=>"70160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"777",
    "province_id"=>"55",
    "name"=>"โพธาราม",
    "zipcode"=>"70120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"778",
    "province_id"=>"55",
    "name"=>"ปากท่อ",
    "zipcode"=>"70140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"779",
    "province_id"=>"55",
    "name"=>"วัดเพลง",
    "zipcode"=>"70170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"780",
    "province_id"=>"55",
    "name"=>"บ้านคา",
    "zipcode"=>"70180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"782",
    "province_id"=>"56",
    "name"=>"เมืองกาญจนบุรี",
    "zipcode"=>"71000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"783",
    "province_id"=>"56",
    "name"=>"ไทรโยค",
    "zipcode"=>"71150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"784",
    "province_id"=>"56",
    "name"=>"บ่อพลอย",
    "zipcode"=>"71160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"785",
    "province_id"=>"56",
    "name"=>"ศรีสวัสดิ์",
    "zipcode"=>"71250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"786",
    "province_id"=>"56",
    "name"=>"ท่ามะกา",
    "zipcode"=>"71120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"787",
    "province_id"=>"56",
    "name"=>"ท่าม่วง",
    "zipcode"=>"71110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"788",
    "province_id"=>"56",
    "name"=>"ทองผาภูมิ",
    "zipcode"=>"71180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"789",
    "province_id"=>"56",
    "name"=>"สังขละบุรี",
    "zipcode"=>"71240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"790",
    "province_id"=>"56",
    "name"=>"พนมทวน",
    "zipcode"=>"71140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"791",
    "province_id"=>"56",
    "name"=>"เลาขวัญ",
    "zipcode"=>"71210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"792",
    "province_id"=>"56",
    "name"=>"ด่านมะขามเตี้ย",
    "zipcode"=>"71260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"793",
    "province_id"=>"56",
    "name"=>"หนองปรือ",
    "zipcode"=>"71220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"794",
    "province_id"=>"56",
    "name"=>"ห้วยกระเจา",
    "zipcode"=>"71170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"797",
    "province_id"=>"57",
    "name"=>"เมืองสุพรรณบุรี",
    "zipcode"=>"72000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"798",
    "province_id"=>"57",
    "name"=>"เดิมบางนางบวช",
    "zipcode"=>"72120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"799",
    "province_id"=>"57",
    "name"=>"ด่านช้าง",
    "zipcode"=>"72180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"800",
    "province_id"=>"57",
    "name"=>"บางปลาม้า",
    "zipcode"=>"72150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"801",
    "province_id"=>"57",
    "name"=>"ศรีประจันต์",
    "zipcode"=>"72140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"802",
    "province_id"=>"57",
    "name"=>"ดอนเจดีย์",
    "zipcode"=>"72170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"803",
    "province_id"=>"57",
    "name"=>"สองพี่น้อง",
    "zipcode"=>"72110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"804",
    "province_id"=>"57",
    "name"=>"สามชุก",
    "zipcode"=>"72130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"805",
    "province_id"=>"57",
    "name"=>"อู่ทอง",
    "zipcode"=>"72160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"806",
    "province_id"=>"57",
    "name"=>"หนองหญ้าไซ",
    "zipcode"=>"72240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"807",
    "province_id"=>"58",
    "name"=>"เมืองนครปฐม",
    "zipcode"=>"73000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"808",
    "province_id"=>"58",
    "name"=>"กำแพงแสน",
    "zipcode"=>"73140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"809",
    "province_id"=>"58",
    "name"=>"นครชัยศรี",
    "zipcode"=>"73120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"810",
    "province_id"=>"58",
    "name"=>"ดอนตูม",
    "zipcode"=>"73150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"811",
    "province_id"=>"58",
    "name"=>"บางเลน",
    "zipcode"=>"73130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"812",
    "province_id"=>"58",
    "name"=>"สามพราน",
    "zipcode"=>"73110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"813",
    "province_id"=>"58",
    "name"=>"พุทธมณฑล",
    "zipcode"=>"73170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"814",
    "province_id"=>"59",
    "name"=>"เมืองสมุทรสาคร",
    "zipcode"=>"74000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"815",
    "province_id"=>"59",
    "name"=>"กระทุ่มแบน",
    "zipcode"=>"74110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"816",
    "province_id"=>"59",
    "name"=>"บ้านแพ้ว",
    "zipcode"=>"74120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"817",
    "province_id"=>"60",
    "name"=>"เมืองสมุทรสงคราม",
    "zipcode"=>"75000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"818",
    "province_id"=>"60",
    "name"=>"บางคนที",
    "zipcode"=>"75120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"819",
    "province_id"=>"60",
    "name"=>"อัมพวา",
    "zipcode"=>"75110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"820",
    "province_id"=>"61",
    "name"=>"เมืองเพชรบุรี",
    "zipcode"=>"76000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"821",
    "province_id"=>"61",
    "name"=>"เขาย้อย",
    "zipcode"=>"76140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"822",
    "province_id"=>"61",
    "name"=>"หนองหญ้าปล้อง",
    "zipcode"=>"76160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"823",
    "province_id"=>"61",
    "name"=>"ชะอำ",
    "zipcode"=>"76120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"824",
    "province_id"=>"61",
    "name"=>"ท่ายาง",
    "zipcode"=>"76130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"825",
    "province_id"=>"61",
    "name"=>"บ้านลาด",
    "zipcode"=>"76150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"826",
    "province_id"=>"61",
    "name"=>"บ้านแหลม",
    "zipcode"=>"76110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"827",
    "province_id"=>"61",
    "name"=>"แก่งกระจาน",
    "zipcode"=>"76170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"828",
    "province_id"=>"62",
    "name"=>"เมืองประจวบคีรีขันธ์",
    "zipcode"=>"77000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"829",
    "province_id"=>"62",
    "name"=>"กุยบุรี",
    "zipcode"=>"77150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"830",
    "province_id"=>"62",
    "name"=>"ทับสะแก",
    "zipcode"=>"77130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"831",
    "province_id"=>"62",
    "name"=>"บางสะพาน",
    "zipcode"=>"77140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"832",
    "province_id"=>"62",
    "name"=>"บางสะพานน้อย",
    "zipcode"=>"77170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"833",
    "province_id"=>"62",
    "name"=>"ปราณบุรี",
    "zipcode"=>"77120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"834",
    "province_id"=>"62",
    "name"=>"หัวหิน",
    "zipcode"=>"77110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"835",
    "province_id"=>"62",
    "name"=>"สามร้อยยอด",
    "zipcode"=>"77120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"836",
    "province_id"=>"63",
    "name"=>"เมืองนครศรีธรรมราช",
    "zipcode"=>"80000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"837",
    "province_id"=>"63",
    "name"=>"พรหมคีรี",
    "zipcode"=>"80320",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"838",
    "province_id"=>"63",
    "name"=>"ลานสกา",
    "zipcode"=>"80230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"839",
    "province_id"=>"63",
    "name"=>"ฉวาง",
    "zipcode"=>"80150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"840",
    "province_id"=>"63",
    "name"=>"พิปูน",
    "zipcode"=>"80270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"841",
    "province_id"=>"63",
    "name"=>"เชียรใหญ่",
    "zipcode"=>"80190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"842",
    "province_id"=>"63",
    "name"=>"ชะอวด",
    "zipcode"=>"80180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"843",
    "province_id"=>"63",
    "name"=>"ท่าศาลา",
    "zipcode"=>"80160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"844",
    "province_id"=>"63",
    "name"=>"ทุ่งสง",
    "zipcode"=>"80110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"845",
    "province_id"=>"63",
    "name"=>"นาบอน",
    "zipcode"=>"80220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"846",
    "province_id"=>"63",
    "name"=>"ทุ่งใหญ่",
    "zipcode"=>"80240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"847",
    "province_id"=>"63",
    "name"=>"ปากพนัง",
    "zipcode"=>"80140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"848",
    "province_id"=>"63",
    "name"=>"ร่อนพิบูลย์",
    "zipcode"=>"80130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"849",
    "province_id"=>"63",
    "name"=>"สิชล",
    "zipcode"=>"80120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"850",
    "province_id"=>"63",
    "name"=>"ขนอม",
    "zipcode"=>"80210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"851",
    "province_id"=>"63",
    "name"=>"หัวไทร",
    "zipcode"=>"80170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"852",
    "province_id"=>"63",
    "name"=>"บางขัน",
    "zipcode"=>"80360",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"853",
    "province_id"=>"63",
    "name"=>"ถ้ำพรรณรา",
    "zipcode"=>"80260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"854",
    "province_id"=>"63",
    "name"=>"จุฬาภรณ์",
    "zipcode"=>"80180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"855",
    "province_id"=>"63",
    "name"=>"พระพรหม",
    "zipcode"=>"80000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"856",
    "province_id"=>"63",
    "name"=>"นบพิตำ",
    "zipcode"=>"80160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"857",
    "province_id"=>"63",
    "name"=>"ช้างกลาง",
    "zipcode"=>"80250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"858",
    "province_id"=>"63",
    "name"=>"เฉลิมพระเกียรติ",
    "zipcode"=>"80190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"864",
    "province_id"=>"64",
    "name"=>"เมืองกระบี่",
    "zipcode"=>"81000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"865",
    "province_id"=>"64",
    "name"=>"เขาพนม",
    "zipcode"=>"81140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"866",
    "province_id"=>"64",
    "name"=>"เกาะลันตา",
    "zipcode"=>"81150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"867",
    "province_id"=>"64",
    "name"=>"คลองท่อม",
    "zipcode"=>"81120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"868",
    "province_id"=>"64",
    "name"=>"อ่าวลึก",
    "zipcode"=>"81110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"869",
    "province_id"=>"64",
    "name"=>"ปลายพระยา",
    "zipcode"=>"81160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"870",
    "province_id"=>"64",
    "name"=>"ลำทับ",
    "zipcode"=>"81120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"871",
    "province_id"=>"64",
    "name"=>"เหนือคลอง",
    "zipcode"=>"81130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"872",
    "province_id"=>"65",
    "name"=>"เมืองพังงา",
    "zipcode"=>"82000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"873",
    "province_id"=>"65",
    "name"=>"เกาะยาว",
    "zipcode"=>"82160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"874",
    "province_id"=>"65",
    "name"=>"กะปง",
    "zipcode"=>"82170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"875",
    "province_id"=>"65",
    "name"=>"ตะกั่วทุ่ง",
    "zipcode"=>"82130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"876",
    "province_id"=>"65",
    "name"=>"ตะกั่วป่า",
    "zipcode"=>"82110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"877",
    "province_id"=>"65",
    "name"=>"คุระบุรี",
    "zipcode"=>"82150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"878",
    "province_id"=>"65",
    "name"=>"ทับปุด",
    "zipcode"=>"82180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"879",
    "province_id"=>"65",
    "name"=>"ท้ายเหมือง",
    "zipcode"=>"82120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"880",
    "province_id"=>"66",
    "name"=>"เมืองภูเก็ต",
    "zipcode"=>"83000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"881",
    "province_id"=>"66",
    "name"=>"กะทู้",
    "zipcode"=>"83120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"882",
    "province_id"=>"66",
    "name"=>"ถลาง",
    "zipcode"=>"83110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"884",
    "province_id"=>"67",
    "name"=>"เมืองสุราษฎร์ธานี",
    "zipcode"=>"84000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"885",
    "province_id"=>"67",
    "name"=>"กาญจนดิษฐ์",
    "zipcode"=>"84290",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"886",
    "province_id"=>"67",
    "name"=>"ดอนสัก",
    "zipcode"=>"84220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"887",
    "province_id"=>"67",
    "name"=>"เกาะสมุย",
    "zipcode"=>"84140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"888",
    "province_id"=>"67",
    "name"=>"เกาะพะงัน",
    "zipcode"=>"84280",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"889",
    "province_id"=>"67",
    "name"=>"ไชยา",
    "zipcode"=>"84110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"890",
    "province_id"=>"67",
    "name"=>"ท่าชนะ",
    "zipcode"=>"84170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"891",
    "province_id"=>"67",
    "name"=>"คีรีรัฐนิคม",
    "zipcode"=>"84180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"892",
    "province_id"=>"67",
    "name"=>"บ้านตาขุน",
    "zipcode"=>"84230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"893",
    "province_id"=>"67",
    "name"=>"พนม",
    "zipcode"=>"84250",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"894",
    "province_id"=>"67",
    "name"=>"ท่าฉาง",
    "zipcode"=>"84150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"895",
    "province_id"=>"67",
    "name"=>"บ้านนาสาร",
    "zipcode"=>"84120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"896",
    "province_id"=>"67",
    "name"=>"บ้านนาเดิม",
    "zipcode"=>"84240",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"897",
    "province_id"=>"67",
    "name"=>"เคียนซา",
    "zipcode"=>"84260",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"898",
    "province_id"=>"67",
    "name"=>"เวียงสระ",
    "zipcode"=>"84190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"899",
    "province_id"=>"67",
    "name"=>"พระแสง",
    "zipcode"=>"84210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"900",
    "province_id"=>"67",
    "name"=>"พุนพิน",
    "zipcode"=>"84130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"901",
    "province_id"=>"67",
    "name"=>"ชัยบุรี",
    "zipcode"=>"84350",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"902",
    "province_id"=>"67",
    "name"=>"วิภาวดี",
    "zipcode"=>"84180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"905",
    "province_id"=>"68",
    "name"=>"เมืองระนอง",
    "zipcode"=>"85000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"906",
    "province_id"=>"68",
    "name"=>"ละอุ่น",
    "zipcode"=>"85130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"907",
    "province_id"=>"68",
    "name"=>"กะเปอร์",
    "zipcode"=>"85120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"908",
    "province_id"=>"68",
    "name"=>"กระบุรี",
    "zipcode"=>"85110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"909",
    "province_id"=>"68",
    "name"=>"สุขสำราญ",
    "zipcode"=>"85120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"910",
    "province_id"=>"69",
    "name"=>"เมืองชุมพร",
    "zipcode"=>"86000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"911",
    "province_id"=>"69",
    "name"=>"ท่าแซะ",
    "zipcode"=>"86140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"912",
    "province_id"=>"69",
    "name"=>"ปะทิว",
    "zipcode"=>"86160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"913",
    "province_id"=>"69",
    "name"=>"หลังสวน",
    "zipcode"=>"86110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"914",
    "province_id"=>"69",
    "name"=>"ละแม",
    "zipcode"=>"86170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"915",
    "province_id"=>"69",
    "name"=>"พะโต๊ะ",
    "zipcode"=>"86180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"916",
    "province_id"=>"69",
    "name"=>"สวี",
    "zipcode"=>"86130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"917",
    "province_id"=>"69",
    "name"=>"ทุ่งตะโก",
    "zipcode"=>"86220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"918",
    "province_id"=>"70",
    "name"=>"เมืองสงขลา",
    "zipcode"=>"90000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"919",
    "province_id"=>"70",
    "name"=>"สทิงพระ",
    "zipcode"=>"90190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"920",
    "province_id"=>"70",
    "name"=>"จะนะ",
    "zipcode"=>"90130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"921",
    "province_id"=>"70",
    "name"=>"นาทวี",
    "zipcode"=>"90160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"922",
    "province_id"=>"70",
    "name"=>"เทพา",
    "zipcode"=>"90150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"923",
    "province_id"=>"70",
    "name"=>"สะบ้าย้อย",
    "zipcode"=>"90210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"924",
    "province_id"=>"70",
    "name"=>"ระโนด",
    "zipcode"=>"90140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"925",
    "province_id"=>"70",
    "name"=>"กระแสสินธุ์",
    "zipcode"=>"90270",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"926",
    "province_id"=>"70",
    "name"=>"รัตภูมิ",
    "zipcode"=>"90180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"927",
    "province_id"=>"70",
    "name"=>"สะเดา",
    "zipcode"=>"90120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"928",
    "province_id"=>"70",
    "name"=>"หาดใหญ่",
    "zipcode"=>"90110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"929",
    "province_id"=>"70",
    "name"=>"นาหม่อม",
    "zipcode"=>"90310",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"930",
    "province_id"=>"70",
    "name"=>"ควนเนียง",
    "zipcode"=>"90220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"931",
    "province_id"=>"70",
    "name"=>"บางกล่ำ",
    "zipcode"=>"90110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"932",
    "province_id"=>"70",
    "name"=>"สิงหนคร",
    "zipcode"=>"90280",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"933",
    "province_id"=>"70",
    "name"=>"คลองหอยโข่ง",
    "zipcode"=>"90230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"936",
    "province_id"=>"71",
    "name"=>"เมืองสตูล",
    "zipcode"=>"91000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"937",
    "province_id"=>"71",
    "name"=>"ควนโดน",
    "zipcode"=>"91160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"938",
    "province_id"=>"71",
    "name"=>"ควนกาหลง",
    "zipcode"=>"91130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"939",
    "province_id"=>"71",
    "name"=>"ท่าแพ",
    "zipcode"=>"91150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"940",
    "province_id"=>"71",
    "name"=>"ละงู",
    "zipcode"=>"91110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"941",
    "province_id"=>"71",
    "name"=>"ทุ่งหว้า",
    "zipcode"=>"91120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"942",
    "province_id"=>"71",
    "name"=>"มะนัง",
    "zipcode"=>"91130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"943",
    "province_id"=>"72",
    "name"=>"เมืองตรัง",
    "zipcode"=>"92000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"944",
    "province_id"=>"72",
    "name"=>"กันตัง",
    "zipcode"=>"92110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"945",
    "province_id"=>"72",
    "name"=>"ย่านตาขาว",
    "zipcode"=>"92140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"946",
    "province_id"=>"72",
    "name"=>"ปะเหลียน",
    "zipcode"=>"92120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"947",
    "province_id"=>"72",
    "name"=>"สิเกา",
    "zipcode"=>"92150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"948",
    "province_id"=>"72",
    "name"=>"ห้วยยอด",
    "zipcode"=>"92130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"949",
    "province_id"=>"72",
    "name"=>"วังวิเศษ",
    "zipcode"=>"92220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"950",
    "province_id"=>"72",
    "name"=>"นาโยง",
    "zipcode"=>"92170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"951",
    "province_id"=>"72",
    "name"=>"รัษฎา",
    "zipcode"=>"92160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"952",
    "province_id"=>"72",
    "name"=>"หาดสำราญ",
    "zipcode"=>"92120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"954",
    "province_id"=>"73",
    "name"=>"เมืองพัทลุง",
    "zipcode"=>"93000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"955",
    "province_id"=>"73",
    "name"=>"กงหรา",
    "zipcode"=>"93180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"956",
    "province_id"=>"73",
    "name"=>"เขาชัยสน",
    "zipcode"=>"93130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"957",
    "province_id"=>"73",
    "name"=>"ตะโหมด",
    "zipcode"=>"93160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"958",
    "province_id"=>"73",
    "name"=>"ควนขนุน",
    "zipcode"=>"93110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"959",
    "province_id"=>"73",
    "name"=>"ปากพะยูน",
    "zipcode"=>"93120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"960",
    "province_id"=>"73",
    "name"=>"ศรีบรรพต",
    "zipcode"=>"93190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"961",
    "province_id"=>"73",
    "name"=>"ป่าบอน",
    "zipcode"=>"93170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"962",
    "province_id"=>"73",
    "name"=>"บางแก้ว",
    "zipcode"=>"93140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"963",
    "province_id"=>"73",
    "name"=>"ป่าพะยอม",
    "zipcode"=>"93110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"964",
    "province_id"=>"73",
    "name"=>"ศรีนครินทร์",
    "zipcode"=>"93000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"965",
    "province_id"=>"74",
    "name"=>"เมืองปัตตานี",
    "zipcode"=>"94000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"966",
    "province_id"=>"74",
    "name"=>"โคกโพธิ์",
    "zipcode"=>"94120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"967",
    "province_id"=>"74",
    "name"=>"หนองจิก",
    "zipcode"=>"94170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"968",
    "province_id"=>"74",
    "name"=>"ปะนาเระ",
    "zipcode"=>"94130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"969",
    "province_id"=>"74",
    "name"=>"มายอ",
    "zipcode"=>"94140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"970",
    "province_id"=>"74",
    "name"=>"ทุ่งยางแดง",
    "zipcode"=>"94140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"971",
    "province_id"=>"74",
    "name"=>"สายบุรี",
    "zipcode"=>"94110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"972",
    "province_id"=>"74",
    "name"=>"ไม้แก่น",
    "zipcode"=>"94220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"973",
    "province_id"=>"74",
    "name"=>"ยะหริ่ง",
    "zipcode"=>"94150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"974",
    "province_id"=>"74",
    "name"=>"ยะรัง",
    "zipcode"=>"94160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"975",
    "province_id"=>"74",
    "name"=>"กะพ้อ",
    "zipcode"=>"94230",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"976",
    "province_id"=>"74",
    "name"=>"แม่ลาน",
    "zipcode"=>"94180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"977",
    "province_id"=>"75",
    "name"=>"เมืองยะลา",
    "zipcode"=>"95000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"978",
    "province_id"=>"75",
    "name"=>"เบตง",
    "zipcode"=>"95110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"979",
    "province_id"=>"75",
    "name"=>"บันนังสตา",
    "zipcode"=>"95130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"980",
    "province_id"=>"75",
    "name"=>"ธารโต",
    "zipcode"=>"95150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"981",
    "province_id"=>"75",
    "name"=>"ยะหา",
    "zipcode"=>"95120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"982",
    "province_id"=>"75",
    "name"=>"รามัน",
    "zipcode"=>"95140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"983",
    "province_id"=>"75",
    "name"=>"กาบัง",
    "zipcode"=>"95120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"984",
    "province_id"=>"75",
    "name"=>"กรงปินัง",
    "zipcode"=>"95000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"985",
    "province_id"=>"76",
    "name"=>"เมืองนราธิวาส",
    "zipcode"=>"96000",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"986",
    "province_id"=>"76",
    "name"=>"ตากใบ",
    "zipcode"=>"96110",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"987",
    "province_id"=>"76",
    "name"=>"บาเจาะ",
    "zipcode"=>"96170",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"988",
    "province_id"=>"76",
    "name"=>"ยี่งอ",
    "zipcode"=>"96180",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"989",
    "province_id"=>"76",
    "name"=>"ระแงะ",
    "zipcode"=>"96130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"990",
    "province_id"=>"76",
    "name"=>"รือเสาะ",
    "zipcode"=>"96150",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"991",
    "province_id"=>"76",
    "name"=>"ศรีสาคร",
    "zipcode"=>"96210",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"992",
    "province_id"=>"76",
    "name"=>"แว้ง",
    "zipcode"=>"96160",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"993",
    "province_id"=>"76",
    "name"=>"สุคิริน",
    "zipcode"=>"96190",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"994",
    "province_id"=>"76",
    "name"=>"สุไหงโก-ลก",
    "zipcode"=>"96120",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"995",
    "province_id"=>"76",
    "name"=>"สุไหงปาดี",
    "zipcode"=>"96140",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"996",
    "province_id"=>"76",
    "name"=>"จะแนะ",
    "zipcode"=>"96220",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
],
[
    "id"=>"997",
    "province_id"=>"76",
    "name"=>"เจาะไอร้อง",
    "zipcode"=>"96130",
    "created_at"=>date("Y-m-d H:i:s"),
    "updated_at"=>date("Y-m-d H:i:s")
]

        ]);
    }
}
