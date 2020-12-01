<?php

namespace Modules\VanBanDi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDen\Entities\VanBanDen;

class VanBanDi extends Model
{

    use SoftDeletes;
    protected $table = 'van_ban_di';

    protected $fillable = [

    ];

    const VAN_BAN_DU_THAO = 1;
    const DUYET_DU_THAO = 1;


    public function nguoidung2()
    {
        return $this->belongsTo(User::class,'nguoi_ky');
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
         return $this->belongsTo(DonVi::class,'donvisoanthao_id');
    }
    public function vanBanDiFile()
    {
        return $this->hasMany(FileVanBanDi::class, 'van_ban_di_id', 'id');
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

    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
    }

    public static function duThaoVanBanChoDuyet($user, $danhSachDonVi)
    {
        return VanBanDi::whereIn('donvisoanthao_id',
            $danhSachDonVi->pluck('ma_id'))
            ->where('van_ban_du_thao', VanBanDi::VAN_BAN_DU_THAO)
            ->where('user_id', $user->id)
            ->whereNull('status');
    }

    public function vanBanDenDonVi() {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_don_vi_id', 'id');
    }

    public function vanBanDiFileDaKy()
    {
        return $this->hasMany(FileVanBanDi::class, 'vanbandi_id', 'id')
            ->where('trangthai', FileVanBanDi::TRANG_THAI_FILE_TRINH_KY)
            ->where('loai_file', FileVanBanDi::LOAI_FILE_DA_KY)
            ->whereNull('trang_thai_gui');
    }

    public function checkSoVanBanDi()
    {
        if ($this->sovanban_id == 2) {

            return 'Văn phòng UBND thành phố Hà Nội';
        } else{

            return 'Uỷ ban nhân dân thành phố Hà Nội';
        }
    }

    public function vanBanDiFilePdfDaKy()
    {
        return $this->hasMany(FileVanBanDi::class, 'vanbandi_id', 'id')
            ->where('trangthai', FileVanBanDi::TRANG_THAI_FILE_TRINH_KY)
            ->where('loai_file', FileVanBanDi::LOAI_FILE_DA_KY);
    }


}
