<?php

namespace Modules\DanhGiaCanBo\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\DonVi;

class DuyetDanhGia extends Model
{
    protected $table = 'dgcb_chuyen_cap_tren';
    public function laychitietdanhgia()
    {
        return $this->belongsTo(UbndDanhGiaChiTiet::class, 'danh_gia_id', 'id');
    }
    public function laynhanxettruongphong()
    {
        return $this->belongsTo(DuyetDanhGia::class,'id_dau_tien','id_dau_tien')->where('cap_danh_gia',2);
    }
    public function laynhanxetphophong()
    {
        return $this->belongsTo(DuyetDanhGia::class,'id_dau_tien','id_dau_tien')->where('cap_danh_gia',3);
    }
    public function laynhanxetcanhan()
    {
        return $this->belongsTo(DuyetDanhGia::class,'id_dau_tien','id_dau_tien')->where('cap_danh_gia',1);
    }

    public function laydanhgia($id)
    {

        return DuyetDanhGia::where('id', $id)->first();
    }
    public function laydanhgiacuoi($id)
    {

        return DuyetDanhGia::where('id_dau_tien', $id)->orderBy('created_at','desc')->first();
    }

    public function nguoidung()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen', 'id');
    }
    public function nguoinhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan', 'id');
    }
    public function canbodanhgia()
    {
        return $this->belongsTo(User::class, 'can_bo_goc', 'id');
    }

    public function donvi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'ma_id');
    }
    public function laytendonvi($id)
    {
        $donvi = Donvi::where('ma_id', $id)->first();
        $tendonvi = $donvi->ten_don_vi;
        return $tendonvi;
    }
    public function nguoidung2($id)
    {
        $duyetdanhgia = DuyetDanhGia::where('id_dau_tien', $id)->orderBy('created_at','asc')->first();
        $nguoidung = User::where('id', $duyetdanhgia->can_bo_chuyen)->first();
        $hoten = $nguoidung->ho_ten;
        return $hoten;
    }
    public function ngaydanhgia($id)
    {
        $duyetdanhgia = DuyetDanhGia::where('id_dau_tien', $id)->orderBy('created_at','asc')->first();
        $ngaydanhgia = $duyetdanhgia->created_at;
        return $ngaydanhgia;
    }
    public function ngaydanhgiacuoi($id)
    {
        $duyetdanhgia = DuyetDanhGia::where('id_dau_tien', $id)->orderBy('created_at','desc')->first();
        $ngaydanhgia = $duyetdanhgia->created_at;
        return $ngaydanhgia;
    }
    public function nguoidung3($id)
    {
        $duyetdanhgia = DuyetDanhGia::where('id_dau_tien', $id)->orderBy('created_at','desc')->first();
        $nguoidung = User::where('id', $duyetdanhgia->can_bo_chuyen)->first();
        $hoten = $nguoidung->ho_ten;
        return $hoten;
    }
    public function layphophongdanhgia($id,$canbonhan)
    {
        $laydanhgiaphophong = DuyetDanhGia::where(['id_dau_tien'=> $id,'can_bo_chuyen'=>$canbonhan])->first();
        return $laydanhgiaphophong;
    }
    public function layhotenphophongdanhgia($id,$canbonhan)
    {
        $laydanhgiaphophong = DuyetDanhGia::where(['id_dau_tien'=> $id,'can_bo_chuyen'=>$canbonhan])->first();
        $nguoidung = User::where('id',$laydanhgiaphophong->can_bo_nhan)->first();
        $hoten= $nguoidung->ho_ten;

        return $hoten;
    }

    public function nguoidung5($id)
    {
        $duyetdanhgia = DuyetDanhGia::where('id_dau_tien', $id)->orderBy('created_at','asc')->first();
        $nguoidung = User::where('id', $duyetdanhgia->can_bo_chuyen)->first();
        $hoten = $nguoidung->id;
        return $hoten;
    }
}
