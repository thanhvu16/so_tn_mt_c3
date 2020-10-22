<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoMat extends Model
{
    use SoftDeletes;

    protected $table = 'do_bao_mat';
    protected $fillable = [
        'ten_muc_do',
        'mo_ta'


    ];
}

