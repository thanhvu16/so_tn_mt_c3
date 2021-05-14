<?php

namespace Modules\CongViecDonVi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use auth;

class CongViecDonViPhoiHop extends Model
{
    protected $table = 'cong_viec_don_vi_phoi_hop';

    protected $fillable = [
        'cong_viec_don_vi_id',
        'chuyen_nhan_cong_viec_don_vi_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'don_vi_id',
        'noi_dung',
        'type',
        'status'
    ];

    const STATUS_GIAI_QUYET = 1;
    const TYPE_XEM_DE_BIET = 1;

    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }

    public function congViecDonVi()
    {
        return $this->belongsTo(CongViecDonVi::class, 'cong_viec_don_vi_id', 'id');
    }

    public static function savechuyenVienPhoiHop($arrChuyenVien, $congViecDonViId, $chuyenNhanCongViecDonViId, $donViId)
    {

        $vanBanLanhDaoPhoiHop = CongViecDonViPhoiHop::where('cong_viec_don_vi_id', $congViecDonViId)
            ->where('don_vi_id', $donViId)
            ->whereNull('type')
            ->whereNull('status')
            ->delete();

        if (count($arrChuyenVien) > 0) {
            foreach ($arrChuyenVien as $chuyenVienId) {

                $nguoiDung = User::where(['id' => $chuyenVienId, 'trang_thai' => User::TRANG_THAI_HOAT_DONG])->first();

                $noiDung = 'Chuyển chuyên viên '. $nguoiDung->ho_ten .' phối hợp thực hiện';

                $chuyenVien = new CongViecDonViPhoiHop();
                $chuyenVien->cong_viec_don_vi_id = $congViecDonViId;
                $chuyenVien->chuyen_nhan_cong_viec_don_vi_id = $chuyenNhanCongViecDonViId;
                $chuyenVien->don_vi_id = $donViId;
                $chuyenVien->can_bo_chuyen_id = auth::user()->id;
                $chuyenVien->can_bo_nhan_id = $chuyenVienId;
                $chuyenVien->noi_dung = $noiDung;
                $chuyenVien->save();
            }
        }
    }

    public static function saveCanBoXemDeBiet($canBoId, $congViecDonViId, $chuyenNhanCongViecDonViId)
    {
        $checkcanBoXemDeBiet = CongViecDonViPhoiHop::where('cong_viec_don_vi_id', $congViecDonViId)
            ->where('type', self::TYPE_XEM_DE_BIET)->delete();

        if (count($canBoId) > 0) {

            foreach ($canBoId as $canBoNhanId) {

                $canBoXemDeBiet = new CongViecDonViPhoiHop();
                $canBoXemDeBiet->cong_viec_don_vi_id = $congViecDonViId;
                $canBoXemDeBiet->chuyen_nhan_cong_viec_don_vi_id = $chuyenNhanCongViecDonViId;
                $canBoXemDeBiet->don_vi_id = auth::user()->donvi_id;
                $canBoXemDeBiet->can_bo_chuyen_id = auth::user()->id;
                $canBoXemDeBiet->can_bo_nhan_id = $canBoNhanId;
                $canBoXemDeBiet->type = self::TYPE_XEM_DE_BIET;
                $canBoXemDeBiet->save();
            }

        }
    }
}
