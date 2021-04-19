<?php

namespace Modules\CongViecDonVi\Entities;

use App\Models\LichCongTac;
use App\User;
use Illuminate\Database\Eloquent\Model;
use auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class CongViecDeXuat extends Model
{
    use SoftDeletes;
    protected $table = 'cong_viec_chuyen_vien_de_xuat';


    public function truongPhong()
    {
        return $this->belongsTo(User::class, 'truong_phong', 'id');
    }
    public function chuyenvien()
    {
        return $this->belongsTo(User::class, 'nguoi_gui', 'id');
    }

    public function file()
    {
        return $this->hasMany(CongViecDeXuatFile::class, 'cong_viec_id', 'id');
    }


}
