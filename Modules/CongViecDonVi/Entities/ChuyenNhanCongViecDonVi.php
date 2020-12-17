<?php

namespace Modules\CongViecDonVi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\DonVi;
use auth;

class ChuyenNhanCongViecDonVi extends Model
{
    protected $table = 'chuyen_nhan_cong_viec_don_vi';

    protected $fillable = [
        'cong_viec_don_vi_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'parent_id',
        'don_vi_id',
        'noi_dung',
        'noi_dung_chuyen',
        'type',
        'chuyen_tiep',
        'han_xu_ly',
        'hoan_thanh'
    ];

    const TYPE_DV_PHOI_HOP = 1;
    const HOAN_THANH_CONG_VIEC = 1;
    const CHUYEN_TIEP = 1;
    const GIAI_QUYET = 2;

    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }

    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'ma_id');
    }

    public function congViecDonVi()
    {
        return $this->belongsTo(CongViecDonVi::class, 'cong_viec_don_vi_id', 'id');
    }

    public static function saveDonViPhoiHop($donViPhoiHopId, $congViecDonViId, $dauViec)
    {
        $donViphoiHop = ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $congViecDonViId)
            ->where('type', self::TYPE_DV_PHOI_HOP)
            ->whereNull('hoan_thanh')
            ->delete();

        if (!empty($donViPhoiHopId) && count($donViPhoiHopId) > 0) {
            foreach ($donViPhoiHopId as $donViId) {
                $canBoNhanId = null;
                $donVi = Donvi::where('id', $donViId)
                    ->whereNull('deleted_at')
                    ->first();
                if ($donVi) {

                    $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];

                    $nguoiDung = User::where('don_vi_id', $donVi->id)
                        ->whereHas('roles', function ($query) use ($roles) {
                            return $query->whereIn('name', $roles);
                        })
                        ->whereNull('deleted_at')
                        ->first();
                    $canBoNhanId = $nguoiDung->id ?? null;
                }

                $donViphoiHop = new ChuyenNhanCongViecDonVi();
                $donViphoiHop->cong_viec_don_vi_id = $congViecDonViId;
                $donViphoiHop->can_bo_nhan_id = $canBoNhanId;
                $donViphoiHop->noi_dung = $dauViec;
                $donViphoiHop->can_bo_chuyen_id = auth::user()->id;
                $donViphoiHop->don_vi_id = $donViId;
                $donViphoiHop->type = self::TYPE_DV_PHOI_HOP;
                $donViphoiHop->save();
            }
        }

    }

    public function checkCanBoNhan($canBoNhanId) {

        return ChuyenNhanCongViecDonVi::whereIn('can_bo_nhan_id', $canBoNhanId)
            ->where('don_vi_id', $this->don_vi_id)
            ->where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->first();
    }

    public function checkUpdateChuyenNhanCongViec()
    {
        return ChuyenNhanCongViecDonVi::where('don_vi_id', $this->don_vi_id)
            ->where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->where('parent_id', $this->id)
            ->whereNull('chuyen_tiep')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function checkChuyenVienPhoiHop()
    {
        return CongViecDonViPhoiHop::where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->whereNull('type')
            ->get();
    }

    public function checklanhdaoXemDeBiet()
    {
        return CongViecDonViPhoiHop::where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->where('type', CongViecDonViPhoiHop::TYPE_XEM_DE_BIET)
            ->get();
    }

    public function getTrinhTuXuLy()
    {
        return ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->where('don_vi_id',  $this->don_vi_id)
            ->get();
    }

    public function giaHanVanBanChoDuyet($userId)
    {
        return CongViecDonViGiaHan::where('chuyen_nhan_cong_viec_don_vi_id', $this->id)
            ->where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->where('can_bo_chuyen_id', $userId)
            ->where('status', CongViecDonViGiaHan::STATUS_CHO_DUYET)
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function giaHanVanBanTraLai($userId)
    {
        return CongViecDonViGiaHan::where('chuyen_nhan_cong_viec_don_vi_id', $this->id)
            ->where('can_bo_nhan_id', $userId)->where('status', CongViecDonViGiaHan::STATUS_TRA_LAI)->first();
    }

    public function giaHanVanBanDaDuyet($userId)
    {
        return CongViecDonViGiaHan::where('chuyen_nhan_cong_viec_don_vi_id', $this->id)
            ->where('can_bo_chuyen_id', $userId)->where('status', CongViecDonViGiaHan::STATUS_DA_DUYET)->first();
    }

    public function giaHanCongViec()
    {
        return $this->hasMany(CongViecDonViGiaHan::class, 'cong_viec_don_vi_id', 'cong_viec_don_vi_id')
            ->where('don_vi_id', auth::user()->don_vi_id);
    }

    public function giaHanCongViecByDonVi($donViId)
    {
        return CongViecDonViGiaHan::where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->where('don_vi_id', $donViId)
            ->get();
    }

    public function giaiQuyetCongViec()
    {
        return $this->hasMany(GiaiQuyetCongViecDonVi::class, 'cong_viec_don_vi_id', 'cong_viec_don_vi_id')
            ->where('don_vi_id', $this->don_vi_id);
    }

    public function giaiQuyetCongViecTraLai()
    {
        return GiaiQuyetCongViecDonVi::where('chuyen_nhan_cong_viec_don_vi_id', $this->id)
            ->where('user_id', auth::user()->id)
            ->where('status', GiaiQuyetCongViecDonVi::STATUS_TRA_LAI)
            ->orderBy('id', 'DESC')->first();
    }

    public function giaiQuyetCongViecHoanThanh()
    {
        return GiaiQuyetCongViecDonVi::where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function chuyenVienphoiHopGiaiQuyet()
    {
        return  CongViecDonViPhoiHop::where([
            'cong_viec_don_vi_id' => $this->cong_viec_don_vi_id,
            'can_bo_nhan_id' => auth::user()->id,
            'don_vi_id' => auth::user()->don_vi_id,
            'status' => CongViecDonViPhoiHop::STATUS_GIAI_QUYET
        ])->first();
    }

    public function chuyenVienPhoiHop()
    {
        return DonViPhoiHopGiaiQuyet::where([
            'cong_viec_don_vi_id' => $this->cong_viec_don_vi_id,
            'don_vi_id' => $this->don_vi_id,
        ])
            ->where('status', DonViPhoiHopGiaiQuyet::GIAI_QUYET_CHUYEN_VIEN_PHOI_HOP)
            ->get();
    }

    public function getCaNhanPhoiHop()
    {
        return DonViPhoiHopGiaiQuyet::where('chuyen_nhan_cong_viec_don_vi_id', $this->id)
            ->where('user_id', auth::user()->id)
            ->where('status', DonViPhoiHopGiaiQuyet::GIAI_QUYET_CHUYEN_VIEN_PHOI_HOP)
            ->first();
    }

    public function chuyenVienDonViPhoiHopGiaiQuyet() {

        return DonViPhoiHopGiaiQuyet::where('chuyen_nhan_cong_viec_don_vi_id', $this->id)
            ->where('user_id', auth::user()->id)
            ->where('status', DonViPhoiHopGiaiQuyet::GIAI_QUYET_DON_VI_PHOI_HOP)
            ->first();
    }

    public function donViPhoiHopDaGiaiQuyet() {

        return DonViPhoiHopGiaiQuyet::where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->where('don_vi_id', auth::user()->don_vi_id)
            ->where('status', DonViPhoiHopGiaiQuyet::GIAI_QUYET_DON_VI_PHOI_HOP)
            ->first();
    }

    public function getPhoiHopDaGiaiQuyet($donViId) {

        return DonViPhoiHopGiaiQuyet::where('cong_viec_don_vi_id', $this->cong_viec_don_vi_id)
            ->where(function ($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('don_vi_id', $donViId);
                }
            })
            ->where('status', DonViPhoiHopGiaiQuyet::GIAI_QUYET_DON_VI_PHOI_HOP)
            ->get();
    }
}
