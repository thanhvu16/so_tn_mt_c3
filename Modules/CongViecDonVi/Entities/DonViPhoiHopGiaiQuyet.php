<?php

namespace Modules\CongViecDonVi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DonViPhoiHopGiaiQuyet extends Model
{
    protected $table = 'cong_viec_don_vi_phoi_hop_giai_quyet';

    protected $fillable = [
        'cong_viec_don_vi_id',
        'chuyen_nhan_cong_viec_don_vi_id',
        'noi_dung',
        'status',
        'don_vi_id',
        'user_id'
    ];

    const GIAI_QUYET_CHUYEN_VIEN_PHOI_HOP = 2;
    const GIAI_QUYET_DON_VI_PHOI_HOP = 1;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function phoiHopGiaiQuyetFile()
    {
        return $this->hasMany(DonViPhoiHopGiaiQuyetFile::class, 'cong_viec_don_vi_phoi_hop_giai_quyet_id', 'id');
    }
}
