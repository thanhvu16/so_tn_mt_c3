<?php

namespace Modules\VanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
class FileVanBanDen extends Model
{
    use SoftDeletes;
    protected $table = 'vbd_file_van_ban_den';

    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }

}
