<?php

namespace Modules\VanBanDi\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\MailNgoaiThanhPho;

class NoiNhanVanBanDi extends Model
{
    protected $table = 'don_vi_nhan_van_ban_di';
    public function laytendonvinhan()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id_nhan', 'id');
    }
    public function donvigui()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_gui', 'id');
    }

    public function vanbandi()
    {
        return $this->belongsTo(VanBanDi::class, 'van_ban_di_id', 'id');
    }
}
