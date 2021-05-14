<?php

namespace Modules\LichCongTac\Entities;

use App\User;
use auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NguoiThamDu extends Model
{
    protected $table = 'qlch_nguoi_tham_du';
    protected $fillable = [];
    use SoftDeletes;

    public static function taoNguoiDuHop($vanBanDenId, $lanhDaoDuHopId)
    {
        $xoabanghisau = NguoiThamDu::where(['van_ban_id'=> $vanBanDenId,'user_id'=>auth::user()->id])->first();
        if(!empty($xoabanghisau))
        {
            $xoa= NguoiThamDu::where(['van_ban_id'=> $vanBanDenId])->where('id','>',$xoabanghisau->id)->delete();
        }
        $nguoiThamDu = new NguoiThamDu();
        $nguoiThamDu->van_ban_id = $vanBanDenId;
        $nguoiThamDu->user_id = $lanhDaoDuHopId;
        //người được up tài liệu
        $nguoiThamDu->trang_thai = 1;
        $nguoiThamDu->save();

    }
    public static function nguoiDuHop($vanBanDenId, $lanhDaoDuHopId)
    {
        $xoabanghisau = NguoiThamDu::where(['van_ban_id'=> $vanBanDenId,'user_id'=>auth::user()->id])->first();

        if(!empty($xoabanghisau))
        {
           NguoiThamDu::where(['van_ban_id'=> $vanBanDenId])->where('id','>',$xoabanghisau->id)->delete();
        }

        $nguoiThamDu = new NguoiThamDu();
        $nguoiThamDu->van_ban_id = $vanBanDenId;
        $nguoiThamDu->user_id = $lanhDaoDuHopId;
        $nguoiThamDu->save();

    }

    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
