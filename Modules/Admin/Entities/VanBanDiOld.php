<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VanBanDiOld extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'vb_vbdi2';
    protected $primaryKey = 'vbdi_id';


}

