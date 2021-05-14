<?php

namespace Modules\VanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class VanBanDenDonVi extends Model
{
    use SoftDeletes;
    public function __construct()
    {
        $table = 'van_ban_den_' . auth::user()->don_vi_id;

        $this->setTable($table);
    }

    protected $fillable = [];
}
