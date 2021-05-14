<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoKhan extends Model
{
    use SoftDeletes;

    protected $table = 'do_khan_cap';
    protected $fillable = [
        'ten_muc_do',
        'mo_ta'


    ];
}

