<?php

namespace Modules\Admin\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonVi extends Model
{
    use SoftDeletes;

    protected $table = 'don_vi';
    protected $fillable = [
        'ten_don_vi',
        'ten_viet_tat',
        'ma_hanh_chinh',
        'dia_chi',
        'so_dien_thoai',
        'email',
        'dieu_hanh'

    ];

    const DIEU_HANH = 1;
    const CAP_XA = 1;
    const NO_PARENT_ID = 0;
    const TRANG_THAI_HOAT_DONG = 1;

    public function nhomDonVi()
    {
        return $this->belongsTo(NhomDonVi::class, 'nhom_don_vi', 'id');
    }

    public function getParent()
    {
        return $this->belongsTo(DonVi::class, 'parent_id', 'id')->select('id', 'ten_don_vi');
    }
}

