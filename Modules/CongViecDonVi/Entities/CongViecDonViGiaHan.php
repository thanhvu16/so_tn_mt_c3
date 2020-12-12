<?php

namespace Modules\CongViecDonVi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CongViecDonViGiaHan extends Model
{
    protected $table = 'cong_viec_don_vi_gia_han';

    protected $fillable = [
        'chuyen_nhan_cong_viec_don_vi_id',
        'cong_viec_don_vi_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'don_vi_id',
        'noi_dung',
        'thoi_han_de_xuat',
        'han_cu',
        'status'
    ];

    const STATUS_CHO_DUYET = 1;
    const STATUS_TRA_LAI = 2;
    const STATUS_DA_DUYET = 3;

    public function congViecDonVi()
    {
        return $this->belongsTo(CongViecDonVi::class, 'cong_viec_don_vi_id', 'id');
    }

    public function chuyenNhanCongViecDonVi()
    {
        return $this->belongsTo(ChuyenNhanCongViecDonVi::class, 'chuyen_nhan_cong_viec_don_vi_id', 'id');
    }

    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 1:
                return '<span class="label label-warning">Chờ duyệt</span>';
                break;
            case 2:
                return '<span class="label label-danger">Trả lại</span>';
                break;

            case 3:
                return '<span class="label label-success">Đã duyệt</span>';
                break;
        }
    }
}
