<?php

namespace Modules\VanBanDi\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fileduthao extends Model
{
    use SoftDeletes;
    protected $table = 'dtvb_file_du_thao';


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

