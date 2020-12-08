<?php

namespace Modules\DanhGiaCanBo\Entities;

use Illuminate\Database\Eloquent\Model;

class ChuyenNoiVu extends Model
{
    protected $table = 'dgcb_chuyen_noi_vu';



    public function laytenphong()
    {
        return $this->belongsTo(DonVi::class, 'phong', 'ma_id');
    }
    public function laytencanbogui()
    {
        return $this->belongsTo(NguoiDung::class, 'can_bo_gui', 'id');
    }
}
