<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;

class VanBanQuanTrong extends Model
{
    protected $table = 'dhvbd_van_ban_quan_trong';

    protected $fillable = [
        'van_ban_den_id',
        'user_id'
    ];
}
