<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonViOld extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'c_donvi';
    protected $primaryKey = 'id_donvi';


}

