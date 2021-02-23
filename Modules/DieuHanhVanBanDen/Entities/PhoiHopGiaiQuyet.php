<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PhoiHopGiaiQuyet extends Model
{
    protected $table = 'dhvbd_phoi_hop_giai_quyet';

    protected $fillable = [
        'van_ban_den_id',
        'noi_dung',
        'status',
        'don_vi_id',
        'parent_don_vi_id',
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
        return $this->hasMany(PhoiHopGiaiQuyetFile::class, 'phoi_hop_giai_quyet_id', 'id');
    }
}
