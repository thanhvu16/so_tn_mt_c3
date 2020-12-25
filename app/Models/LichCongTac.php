<?php

namespace App\Models;

use App\User;
use Modules\CongViecDonVi\Entities\CongViecDonVi;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Illuminate\Database\Eloquent\Model;
use Modules\VanBanDi\Entities\VanBanDi;
use Auth;

class LichCongTac extends Model
{
    protected $table = 'dhvbd_lich_cong_tac';

    protected $fillable = [
        'object_id',
        'type',
        'lanh_dao_id',
        'ngay',
        'gio',
        'tuan',
        'noi_dung',
        'don_vi_id',
        'buoi',
        'dia_diem',
        'trang_thai_lich',
        'ghi_chu',
        'user_id',
        'don_vi_du_hop'
    ];

    const TYPE_VB_DI = 1;
    const TYPE_NHAP_TRUC_TIEP = 2;
    const DON_VI_DU_HOP = 1;

    public function vanBanDen()
    {
        return $this->hasOne(VanBanDen::class, 'id', 'object_id');
    }

    public function lanhDao()
    {
        return $this->belongsTo(User::class, 'lanh_dao_id', 'id');
    }

    public static function checkLanhDaoDuHop($lanhDaoId)
    {
        return User::where('id', $lanhDaoId)->where('trang_thai', ACTIVE)->whereNull('deleted_at')->first();
    }

    public function chuanBiTruocCuocHop()
    {
        $xuLyVanBanDen = XuLyVanBanDen::where('van_ban_den_id', $this->object_id)
            ->where('can_bo_chuyen_id', $this->lanh_dao_id)->first();

        return $xuLyVanBanDen->id ?? null;
    }

    public function donViChuTri()
    {
        return DonViChuTri::where('van_ban_den_id', $this->object_id)->get();
    }

    public function vanBanDi()
    {
        return $this->belongsTo(VanBanDi::class, 'object_id', 'id');
    }

    public function congViecDonVi()
    {
        return $this->hasOne(CongViecDonVi::class, 'lich_cong_tac_id', 'id');
    }

    public function taoLichCongTac($vanBanDi)
    {
        $tuan = date('W', strtotime($vanBanDi->ngay_hop));

        $lanhDaoDuHop = $this->checkLanhDaoDuHop($vanBanDi->nguoi_ky);
        $noiDungMoiHop = $vanBanDi->noi_dung_hop ?? null;

        if (!empty($lanhDaoDuHop) && empty($vanBanDi->noi_dung_hop)) {

            $noiDungMoiHop = 'Kính mời ' . $lanhDaoDuHop->chucVu->ten_chuc_vu . ' ' . $lanhDaoDuHop->ho_ten . ' dự họp';
        }

        $dataLichCongTac = array(
            'van_ban_den_don_vi_id' => $vanBanDi->id,
            'lanh_dao_id' => $lanhDaoDuHop->id,
            'ngay' => $vanBanDi->ngay_hop,
            'gio' => $vanBanDi->gio_hop,
            'tuan' => $tuan,
            'buoi' => ($vanBanDi->gio_hop <= '12:00') ? 1 : 2,
            'noi_dung' => $noiDungMoiHop,
            'user_id' => auth::user()->id,
            'type' => LichCongTac::TYPE_VB_DI
        );
        //check lich cong tac
        $lichCongTac = LichCongTac::where('object_id', $vanBanDi->id)
            ->where('type', LichCongTac::TYPE_VB_DI)
            ->first();

        if (empty($lichCongTac)) {
            $lichCongTac = new LichCongTac();
        }
        $lichCongTac->fill($dataLichCongTac);
        $lichCongTac->save();
    }

    public static function taoLichHopVanBanDen($vanBanDenId, $lanhDaoDuHopId, $donViDuHop, $donViChuTriId, $chuyenTuDonVi = null)
    {
        $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
        $currentUser = auth::user();
        $lanhDaoId = $lanhDaoDuHopId;

        $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];
        $nguoiDung = null;

        if (!empty($donViChuTriId)) {
            $nguoiDung = User::where('trang_thai', ACTIVE)
                ->where('don_vi_id', $donViChuTriId)
                ->whereHas('roles', function ($query) use ($roles) {
                    return $query->whereIn('name', $roles);
                })
                ->orderBy('id', 'DESC')
                ->whereNull('deleted_at')->first();
        }

        $tuan = date('W', strtotime($vanBanDen->ngay_hop_chinh));

        $lanhDaoDuHop = LichCongTac::checkLanhDaoDuHop($lanhDaoDuHopId);
        $noiDungMoiHop = null;

        if (!empty($lanhDaoDuHop)) {

            $noiDungMoiHop = 'Kính mời ' . $lanhDaoDuHop->chucVu->ten_chuc_vu . ' ' . $lanhDaoDuHop->ho_ten . ' dự họp';
        }

        // don vi du hop
        if (empty($chuyenTuDonVi) && !empty($donViDuHop) && $donViDuHop == VanBanDen::DON_VI_DU_HOP) {
            $lanhDaoId = $nguoiDung->id ?? null;
        }

        $dataLichCongTac = array(
            'object_id' => $vanBanDen->id,
            'lanh_dao_id' => $lanhDaoId,
            'ngay' => $vanBanDen->ngay_hop,
            'gio' => $vanBanDen->gio_hop,
            'tuan' => $tuan,
            'buoi' => ($vanBanDen->gio_hop <= '12:00') ? 1 : 2,
            'noi_dung' => !empty($vanBanDen->noi_dung_hop) ? $vanBanDen->noi_dung_hop : $vanBanDen->trich_yeu,
            'dia_diem' => !empty($vanBanDen->dia_diem) ? $vanBanDen->dia_diem : null,
            'user_id' => $currentUser->id,
            'don_vi_du_hop' =>  !empty($donViDuHop) ? $donViChuTriId : null
        );
        //check lich cong tac
        $lichCongTac = LichCongTac::where('object_id', $vanBanDenId)->whereNull('type')->first();
        if (empty($lichCongTac)) {
            $lichCongTac = new LichCongTac();
        }
        $lichCongTac->fill($dataLichCongTac);
        $lichCongTac->save();
    }
}
