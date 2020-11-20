<?php

namespace Modules\VanBanDi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Duthaovanbandi extends Model
{

    protected $table = 'dtvb_du_thao_van_ban_di';
    use SoftDeletes;


    protected $fillable = [
        'loai_van_ban_id',
        'so_ky_hieu',
        'vb_trich_yeu',
        'nguoi_ky',
        'chuc_vu',
        'so_trang',
        'nguoi_tao',
        'y_kien'

    ];
    public function Duthaofile()
    {
        return $this->hasMany(Fileduthao::class, 'vb_du_thao_id', 'id')->where('stt','!=',0);
    }
    public function canbotrongphong()
    {
        return $this->hasMany(CanBoPhongDuThao::class,'du_thao_vb_id','id');
    }
    public function canbophongkhac()
    {
        return $this->hasMany(CanBoPhongDuThaoKhac::class,'du_thao_vb_id','id');
    }
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_tao', 'id');
    }
    public function vanbandi()
    {
        return $this->belongsTo(VanBanDi::class, 'van_ban_di_id', 'id');
    }
    public function loaivanban(){
        return $this->belongsTo(LoaiVanBan::class,'loai_van_ban_id');
    }
    public function nguoidung2()
    {
        return $this->belongsTo(NguoiDung::class,'nguoi_ky');
    }
    public function caclanduthao()
    {
        return $this->hasMany(Duthaovanbandi::class,'du_thao_id','du_thao_id');
    }
    public function getChucVu($id)
    {

        $nguoidung = NguoiDung::where('id',$id)->first();
        $chuc_vu = $nguoidung->chuc_vu_id;
        $ds_chucvu = ChucVu::where('ma_id',$chuc_vu)->first();
        $ten_chuc_vu = $ds_chucvu->ten_chuc_vu;


        return $ten_chuc_vu;
    }


}
