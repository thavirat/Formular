<?php
namespace App;
use Session;
use \App\Models\User;
use \App\Models\Order;
use \App\Models\Live;
use NumberFormatter;
class Help
{
    public static function DateThai($strDate , $format ='d/m/Y' , $para = '/')
	{
        if($strDate){
            $strYear = date("Y",strtotime($strDate))+543;
            $strMonth= date("n",strtotime($strDate));
            $strDay= date("j",strtotime($strDate));
            $strHour= date("H",strtotime($strDate));
            $strMinute= date("i",strtotime($strDate));
            $strSeconds= date("s",strtotime($strDate));
            $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
            $strMonthMax = Array("","มกราคม.","กุมภาพันธ์","มีนาคม","เมษายน" , "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
            $strMonthThai=$strMonthCut[$strMonth];
            $f["Y"] = $strYear;
            $f["y"] = substr($strYear , 2);
            $f["n"] = $strMonth;
            $f["m"] = $strMonthThai;
            $f["M"] = $strMonthMax[$strMonth];
            $f["d"] = $strDay;
            $f["h"] = $strHour;
            $f["i"] = $strMinute;
            $spl = explode($para , $format);
            $return = '';
            if(sizeof($spl)>0){
                foreach($spl as $k=>$fo){
                    $return.= $f[$fo];
                    if($k<(sizeof($spl)-1)){
                        $return.= $para;
                    }
                }
            }
            return $return;
        }

	}

    public static function CheckPermissionMenu($url, $value, $redirect = false) {
        // ดึงสิทธิ์ผ่าน Helper (ซึ่งข้างในมีการเช็ค Auth และ Cache เรียบร้อยแล้ว)
        $permission = \App\Helpers\UserPermission::getMyPermissions();

        // เช็คว่ามี $url นี้ และมี $value นี้ที่เป็น 'T' หรือไม่
        if (isset($permission[$url][$value]) && $permission[$url][$value] == 'T') {
            return true;
        }

        // กรณีเป็นสิทธิ์แบบ Key-Value (ไม่มีระดับ CRUD)
        if (isset($permission[$url]) && $permission[$url] == 'T' && !is_array($permission[$url])) {
            return true;
        }

        // ถ้าไม่ผ่านสิทธิ์
        if ($redirect) {
            // ใช้ abort(403) หรือ redirect ตามต้องการ
            return abort(403, 'Unauthorized action.');
        }

        return false;
    }

    public static function CheckGeneralPermission($key , $value){
        $permission = Session::get('all_permissions');
        if($permission[$key] == $value){
            return true;
        }else{
            return redirect('/admin/PermissionDenined');
        }
    }

    public static function is_expired(){
        return Session::get('is_expired');
    }

    /**
     * Convert Date thai to date for database
     *
     * @param  $date Ex. 2/2/2562
     * @param  $prefix Is parametor for explode ex. / , -
     * @return \Illuminate\Http\Response
     */

    public static function convertDateThaiToDbFormat($date , $prefix){
        $spl = explode($prefix , $date);
        return ($spl[2]-543).'-'.$spl[1].'-'.$spl[0];
    }


    public static function convertDateToDbFormat($date,$para = '/')
    {
        $dateSplit = explode($para, $date);
        return $dateSplit[2].'-'.$dateSplit[1].'-'.$dateSplit[0];
    }

    public static function numberToWords($number , $symbol) {
        $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $cleanNumber = str_replace(',', '', $number);
        $words = $formatter->format($cleanNumber);
        return strtoupper($words) . ' ' . $symbol;
    }


    public static function convertNumToText($number){
        $number = number_format($number, 2, '.', '');
        $numberx = $number;
        $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
        $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
        $number = str_replace(",","",$number);
        $number = str_replace(" ","",$number);
        $number = str_replace("บาท","",$number);
        $number = explode(".",$number);
        if(sizeof($number)>2){
        return 'ทศนิยมหลายตัว';
        exit;
        }
        $strlen = strlen($number[0]);
        $convert = '';
        for($i=0;$i<$strlen;$i++){
            $n = substr($number[0], $i,1);
            if($n!=0){
                if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; }
                elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; }
                elseif($i==($strlen-2) AND $n==1){ $convert .= ''; }
                else{ $convert .= $txtnum1[$n]; }
                $convert .= $txtnum2[$strlen-$i-1];
            }
        }

        $convert .= 'บาท';
        if($number[1]=='0' OR $number[1]=='00' OR
        $number[1]==''){
        $convert .= '';
        }else{
        $strlen = strlen($number[1]);
        for($i=0;$i<$strlen;$i++){
        $n = substr($number[1], $i,1);
            if($n!=0){
            if($i==($strlen-1) AND $n==1){$convert
            .= 'เอ็ด';}
            elseif($i==($strlen-2) AND
            $n==2){$convert .= 'ยี่';}
            elseif($i==($strlen-2) AND
            $n==1){$convert .= '';}
            else{ $convert .= $txtnum1[$n];}
            $convert .= $txtnum2[$strlen-$i-1];
            }
        }
        $convert .= 'สตางค์';
        }
        //แก้ต่ำกว่า 1 บาท ให้แสดงคำว่าศูนย์ แก้ ศูนย์บาท
        if($numberx < 1)
        {
            $convert = "ศูนย์" .  $convert;
        }

        //แก้เอ็ดสตางค์
        $len = strlen($numberx);
        $lendot1 = $len - 2;
        $lendot2 = $len - 1;
        if(($numberx[$lendot1] == 0) && ($numberx[$lendot2] == 1))
        {
            $convert = substr($convert,0,-10);
            $convert = $convert . "หนึ่งสตางค์";
        }

        //แก้เอ็ดบาท สำหรับค่า 1-1.99
        if($numberx >= 1)
        {
            if($numberx < 2)
            {
                $convert = substr($convert,4);
                $convert = "หนึ่ง" .  $convert;
            }
        }
        return $convert;
    }

    public static function isLimit($type)
    {
        $shop = Session::get('shop');
        $limit = $shop->{'limit_'.$type};
        if ($type == 'user') {
            $user_count = User::where('active','T')->count();
            return ( $user_count >= $limit ? true : false );
        }
        if ($type == 'order') {
            $order_count = Order::whereDate('doc_date','>=',date('Y-m-01'))
                                            ->whereDate('doc_date','<=',date('Y-m-t'))
                                            ->count();
            return ( $order_count >= $limit ? true : false );
        }
        if ($type == 'live') {
            $live_count = Live::whereDate('created_at','>=',date('Y-m-01'))
                                            ->whereDate('created_at','<=',date('Y-m-t'))
                                            ->count();
            return ( $live_count >= $limit ? true : false );
        }
    }

    public static function asset_cdn($path = '')
    {
        return env('ASSET_CDN').Session::get('shop_name').'/'.$path;
    }


    public static function showIntegerOrFloat($number, $is_number_format = false)
    {
        $decimal = 0;
        if ( is_numeric($number) ) {
            if ( (int)$number == $number ) {
                $number = (int)$number;
                $decimal = 0;
            }else{
                $decimal = 2;
            }
            return ( $is_number_format ? number_format($number, $decimal) : $number );
        }
        return '';
    }

    public static function getBankImage($bank_id = null)
    {
        switch ($bank_id) {
            case 1:
                return Help::asset_cdn('images/bankok.png');
                break;
            case 2:
                return Help::asset_cdn('images/kasikorn.png');
                break;
            case 3:
                return Help::asset_cdn('images/krungthai.png');
                break;
            case 4:
                return Help::asset_cdn('images/tmb.png');
                break;
            case 5:
                return Help::asset_cdn('images/scb.png');
                break;
            case 6:
                return Help::asset_cdn('images/krungsri.png');
                break;
            case 7:
                return Help::asset_cdn('images/thanachart.png');
                break;
            case 8:
                return Help::asset_cdn('images/uob.png');
                break;
            case 9:
                return Help::asset_cdn('images/aomsin.png');
                break;
            case 10:
                return Help::asset_cdn('images/thor_or_sor.png');
                break;
            case 11:
                return Help::asset_cdn('images/islam.png');
                break;
            default:
                return Help::asset_cdn('images/AW-LOGO-01_OKSHOP.png');
                break;
        }
    }

}
