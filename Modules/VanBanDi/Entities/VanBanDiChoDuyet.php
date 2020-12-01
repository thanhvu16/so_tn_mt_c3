<?php

namespace Modules\VanBanDi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;


class VanBanDiChoDuyet extends Model
{

    protected $table = 'van_ban_di_cho_duyet';
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'can_bo_id', 'id');
    }
    public function nguoitralai()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }
    public function canbochuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }
    public function canbonhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }

    public function vanbandi()
    {
        return $this->belongsTo(VanBanDi::class, 'van_ban_di_id', 'id');
    }
    public function vanbanditrinhky()
    {
        return $this->hasMany(VanBanDiChoDuyet::class, 'van_ban_di_id', 'van_ban_di_id');
    }
    public function file()
    {
        return $this->hasMany(FileVanBanDi::class, 'vanbandi_id', 'van_ban_di_id')->where('trangthai', 1);

    }


}
