<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhomDonVi extends Model
{
    use SoftDeletes;

    protected $table = 'nhom_don_vi';

}

