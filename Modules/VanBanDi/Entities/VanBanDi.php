<?php

namespace Modules\VanBanDi\Entities;

use App\Models\VanBanDiVanBanDen;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDen\Entities\VanBanDen;

class VanBanDi extends Model
{

    use SoftDeletes;
    protected $table = 'van_ban_di';
    const LOAI_VAN_BAN_GIAY_MOI = 2;
    const CHO_PHAT_HANH = 1;
    const DA_PHAT_HANH = 2;

    protected $fillable = [

    ];

    protected $casts = [
        'van_ban_den_id' => 'array'
    ];

    const VAN_BAN_DU_THAO = 1;
    const DUYET_DU_THAO = 1;
    const LOAI_VAN_BAN_DI = 1;


    public function nguoidung2()
    {
        return $this->belongsTo(User::class,'nguoi_ky');
    }
    public function donViPhatHanh()
    {
        return $this->belongsTo(DonVi::class,'phong_phat_hanh');
    }
    public function donViSoanThaoVB()
    {
        return $this->belongsTo(DonVi::class,'van_ban_huyen_ky');
    }

    public function donViSoanThaoVBC($id)
    {
        $donVi = DonVi::where('id',$id)->first();
        if($donVi)
        {
            if($donVi->parent_id == 0)
            {
                return $donVi->ten_don_vi;
            }else{
                $donVi = DonVi::where('id',$donVi->parent_id)->first();
                return $donVi->ten_don_vi;
            }
        }

    }
    public function nguoitao()
    {
        return $this->belongsTo(User::class,'nguoi_tao');
    }
    public function loaivanban(){
        return $this->belongsTo(LoaiVanBan::class,'loai_van_ban_id');
    }
    public function sovanban(){
        return $this->belongsTo(SoVanBan::class,'so_van_ban_id');
    }

    public function domat(){
        return $this->belongsTo(DoMat::class,'dobaomat_id');
    }
    public function dokhan(){
        return $this->belongsTo(DoKhan::class,'dokhan_id');
    }


    public function dvSoanThao(){
         return $this->belongsTo(DonVi::class,'don_vi_soan_thao');
    }
    public function vanBanDiFile()
    {
        return $this->hasMany(FileVanBanDi::class, 'van_ban_di_id', 'id');
    }
    public function filechinh(){
        return $this->hasMany(FileVanBanDi::class,'van_ban_di_id')->where('file_chinh_gui_di',2);
    }
    public function filephieutrinh(){
        return $this->hasMany(FileVanBanDi::class,'van_ban_di_id')->where('trang_thai',1);
    }
    public function filetrinhky(){
        return $this->hasMany(FileVanBanDi::class,'van_ban_di_id')->where('trang_thai',2);
    }
    public function filehoso(){
        return $this->hasMany(FileVanBanDi::class,'van_ban_di_id')->where('trang_thai',3);
    }


    public function mailtrongtp(){
        return $this->hasMany(NoiNhanMail::class,'van_ban_di_id')->where('status',1);
    }



    public function mailngoaitp(){
        return $this->hasMany(NoiNhanMailNgoai::class,'van_ban_di_id')->where('status',1);
    }
    public function donvinhanvbdi(){
        return $this->hasMany(NoiNhanVanBanDi::class,'van_ban_di_id');
    }





    public function loaiVanBanid()
    {
        return $this->belongsTo(LoaiVanBan::class, 'loai_van_ban_id', 'id');
    }




    public function coutLoaiVanBan( $loaiVanBanId) {

        return VanBanDi::where('loaivanban_id',$loaiVanBanId)->count();

    }

    public function canBoDuThao()
    {
        return $this->belongsTo(User::class, 'can_bo_id', 'id');
    }

    public function canBoDuyet()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

//    public function vanBanDen()
//    {
//        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
//    }

    public static function duThaoVanBanChoDuyet($user, $danhSachDonVi)
    {
        return VanBanDi::whereIn('donvisoanthao_id',
            $danhSachDonVi->pluck('ma_id'))
            ->where('van_ban_du_thao', VanBanDi::VAN_BAN_DU_THAO)
            ->where('user_id', $user->id)
            ->whereNull('status');
    }

//    public function vanBanDenDonVi() {
//        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
//    }

    public function getListVanBanDen()
    {

        $vanBanDi = VanBanDiVanBanDen::where('van_ban_di_id', $this->id)->get();
        $arrVanBanDenId = $vanBanDi->pluck('van_ban_den_id')->toArray();
        if (count($arrVanBanDenId) > 0) {

            return VanBanDen::whereIn('id', $arrVanBanDenId)->get();
        }

        return false;

    }

    public function vanBanDiFileDaKy()
    {
        return $this->hasMany(FileVanBanDi::class, 'van_ban_di_id', 'id')
            ->where('file_chinh_gui_di', FileVanBanDi::TRANG_THAI_FILE_TRINH_KY)
            ->where('loai_file', FileVanBanDi::LOAI_FILE_DA_KY)
            ->whereNull('trang_thai_gui');
    }

    public function checkSoVanBanDi()
    {
        if ($this->sovanban_id == 2) {

            return 'V??n ph??ng UBND th??nh ph??? H?? N???i';
        } else{

            return 'U??? ban nh??n d??n th??nh ph??? H?? N???i';
        }
    }

    public function vanBanDiFilePdfDaKy()
    {
        return $this->hasMany(FileVanBanDi::class, 'van_ban_di_id', 'id')
            ->where('trang_thai', FileVanBanDi::TRANG_THAI_FILE_TRINH_KY)
            ->where('loai_file', FileVanBanDi::LOAI_FILE_DA_KY);
    }

    public static function luuVanBanDiVanBanDen($vanBanDiId, $arrVanBanDen)
    {
        if (!empty($arrVanBanDen)) {
            $arrVanBanDenId = explode(',', $arrVanBanDen);

            foreach ($arrVanBanDenId as $vanBanDenId) {
                VanBanDiVanBanDen::saveVanBanDiVanBanDen($vanBanDiId, $vanBanDenId);
            }

        }
    }
}
