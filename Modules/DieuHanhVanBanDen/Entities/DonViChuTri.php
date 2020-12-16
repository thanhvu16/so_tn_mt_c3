<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\DonVi;
use Modules\VanBanDen\Entities\VanBanDen;
use Auth;

class DonViChuTri extends Model
{
    protected $table = 'dhvbd_don_vi_chu_tri';

    protected $fillable = [
        'van_ban_den_id',
        'can_bo_chuyen_id',
        'can_bo_nhan_id',
        'don_vi_id',
        'parent_id',
        'noi_dung',
        'don_vi_co_dieu_hanh',
        'vao_so_van_ban',
        'chuyen_tiep',
        'hoan_thanh'
    ];

    const HOAN_THANH_VB = 1;
    const CHUYEN_TIEP = 1;
    const GIAI_QUYET = 2;
    const TYPE_NHAP_TU_VAN_THU_DON_VI = 1;

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

    public static function saveDonViChuTri($vanBanDenId)
    {
        // luu don vi chu tri
        $role = [TRUONG_PHONG, CHANH_VAN_PHONG];
        $nguoiDung = User::where('don_vi_id', auth::user()->don_vi_id)
            ->whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->first();

        $donVi = auth::user()->donVi;

        $dataLuuDonViChuTri = [
            'van_ban_den_id' => $vanBanDenId,
            'can_bo_chuyen_id' => auth::user()->id,
            'can_bo_nhan_id' => $nguoiDung->id ?? null,
            'noi_dung' => 'Chuyển đơn vị chủ trì: '. $donVi->ten_don_vi,
            'don_vi_id' => $donVi->id,
            'user_id' => auth::user()->id,
            'don_vi_co_dieu_hanh' => $donVi->dieu_hanh ?? null,
            'vao_so_van_ban' =>  1,
            'type' => DonViChuTri::TYPE_NHAP_TU_VAN_THU_DON_VI
        ];

        $donViChuTri = new DonViChuTri();
        $donViChuTri->fill($dataLuuDonViChuTri);
        $donViChuTri->save();
    }

    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'id');
    }
}
