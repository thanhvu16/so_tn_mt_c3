<?php

namespace Modules\DanhGiaCanBo\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Lanhdaodanhgia extends Model
{
    protected $table = 'dgcb_lanh_dao_duyet';
    public function nguoidung()
    {
        return $this->belongsTo(User::class, 'ca_nhan', 'id');
    }
}
