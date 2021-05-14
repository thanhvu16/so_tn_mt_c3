<?php

namespace Modules\VanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
class TieuChuanVanBan extends Model
{
    use SoftDeletes;
    protected $table = 'han_van_ban';



}
