<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\VanBanDen\Entities\VanBanDen;

class ChuyenVienPhoiHop extends Model
{
    protected $table = 'dhvbd_chuyen_vien_phoi_hop';

    protected $fillable = [
        'van_ban_den_id',
        'don_vi_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'noi_dung',
        'status'
    ];

    const CHUYEN_VIEN_GIAI_QUYET = 1;

    public function giayMoiDen()
    {
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')->where('loai_van_ban_id',$loaiVanBanGiayMoi->id);
    }
    public function vanBanDenDen()
    {
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')->where('loai_van_ban_id','!=',$loaiVanBanGiayMoi->id);
    }

    public static function savechuyenVienPhoiHop($arrChuyenVien, $vanBanDenDonViId, $donViId)
    {

        if (!empty($arrChuyenVien) && count($arrChuyenVien) > 0) {
            foreach ($arrChuyenVien as $chuyenVienId) {

                $nguoiDung = User::where(['id' => $chuyenVienId, 'trang_thai' => ACTIVE])->first();

                $noiDung = 'Chuyển chuyên viên '. $nguoiDung->ho_ten .' phối hợp thực hiện';

                $chuyenVien = new ChuyenVienPhoiHop();
                $chuyenVien->van_ban_den_id = $vanBanDenDonViId;
                $chuyenVien->don_vi_id = $donViId;
                $chuyenVien->can_bo_chuyen_id = auth::user()->id;
                $chuyenVien->can_bo_nhan_id = $chuyenVienId;
                $chuyenVien->noi_dung = $noiDung;
                $chuyenVien->save();
            }
        }
    }
}
