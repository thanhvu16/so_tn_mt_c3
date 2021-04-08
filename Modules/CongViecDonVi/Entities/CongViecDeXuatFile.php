<?php

namespace Modules\CongViecDonVi\Entities;

use App\Models\LichCongTac;
use App\User;
use Illuminate\Database\Eloquent\Model;
use auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class CongViecDeXuatFile extends Model
{
    protected $table = 'cong_viec_chuyen_vien_de_xuat_file';
    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }

}
