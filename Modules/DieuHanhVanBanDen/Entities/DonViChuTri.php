<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\Common\AllPermission;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
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
        'parent_don_vi_id',
        'noi_dung',
        'han_xu_ly_cu',
        'han_xu_ly_moi',
        'da_chuyen_xuong_don_vi',
        'don_vi_co_dieu_hanh',
        'vao_so_van_ban',
        'chuyen_tiep',
        'hoan_thanh',
        'van_ban_quan_trong',
        'da_tham_muu',
        'van_thu_nhan'
    ];

    const HOAN_THANH_VB = 1;
    const CHUYEN_TIEP = 1;
    const GIAI_QUYET = 2;
    const TYPE_NHAP_TU_VAN_THU_DON_VI = 1;
    const VB_DA_CHUYEN_XUONG_DON_VI = 1;
    const DON_VI_CO_DIEU_HANH = 1;
    const DA_THAM_MUU = 1;
    const TRA_LAI = 1;

    public function canBoChuyen()
    {
        return $this->belongsTo(User::class, 'can_bo_chuyen_id', 'id')->select('id', 'ho_ten', 'chuc_vu_id', 'don_vi_id');
    }

    public function canBoNhan()
    {
        return $this->belongsTo(User::class, 'can_bo_nhan_id', 'id')->select('id', 'ho_ten', 'chuc_vu_id', 'don_vi_id');;
    }

    public function vanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id');
    }
    public function searchVanBanDen()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')
            ->where('loai_van_ban_id', '!=',100)
            ->where('type', 1)
            ->whereNull('deleted_at')
            ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->select('id', 'don_vi_id', 'van_ban_den_id');
    }
    public function searchVanBanDenChuaGiaiQuyetQuaHan()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')
            ->where('loai_van_ban_id', '!=',100)
            ->where('type', 1)
            ->whereNull('deleted_at')
            ->where(function ($query) {
                return $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
                    ->orWhereNull('trinh_tu_nhan_van_ban');
            })
            ->select('id', 'don_vi_id', 'van_ban_den_id');
    }
    public function searchVanBanDenChuaGiaiQuyetTrongHan()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')
            ->where('loai_van_ban_id', '!=',100)
            ->where('type', 1)
            ->whereNull('deleted_at')
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('trinh_tu_nhan_van_ban', '>', VanBanDen::CHU_TICH_NHAN_VB)
            ->select('id', 'don_vi_id', 'van_ban_den_id');
    }

    public function searchVanBanDenHoanThanhDungHan()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')
            ->where('loai_van_ban_id', '!=',100)
            ->where('type', 1)
            ->whereNull('deleted_at')
            ->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN)
            ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->select('id', 'don_vi_id', 'van_ban_den_id');
    }

    public function searchVanBanDenChuaHoanThanh()
    {
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')
            ->where('loai_van_ban_id', '!=',100)
            ->where('type', 1)
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('trinh_tu_nhan_van_ban', '>', VanBanDen::CHU_TICH_NHAN_VB)
            ->select('id', 'don_vi_id', 'van_ban_den_id')
            ->whereNull('deleted_at');
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
        $tenDonVi = $donVi->ten_don_vi;
        $donViId = $donVi->id;
        $dieuHanh = $donVi->dieu_hanh;
        $daThamMuu = DonViChuTri::DA_THAM_MUU;

        if (auth::user()->hasRole([VAN_THU_DON_VI])) {
            $nguoiDung = User::role(CHU_TICH)
                ->where('don_vi_id', auth::user()->donVi->parent_id)
                ->where('trang_thai', ACTIVE)
                ->whereNull('deleted_at')->first();

            $thamMuuChiCuc = User::permission(AllPermission::thamMuu())
                ->whereHas('donVi', function ($query) {
                    return $query->where('parent_id', auth::user()->donVi->parent_id);
                })->orderBy('id', 'DESC')->first();

            if ($thamMuuChiCuc) {
                $nguoiDung = $thamMuuChiCuc;
                $daThamMuu = null;
            }

            $parentDonVi = DonVi::where('id', $donVi->parent_id)->first();
            $tenDonVi = $parentDonVi->ten_don_vi ?? $donVi->ten_don_vi;
            $donViId = $parentDonVi->id ?? $donVi->id;
            $dieuHanh = $parentDonVi->dieu_hanh ?? $donVi->dieu_hanh;
        }

        $dataLuuDonViChuTri = [
            'van_ban_den_id' => $vanBanDenId,
            'can_bo_chuyen_id' => auth::user()->id,
            'can_bo_nhan_id' => $nguoiDung->id ?? null,
            'noi_dung' => 'Chuyển đơn vị chủ trì: '. $tenDonVi,
            'don_vi_id' => $donViId,
            'user_id' => auth::user()->id,
            'don_vi_co_dieu_hanh' => $dieuHanh,
            'vao_so_van_ban' =>  1,
            'type' => DonViChuTri::TYPE_NHAP_TU_VAN_THU_DON_VI,
            'da_tham_muu' => $daThamMuu
        ];

        $donViChuTri = new DonViChuTri();
        $donViChuTri->fill($dataLuuDonViChuTri);
        $donViChuTri->save();
    }

    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'id');
    }



    public static function guiSMSchuTri($vanBanDenId, $danhSachDonViChuTriIds)
    {
        $donVi = DonVi::where('id', $danhSachDonViChuTriIds[$vanBanDenId])->first();
        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
            $roles = [CHU_TICH];
            $nguoiDung = User::where('trang_thai', ACTIVE)
                ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                ->whereHas('roles', function ($query) use ($roles) {
                    return $query->whereIn('name', $roles);
                })
                ->select('id', 'don_vi_id','so_dien_thoai')
                ->orderBy('id', 'asc')
                ->whereNull('deleted_at')->first();
        } else {
            // luu don vi chu tri
            $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];


            $nguoiDung = User::where('trang_thai', ACTIVE)
                ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                ->whereHas('roles', function ($query) use ($roles) {
                    return $query->whereIn('name', $roles);
                })
                ->select('id', 'don_vi_id','so_dien_thoai')
                ->orderBy('id', 'asc')
                ->whereNull('deleted_at')->first();


        }
        $vanBanDenTY = VanBanDen::where('id',$vanBanDenId)->first();
        $noidungtn = $vanBanDenTY->so_den.','.$vanBanDenTY->trich_yeu.'. Thoi gian:'.$vanBanDenTY->gio_hop.', ngày:'.formatDMY($vanBanDenTY->ngay_hop).', Tại:'.$vanBanDenTY->dia_diem;
        $conVertTY = vn_to_str($noidungtn);
        $sdtmany = [];
        array_push($sdtmany, '84934440299');
        array_push($sdtmany, '84383574229');
        array_push($sdtmany, $nguoiDung ->so_dien_thoai);

        if ($nguoiDung ->so_dien_thoai != null) {
            foreach ($sdtmany as $sdt) {
                $arayOffice = array();
                $arayOffice['RQST']['name'] = 'send_sms_list';
                $arayOffice['RQST']['REQID'] = "1234352";
                $arayOffice['RQST']['LABELID'] = "149355";
                $arayOffice['RQST']['CONTRACTTYPEID'] = '1';
                $arayOffice['RQST']['CONTRACTID'] = '13681';
                $arayOffice['RQST']['TEMPLATEID'] = '791767';
                $arayOffice['RQST']['PARAMS'][0] = array(
                    'NUM' => '1',
                    'CONTENT' => $conVertTY
                );
                $arayOffice['RQST']['SCHEDULETIME'] = '';
                $arayOffice['RQST']['MOBILELIST'] = $sdt;
                $arayOffice['RQST']['ISTELCOSUB'] = '0';
                $arayOffice['RQST']['AGENTID'] = '244';
                $arayOffice['RQST']['APIUSER'] = 'SOTNMT_HN';
                $arayOffice['RQST']['APIPASS'] = 'aBc123@';
                $arayOffice['RQST']['USERNAME'] = 'SOTNMT_HN';
                $arayOffice['RQST']['DATACODING'] = '0';

                $data = json_encode($arayOffice);
                $url = 'http://113.185.0.35:8888/smsbn/api';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arayOffice));
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json'
                ]);
                $response = curl_exec($curl);
                curl_close($curl);
            }
        }


    }

    public static function luuDonViXuLyVanBan($vanBanDenId, $textDonViChuTri, $danhSachDonViChuTriIds, $chuyenVanBanXuongDonVi,$vbquantrong)
    {

        $donVi = DonVi::where('id', $danhSachDonViChuTriIds[$vanBanDenId])->first();
        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
            // chu tich cap xa nhan van ban
            $role = [CHU_TICH];

            $nguoiDung = User::where('trang_thai', ACTIVE)
                ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                ->whereHas('roles', function ($query) use ($role) {
                    return $query->whereIn('name', $role);
                })
                ->select('id', 'don_vi_id')
                ->orderBy('id', 'DESC')
                ->whereNull('deleted_at')->first();


//            if (auth::user()->hasRole(PHO_CHU_TICH)) {
//                $vanBanDen = VanBanDen::find($vanBanDenId);
//                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
//                $vanBanDen->save();
//            }

        } else {
            // luu don vi chu tri
            $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];
            if ($donVi->id == 10084 || $donVi->id == 10085) {
                $nguoiDung = User::where('trang_thai', ACTIVE)
                    ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                    ->whereHas('roles', function ($query) use ($roles) {
                        return $query->whereIn('name', $roles);
                    })
                    ->select('id', 'don_vi_id')
                    ->orderBy('thu_tu_tp', 'desc')
                    ->whereNull('deleted_at')->first();
            }else{
                $nguoiDung = User::where('trang_thai', ACTIVE)
                    ->where('don_vi_id', $danhSachDonViChuTriIds[$vanBanDenId])
                    ->whereHas('roles', function ($query) use ($roles) {
                        return $query->whereIn('name', $roles);
                    })
                    ->select('id', 'don_vi_id')
                    ->orderBy('id', 'asc')
                    ->whereNull('deleted_at')->first();
            }




        }

        $dataLuuDonViChuTri = [
            'van_ban_den_id' => $vanBanDenId,
            'can_bo_chuyen_id' => auth::user()->id,
            'can_bo_nhan_id' => $nguoiDung->id ?? null,
            'noi_dung' => $textDonViChuTri[$vanBanDenId],
            'don_vi_id' => $danhSachDonViChuTriIds[$vanBanDenId],
            'user_id' => auth::user()->id,
            'don_vi_co_dieu_hanh' => $donVi->dieu_hanh ?? null,
            'vao_so_van_ban' => !empty($donVi) && $donVi->dieu_hanh == 0 ? 1 : null,
            'van_ban_quan_trong' => $vbquantrong ?? null,
            'da_chuyen_xuong_don_vi' => $chuyenVanBanXuongDonVi
        ];

        $donViChuTri = new DonViChuTri();
        $donViChuTri->fill($dataLuuDonViChuTri);
        $donViChuTri->save();

        // luu vet van ban den
        $luuVetVanBanDen = new LogXuLyVanBanDen();
        $luuVetVanBanDen->fill($dataLuuDonViChuTri);
        $luuVetVanBanDen->save();

        // save thành phần dự họp
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
        ThanhPhanDuHop::store($giayMoi, $vanBanDen, [$nguoiDung->id ?? null], null, $nguoiDung->don_vi_id ?? null);
    }

    public static function luuDonViCapXa($donViId, $txtDonViChuTri,  $vanBanDenId, $donViChuTri, $hanXuLyCu, $hanXuLyMoi)
    {
        // luu don vi chu tri
        $donVi = DonVi::where('id', $donViId)->select('id', 'ten_don_vi')->first();
//        $role = [TRUONG_BAN, TRUONG_PHONG];
        if($donVi->cap_chi_nhanh == 1)
        {
            $role = [TRUONG_BAN, TRUONG_PHONG];
        }else{
            $role = [VAN_THU_DON_VI];
        }
        $nguoiDung = User::where('don_vi_id', $donViId)
            ->whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->first();


        $dataLuuDonViChuTri = [
            'van_ban_den_id' => $vanBanDenId,
            'can_bo_chuyen_id' => auth::user()->id,
            'can_bo_nhan_id' => $nguoiDung->id ?? null,
            'don_vi_id' => $donViId,
            'parent_id' => $donViChuTri ? $donViChuTri->id : null,
            'noi_dung' => $txtDonViChuTri,
            'don_vi_co_dieu_hanh' => isset($donViChuTri) ? $donViChuTri->don_vi_co_dieu_hanh : null,
            'vao_so_van_ban' => $donViChuTri->vao_so_van_ban ?? null,
            'han_xu_ly_cu' => $hanXuLyCu ?? null,
            'han_xu_ly_moi' => isset($hanXuLyMoi) ? $hanXuLyMoi : $donViChuTri->han_xu_ly_moi ?? null,
            'da_chuyen_xuong_don_vi' => $donViChuTri->da_chuyen_xuong_don_vi ?? null,
            'user_id' => auth::user()->id,
//            'parent_don_vi_id' => auth::user()->can(AllPermission::thamMuu()) ? auth::user()->donVi->parent_id : auth::user()->don_vi_id,
            'parent_don_vi_id' =>  auth::user()->don_vi_id,
            'van_thu_nhan' =>  $role == [VAN_THU_DON_VI] ? 1 : '' ,
        ];


        $donViChuTri = new DonViChuTri();
        $donViChuTri->fill($dataLuuDonViChuTri);
        $donViChuTri->save();

        LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataLuuDonViChuTri);
    }

    public static function LuuDonViKhongDieuHanh($vanBanDenId, $donViId)
    {
        $role = [TRUONG_PHONG, CHANH_VAN_PHONG];
        $nguoiDung = User::where('don_vi_id', $donViId)
            ->whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->first();

        $donVi = DonVi::where('id', $donViId)->first();
        $tenDonVi = $donVi->ten_don_vi;


        $dataLuuDonViChuTri = [
            'van_ban_den_id' => $vanBanDenId,
            'can_bo_chuyen_id' => auth::user()->id,
            'can_bo_nhan_id' => $nguoiDung->id ?? null,
            'noi_dung' => 'Chuyển đơn vị thực hiện: '. $tenDonVi,
            'don_vi_id' => $donViId,
            'user_id' => auth::user()->id,
            'don_vi_co_dieu_hanh' => $donVi->dieu_hanh,
            'vao_so_van_ban' =>  1,
            'type' => DonViChuTri::TYPE_NHAP_TU_VAN_THU_DON_VI
        ];

        $donViChuTri = new DonViChuTri();
        $donViChuTri->fill($dataLuuDonViChuTri);
        $donViChuTri->save();
    }
}
