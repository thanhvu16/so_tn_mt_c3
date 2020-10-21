<?php

namespace Modules\Admin\Entities;

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
}

