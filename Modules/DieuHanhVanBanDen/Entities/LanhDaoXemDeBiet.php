<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\Common\AllPermission;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\VanBanDen\Entities\VanBanDen;

class LanhDaoXemDeBiet extends Model
{
    protected $table = 'dhvbd_lanh_dao_xem_de_biet';

    protected $fillable = [
        'van_ban_den_id',
        'lanh_dao_id',
        'don_vi_id',
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

    public static function saveLanhDaoXemDeBiet($arrLanhDaoId, $vanBanDenId, $type=null)
    {
        $currentUser = auth::user();
        $donViId = $currentUser->don_vi_id;

        if ($currentUser->can(AllPermission::thamMuu())) {
            $donViId = $currentUser->donVi->parent_id;
        }

        if (!empty($arrLanhDaoId) && count($arrLanhDaoId) > 0) {
            foreach ($arrLanhDaoId as $lanhDaoId) {
                $lanhDaoPhoiHop = new LanhDaoXemDeBiet();
                $lanhDaoPhoiHop->van_ban_den_id = $vanBanDenId;
                $lanhDaoPhoiHop->lanh_dao_id = $lanhDaoId;
                $lanhDaoPhoiHop->user_id = $currentUser->id;
                $lanhDaoPhoiHop->don_vi_id = !empty($type) ? $donViId : null;
                $lanhDaoPhoiHop->save();
            }
        }
    }

}
