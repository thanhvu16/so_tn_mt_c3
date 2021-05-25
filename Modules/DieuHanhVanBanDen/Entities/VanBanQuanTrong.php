<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\VanBanDen\Entities\VanBanDen;

class VanBanQuanTrong extends Model
{
    protected $table = 'dhvbd_van_ban_quan_trong';

    protected $fillable = [
        'van_ban_den_id',
        'user_id'
    ];

    public function giayMoiDen()
    {
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')->where('loai_van_ban_id',$loaiVanBanGiayMoi->id);
    }
    public function vanBanDenDen()
    {
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')->where('loai_van_ban_id','!=',$loaiVanBanGiayMoi->id);
    }


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
