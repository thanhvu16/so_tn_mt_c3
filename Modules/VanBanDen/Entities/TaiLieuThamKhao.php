<?php

namespace Modules\VanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
class TaiLieuThamKhao extends Model
{

    protected $table = 'tai_lieu_tham_khao';

    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }

}
