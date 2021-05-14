<?php

namespace Modules\LichCongTac\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class FileCuocHop extends Model
{
    protected $table = 'qlch_file_tai_lieu';
    protected $fillable = [];
    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_tao', 'id');
    }

}
