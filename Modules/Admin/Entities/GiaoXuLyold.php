<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\VanBanDi\Entities\VanBanDi;

class GiaoXuLyold extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'vb_giao_xuly';
    protected $primaryKey = 'Id_giao';



    public function tenDonVi()
    {
        return $this->belongsTo(DonViOld::class, 'id_don_vi', 'id_donvi');
    }

}

