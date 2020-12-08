<?php

namespace Modules\HoSoCongViec\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDi\Entities\VanBanDi;

class DetailHoSoCV extends Model
{
    protected $table = 'hscv_detail_ho_so';
    public function vanbandi()
    {
        return $this->belongsTo(VanBanDi::class,'id_van_ban');
    }
    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class,'id_van_ban');
    }
}
