<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;
use Auth;

class VanBanQuanTrong extends Model
{
    protected $table = 'dhvbd_van_ban_quan_trong';

    protected $fillable = [
        'van_ban_den_id',
        'user_id'
    ];


    public static function saveVanBanQuanTrong($vanBanDenId, $checkVanBanQuanTrong)
    {
        VanBanQuanTrong::where([
            'user_id' => auth::user()->id,
            'van_ban_den_id' => $vanBanDenId
        ])->delete();

        if (isset($checkVanBanQuanTrong) && !empty($checkVanBanQuanTrong)) {
            $dataVanBanQuanTrong = [
                'van_ban_den_id' => $vanBanDenId,
                'user_id' => auth::user()->id
            ];

            $vanBanQuanTrong = VanBanQuanTrong::where([
                'user_id' => auth::user()->id,
                'van_ban_den_id' => $vanBanDenId
            ])->first();

            if (empty($vanBanQuanTrong)) {
                $vanBanQuanTrong = new VanBanQuanTrong();
                $vanBanQuanTrong->fill($dataVanBanQuanTrong);
                $vanBanQuanTrong->save();
            }
        }
    }
}
