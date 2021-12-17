<?php

use App\User;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\NhomDonVi;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use app\Models\UserLogs;
use Modules\LichCongTac\Entities\DanhGiaTaiLieu;

if (!function_exists('uploadFile')) {
    function uploadFile($inputFile, $uploadPath, $folderUploads, $urlFileInDB = null)
    {
        $fileName = date('Y_m_d') . '_' . Time() . '_' . $inputFile->getClientOriginalName();
        $urlFile = $folderUploads . '/' . $fileName;

        //delete file in db and update
        if ($urlFileInDB) {
            File::delete($urlFileInDB);
        }

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        $inputFile->move($uploadPath, $fileName);

        return $urlFile;
    }
}
function strSlugFileName($title, $separator = '-', $language = 'en')
{
    return Str::slug($title, $separator, $language);
}

if (!function_exists('getUrlFile')) {
    function getUrlFile($urlFile)
    {
        if (!empty($urlFile)) {

            return asset($urlFile);
        }
    }
}

function hoTen($id)
{
    $hoTen = User::where('id',$id)->first();
    return $hoTen->ho_ten;
}
if (!function_exists('getStatusLabel')) {
    function getStatusLabel($status)
    {
        if ($status == 1) {

            return '<span class="label label-pill label-sm label-success">Hoạt động</span>';
        }

        return '<span class="label label-pill label-sm label-danger">Khóa</span>';
    }
}

if (!function_exists('canPermission')) {
    function canPermission($permission)
    {
        if (!Auth::user()->can($permission)) {
            return abort(403);
        }
    }
}

function tenNhom($idnhom)
{
    $chucvu = NhomDonVi::where('id',$idnhom)->first();
    if($chucvu)
    {
        $lay_nhom_don_vi =$chucvu->ten_nhom_don_vi;
        return $lay_nhom_don_vi;
    }
    return 0;

}
function layFilepdf($id)
{
    $file = \Modules\VanBanDen\Entities\EmailFile::where('email_id',$id)->get();

    return $file;

}

function vn_to_str ($str){

    $unicode = array(

        'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

        'd'=>'đ',

        'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

        'i'=>'í|ì|ỉ|ĩ|ị',

        'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

        'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

        'y'=>'ý|ỳ|ỷ|ỹ|ỵ',

        'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

        'D'=>'Đ',

        'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

        'I'=>'Í|Ì|Ỉ|Ĩ|Ị',

        'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

        'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

        'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

    );

    foreach($unicode as $nonUnicode=>$uni){

        $str = preg_replace("/($uni)/i", $nonUnicode, $str);

    }
    $str = str_replace(' ',' ',$str);

    return $str;

}

function tongSoVanBanSo($id)
{
    $donVi = DonVi::where('id',$id)->first();
    if( $donVi->dieu_hanh == 1)
    {
        $vanBanDen = VanBanDen::where('don_vi_id',$id)->count();
        return $vanBanDen;
    }else{
        $vanBanDen = DonViChuTri::where('don_vi_id',$id)->distinct()->count();
        return $vanBanDen;
    }

    return 0;
}
function vanBanDaGiaiQuyetTrongHan($id)
{

}



function api_add($arr ,$url)
{
    $arr=  json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $arr,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ))

    );
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;




}function api_list($url)
{
   // $arr=  json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ))

    );
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;




}

 function DonViUpTaiLieu($id)
{
    $donvi = DonVi::where(['id' => $id])
        ->whereNull('deleted_at')
        ->first();

    return $donvi;
}
function layDanhGia($data,$lct)
{
    $qlch = DanhGiaTaiLieu::where(['id_phong'=>$data,'id_lich_ct'=>$lct])->first();
    return $qlch;
}
function dateFromBusinessDays($days, $dateTime=null) {
    $dateTime = is_null($dateTime) ? time() : strtotime(str_replace('/', '-', $dateTime));
    $_day = 0;
    $_direction = $days == 0 ? 0 : intval($days/abs($days));
    $_day_value = (60 * 60 * 24);
    while($_day !== $days) {
        $dateTime += $_direction * $_day_value;
        $_day_w = date("w", $dateTime);
        if ($_day_w > 0 && $_day_w < 6) {
            $_day += $_direction * 1;
        }
    }
    return date('Y-m-d',$dateTime);
}
function dateformat($format)
{
    $ngay = date('d-m-Y', strtotime($format)) ;
    return $ngay;
}

if (!function_exists('cutStr')) {

    function cutStr($str)
    {
        $rest = substr($str, 0, 22);
        $newStr = str_replace($rest, '', $str);

        return $newStr;
    }
}

// format 11/04/2021 to 2021-04-11
function formatYMD($date)
{
    if (!empty($date)) {
        return \DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }
}


// format 2021-04-11 to 11/04/2021
function formatDMY($date)
{
    return date('d/m/Y', strtotime($date));
}
