<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VanBanDenOld extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'vb_vanbanden';
    protected $primaryKey = 'ID_VanBanDen';


}

