<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Auth;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
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
        'don_vi_co_dieu_hanh',
        'vao_so_van_ban',
        'type',
        'chuyen_tiep',
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

                if ($donVi->cap_xa == DonVi::CAP_XA) {
                    // chu tich cap xa nhan van ban
                    $role = [CHU_TICH];
                    $nguoiDung = User::where('trang_thai', ACTIVE)
                        ->where('don_vi_id', $donViId)
                        ->whereHas('roles', function ($query) use ($role) {
                            return $query->whereIn('name', $role);
                        })
                        ->orderBy('id', 'DESC')
                        ->whereNull('deleted_at')->first();

                } else {
                    $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];
                    $nguoiDung = User::where('trang_thai', ACTIVE)
                        ->where('don_vi_id', $donViId)
                        ->whereHas('roles', function ($query) use ($roles) {
                            return $query->whereIn('name', $roles);
                        })
                        ->orderBy('id', 'DESC')
                        ->whereNull('deleted_at')->first();
                }

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

                // save thành phần dự họp
                $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
                $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
                ThanhPhanDuHop::store($giayMoi, $vanBanDen, [$nguoiDung->id], null, $nguoiDung->don_vi_id ?? null);
            }
        }
    }

    public static function saveDonViPhoiHop($vanBanDenId)
    {
        $role = [TRUONG_PHONG, CHANH_VAN_PHONG];
        $nguoiDung = User::where('don_vi_id', auth::user()->don_vi_id)
            ->whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->first();

        $donVi = auth::user()->donVi;

        if ($donVi->cap_xa == DonVi::CAP_XA) {
            $nguoiDung = User::role(CHU_TICH)
                ->where('don_vi_id', auth::user()->don_vi_id)
                ->where('trang_thai', ACTIVE)
                ->whereNull('deleted_at')->first();
        }

        $dataLuuDonViPhoiHop = [
            'van_ban_den_id' => $vanBanDenId,
            'can_bo_chuyen_id'=> auth::user()->id,
            'can_bo_nhan_id'=> $nguoiDung->id ?? null,
            'don_vi_id'=> $donVi->id,
            'noi_dung'=> 'Chuyển đơn vị phối hợp: '. $donVi->ten_don_vi,
            'don_vi_co_dieu_hanh'=> $donVi->dieu_hanh ?? null,
            'vao_so_van_ban' =>  1,
            'type' => 1,
            'user_id' => auth::user()->id
        ];

        $donViPhoiHop = new DonViPhoiHop();
        $donViPhoiHop->fill($dataLuuDonViPhoiHop);
        $donViPhoiHop->save();

        // luu vet van ban den
        $luuVetVanBanDen = new LogXuLyVanBanDen();
        $luuVetVanBanDen->fill($dataLuuDonViPhoiHop);
        $luuVetVanBanDen->save();
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
