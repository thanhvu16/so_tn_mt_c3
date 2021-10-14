<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\Common\AllPermission;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\VanBanDen\Entities\VanBanDen;

class LanhDaoChiDao extends Model
{
    protected $table = 'dhvb_lanh_dao_chi_dao';

    protected $fillable = [
        'van_ban_den_id',
        'lanh_dao_id',
        'y_kien',
        'trang_thai',
        'user_id'
    ];
    public function lanhDao()
    {
        return $this->belongsTo(User::class, 'lanh_dao_id', 'id');
    }
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
    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
    }

    public static function saveLanhDaoChiDao($arrLanhDaoId, $vanBanDenId, $type=null)
    {
        $currentUser = auth::user();
        if (!empty($arrLanhDaoId) && count($arrLanhDaoId) > 0) {
            foreach ($arrLanhDaoId as $lanhDaoId) {
                $lanhDaoPhoiHop = new LanhDaoChiDao();
                $lanhDaoPhoiHop->van_ban_den_id = $vanBanDenId;
                $lanhDaoPhoiHop->lanh_dao_id = $lanhDaoId;
                $lanhDaoPhoiHop->user_id = $currentUser->id;
                $lanhDaoPhoiHop->save();
            }
        }
    }
    public static function saveGiamDocChiDao($LanhDaoId, $vanBanDenId, $type=null)
    {
        $currentUser = auth::user();
        if ($LanhDaoId) {
                $lanhDaoPhoiHop = new LanhDaoChiDao();
                $lanhDaoPhoiHop->van_ban_den_id = $vanBanDenId;
                $lanhDaoPhoiHop->lanh_dao_id = $LanhDaoId;
                $lanhDaoPhoiHop->user_id = $currentUser->id;
                $lanhDaoPhoiHop->save();
        }
    }

    public function vanBanDenID()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')->select('id', 'don_vi_id', 'van_ban_den_id');
    }

}
