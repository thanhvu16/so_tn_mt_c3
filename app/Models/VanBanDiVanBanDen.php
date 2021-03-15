<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class VanBanDiVanBanDen extends Model
{
    protected $table = 'van_ban_di_van_ban_den';

    protected $fillable = [
      'van_ban_di_id',
      'van_ban_den_id',
      'user_id'
    ];

    public static function saveVanBanDiVanBanDen($vanBanDiId, $vanBanDenId)
    {
        // xoa van ban di
//        VanBanDiVanBanDen::where([
//            'van_ban_di_id' => $vanBanDiId
//        ])->delete();

        $data = VanBanDiVanBanDen::where([
            'van_ban_di_id' => $vanBanDiId,
            'van_ban_den_id' => $vanBanDenId,
        ])->first();

        if (empty($data)) {
            $data = new VanBanDiVanBanDen();
        }

        $data->van_ban_di_id = $vanBanDiId;
        $data->van_ban_den_id = $vanBanDenId;
        $data->user_id = auth::user()->id;
        $data->save();

    }

}
