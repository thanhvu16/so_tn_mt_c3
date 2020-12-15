<?php

namespace Modules\CongViecDonVi\Entities;

use App\Models\LichCongTac;
use Illuminate\Database\Eloquent\Model;
use auth;

class CongViecDonVi extends Model
{
    protected $table = 'cong_viec_don_vi';

    protected $fillable = [
        'noi_dung_cuoc_hop',
        'noi_dung_dau_viec',
        'lich_cong_tac_id',
        'user_id'
    ];

    public function congViecDonViFile()
    {
        return $this->hasMany(CongViecDonViFile::class, 'cong_viec_don_vi_id', 'id');
    }

    public function chuyenNhanCongViecDonVi()
    {
        return $this->hasMany(ChuyenNhanCongViecDonVi::class, 'cong_viec_don_vi_id', 'id')
            ->whereNull('type')->whereNull('parent_id');
    }

    public function giaiQuyetCongViecDonVi()
    {
        return $this->hasOne(GiaiQuyetCongViecDonVi::class, 'cong_viec_don_vi_id', 'id')
            ->where('status', GiaiQuyetCongViecDonVi::STATUS_DA_DUYET);
    }

    public function lichCongTac()
    {
        return $this->belongsTo(LichCongTac::class, 'lich_cong_tac_id', 'id');
    }

    public function ChuyenNhanCongViecDonViDangXuLy()
    {
        return $this->hasMany(ChuyenNhanCongViecDonVi::class, 'cong_viec_don_vi_id', 'id')
            ->whereNull('type')
            ->whereNull('parent_id')
            ->whereNull('hoan_thanh');
    }

    public function ChuyenNhanCongViecDonViDaXuLy()
    {
        return $this->hasMany(ChuyenNhanCongViecDonVi::class, 'cong_viec_don_vi_id', 'id')
            ->whereNull('type')
            ->whereNull('parent_id')
            ->where('hoan_thanh', ChuyenNhanCongViecDonVi::HOAN_THANH_CONG_VIEC);
    }
}
