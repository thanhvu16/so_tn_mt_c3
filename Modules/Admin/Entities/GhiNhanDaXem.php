<?php

namespace Modules\Admin\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GhiNhanDaXem extends Model
{

    protected $table = 'ghi_nhan_da_xem';


    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }
}

