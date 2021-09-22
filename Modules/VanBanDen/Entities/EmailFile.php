<?php

namespace Modules\VanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
class EmailFile extends Model
{

    protected $table = 'file_pdf_hom_thu_cong';

    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }

}
