<?php

namespace Modules\VanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VanBanDen extends Model
{
    protected $table = 'van_ban_den';


    protected $fillable = [];
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_tao', 'id');
    }
    public function vanBanDenFile()
    {
        return $this->hasMany(FileVanBanDen::class, 'vb_den_id', 'id')->whereNull('deleted_at');
    }
}
