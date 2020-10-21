<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChucVu extends Model
{
    use SoftDeletes;

    protected $table = 'chuc_vu';
    protected $fillable = [
        'ten_chuc_vu',
        'ten_viet_tat'


    ];
}

