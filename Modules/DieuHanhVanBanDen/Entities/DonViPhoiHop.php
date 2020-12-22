<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Auth;
use Modules\VanBanDen\Entities\VanBanDen;

class DonViPhoiHop extends Model
{
    protected $table = 'dhvbd_don_vi_phoi_hop';

    protected $fillable = [
        'van_ban_den_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'don_vi_id',
        'parent_id',
        'noi_dung',
        'chuyen_tiep',
        'don_vi_co_dieu_hanh',
        'vao_so_van_ban',
        'hoan_thanh'
    ];

    const HOAN_THANH_VB = 1;
    const CHUYEN_TIEP = 1;
    const GIAI_QUYET = 2;

    public static function luuDonViPhoiHop($arrDonViId, $vanBanDenId)
    {

        if (!empty($arrDonViId) && count($arrDonViId) > 0) {
                DonViPhoiHop::where([
                    'van_ban_den_id' => $vanBanDenId,
                    'chuyen_tiep'  => null,
                    'hoan_thanh'  => null
                ])->delete();

            foreach ($arrDonViId as $donViId) {
                $donVi = DonVi::where('id', $donViId)->whereNull('deleted_at')->first();

                $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];
                $nguoiDung = User::where('trang_thai', ACTIVE)
                    ->where('don_vi_id', $donViId)
                    ->whereHas('roles', function ($query) use ($roles) {
                        return $query->whereIn('name', $roles);
                    })
                    ->whereNull('deleted_at')->first();

                $noiDung = !empty($donVi) ? 'Chuyển đơn vị phối hợp: '.$donVi->ten_don_vi : Null;
                $donViPhoiHop  = new DonViPhoiHop();
                $donViPhoiHop->van_ban_den_id = $vanBanDenId;
                $donViPhoiHop->can_bo_chuyen_id = auth::user()->id;
                $donViPhoiHop->can_bo_nhan_id = $nguoiDung->id ?? null;
                $donViPhoiHop->noi_dung = $noiDung;
                $donViPhoiHop->don_vi_id = $donViId;
                $donViPhoiHop->don_vi_co_dieu_hanh = $donVi->dieu_hanh ?? null;
                $donViPhoiHop->vao_so_van_ban = !empty($donVi) && $donVi->dieu_hanh == 0 ? 1 : null;
                $donViPhoiHop->save();
            }
        }
    }

    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'id');
    }

    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id');
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id');
    }

    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
    }
}
