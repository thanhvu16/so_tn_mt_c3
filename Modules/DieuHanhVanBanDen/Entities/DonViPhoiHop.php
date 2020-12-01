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
        'hoan_thanh'
    ];


    public static function luuDonViPhoiHop($arrDonViId, $vanBanDenId)
    {

        $chucVuTP = ChucVu::where('ten_chuc_vu', 'like', 'trưởng phòng')->first();

        if (!empty($arrDonViId) && count($arrDonViId) > 0) {
                DonViPhoiHop::where([
                    'van_ban_den_id' => $vanBanDenId,
                    'chuyen_tiep'  => null,
                    'hoan_thanh'  => null
                ])->delete();

            foreach ($arrDonViId as $donViId) {
                $donVi = DonVi::where('id', $donViId)->whereNull('deleted_at')->first();

                $nguoiDung = User::where('don_vi_id', $donViId)
                    ->where('trang_thai', ACTIVE)
                    ->where(function ($query) use ($chucVuTP) {
                        if (!empty($chucVuTP)) {
                            return $query->where('chuc_vu_id', $chucVuTP->id);
                        }
                    })
                    ->whereNull('deleted_at')->first();

                $noiDung = !empty($donVi) ? 'Chuyển đơn vị phối hợp: '.$donVi->ten_don_vi : Null;
                $donViPhoiHop  = new DonViPhoiHop();
                $donViPhoiHop->van_ban_den_id = $vanBanDenId;
                $donViPhoiHop->can_bo_chuyen_id = auth::user()->id;
                $donViPhoiHop->don_vi_id = $donViId;
                $donViPhoiHop->noi_dung = $noiDung;
                $donViPhoiHop->can_bo_nhan_id = $nguoiDung->id ?? null;
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
