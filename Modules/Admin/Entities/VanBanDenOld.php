<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VanBanDenOld extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'vb_vanbanden';
    protected $primaryKey = 'ID_VanBanDen';

    public function donViChuTri()
    {
        return $this->belongsTo(GiaoXuLyold::class, 'ID_VanBanDen', 'id_van_ban')->where('xu_ly_chinh',1);
    }
    public function donViPhoiHop()
    {
        return $this->belongsTo(GiaoXuLyold::class, 'ID_VanBanDen', 'id_van_ban')->where('xu_ly_chinh',null);
    }

}

