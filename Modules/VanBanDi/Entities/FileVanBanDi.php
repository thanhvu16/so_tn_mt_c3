<?php

namespace Modules\VanBanDi\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileVanBanDi extends Model
{
    protected $table = 'file_van_ban_di';


    protected $fillable = [
        'ten_file',
        'duong_dan',
        'duoi_file',
        'vb_du_thao_id',
        'don_vi',
        'nguoi_tao',
        'stt'

    ];
    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }


}

