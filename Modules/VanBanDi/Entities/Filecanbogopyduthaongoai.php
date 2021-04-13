<?php


namespace Modules\VanBanDi\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filecanbogopyduthaongoai extends Model
{

    protected $table = 'dtvb_file_gop_y_du_thao_ngoai';



    protected $fillable = [
        'duong_dan',
        'duoi_file',
        'can_bo_gop_y',
        'Du_thao_id'

    ];
    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }


}
