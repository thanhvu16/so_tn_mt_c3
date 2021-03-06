<?php

namespace Modules\LichCongTac\Entities;

use App\Common\AllPermission;
use App\Models\LichCongTac;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\DonVi;
use Auth;

class ThanhPhanDuHop extends Model
{
    protected $table = 'lct_thanh_phan_du_hop';

    protected $fillable = [
        'lich_cong_tac_id',
        'don_vi_id',
        'user_id',
        'object_id',
        'type',
        'noi_dung',
        'trang_thai',
        'thanh_phan',
        'thanh_phan_moi',
        'chat_luong',
        'nhan_xet',
        'trang_thai_lich'
    ];
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    const TRANG_THAI_LICH_DA_CHUYEN = 2;
    const THANH_PHAN_MOI_CUA_DON_VI = 2;
    const CHAT_LUONG_KHONG_DAT = 2;
    const CHAT_LUONG_DAT = 2;
    const TYPE_VB_NHAP_TRUC_TIEP = 2;
    const TYPE_VB_DI = 1;
    const TRANG_THAI_BAN = 2;
    const TRANG_THAI_DI_HOP = 1;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
                ->select('id', 'ho_ten');;
    }

    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'id')
            ->select('id', 'ten_don_vi');
    }



    public static function store($giayMoi, $vanBan, $arrayLanhDaoId, $type = null, $donViId = null)
    {
        $donVi = auth::user()->donVi;
        if (!empty($giayMoi) && $giayMoi->id == $vanBan->loai_van_ban_id) {

            $lichCongTac = LichCongTac::where('object_id', $vanBan->id)
                ->where(function ($query) use ($type) {
                    if (!empty($type)) {
                        return $query->where('type', $type);
                    } else {
                        return $query->whereNull('type');
                    }
                })
                ->select('id', 'lanh_dao_id')
                ->first();

            if (!empty($lichCongTac)) {

                $check = ThanhPhanDuHop::where('user_id', auth::user()->id)
                    ->where('object_id', $vanBan->id)
                    ->where('lich_cong_tac_id', $lichCongTac->id)
                    ->where(function ($query) use ($type) {
                        if (!empty($type)) {
                            return $query->where('type', $type);
                        } else {
                            return $query->whereNull('type');
                        }
                    })
                    ->orderBy('created_at', 'DESC')
                    ->first();

                if (empty($donVi->cap_xa) && (auth::user()->can(AllPermission::thamMuu()) || auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]))) {
                    ThanhPhanDuHop::where('lich_cong_tac_id', $lichCongTac->id)
                        ->where('object_id', $vanBan->id)
                        ->where(function ($query) use ($type) {
                            if (!empty($type)) {
                                return $query->where('type', $type);
                            } else {
                                return $query->whereNull('type');
                            }
                        })
                        ->where(function ($query) use ($donViId) {
                            if (!empty($donViId)) {
                                return $query->where('don_vi_id', $donViId);
                            }
                        })
                        ->delete();
                }

                if ($check) {
                    ThanhPhanDuHop::where('lich_cong_tac_id', $lichCongTac->id)
                        ->where('object_id', $vanBan->id)
                        ->where(function ($query) use ($type) {
                            if (!empty($type)) {
                                return $query->where('type', $type);
                            } else {
                                return $query->whereNull('type');
                            }
                        })
                        ->where(function ($query) use ($donViId) {
                            if (!empty($donViId)) {
                                return $query->where('don_vi_id', $donViId);
                            }
                        })
                        ->where('id', '>', $check->id)
                        ->delete();
                }

                foreach ($arrayLanhDaoId as $lanhDaoId) {
                    if (!empty($lanhDaoId)) {
                        $thanhPhanDuHop = new ThanhPhanDuHop();
                        $thanhPhanDuHop->lich_cong_tac_id = $lichCongTac->id;
                        $thanhPhanDuHop->lanh_dao_id = $lichCongTac->lanh_dao_id == $lanhDaoId ? $lichCongTac->lanh_dao_id : null;
                        $thanhPhanDuHop->user_id = $lanhDaoId;
                        $thanhPhanDuHop->object_id = $vanBan->id;
                        $thanhPhanDuHop->don_vi_id = $donViId ?? null;
                        $thanhPhanDuHop->nguoi_tao_id = auth::user()->id;
                        $thanhPhanDuHop->save();
                    }
                }
            }

        }

    }


}
