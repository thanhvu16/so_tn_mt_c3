<?php

namespace Modules\CongViecDonVi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GiaiQuyetCongViecDonVi extends Model
{
    protected $table = 'giai_quyet_cong_viec_don_vi';

    protected $fillable = [
        'chuyen_nhan_cong_viec_don_vi_id',
        'cong_viec_don_vi_id',
        'don_vi_id',
        'noi_dung',
        'noi_dung_nhan_xet',
        'lanh_dao_duyet_id',
        'status',
        'user_id'
    ];

    CONST STATUS_DA_DUYET = 1;
    CONST STATUS_TRA_LAI = 2;


    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function giaiQuyetCongViecDonViFile()
    {
        return $this->hasMany(GiaiQuyetCongViecDonViFile::class, 'giai_quyet_cong_viec_don_vi_id', 'id');
    }

    public function canBoDuyet()
    {
        return $this->belongsTo(User::class, 'lanh_dao_duyet_id', 'id');
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 2:
                return '<span class="label label-danger">Trả lại</span>';
                break;

            case 1:
                return '<span class="label label-success">Đã duyệt</span>';
                break;

            default:
                return '<span class="label label-warning">Chờ duyệt</span>';
                break;
        }
    }

    public function congViecDonVi()
    {
        return $this->belongsTo(CongViecDonVi::class, 'cong_viec_don_vi_id', 'id');
    }

    public function chuyenNhanCongViecDonVi()
    {
        return $this->belongsTo(ChuyenNhanCongViecDonVi::class, 'chuyen_nhan_cong_viec_don_vi_id', 'id');
    }
}
