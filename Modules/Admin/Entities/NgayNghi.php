<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class NgayNghi extends Model
{
    protected $table = 'ngay_nghi';

    protected $fillable = [
        'ten_ngay_nghi',
        'mo_ta',
        'ngay_nghi',
        'thu_tu',
        'trang_thai'
    ];


}

