<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use App\Common\AllPermission;
use App\Models\LichCongTac;
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
        'parent_don_vi_id',
        'noi_dung',
        'don_vi_co_dieu_hanh',
        'vao_so_van_ban',
        'type',
        'chuyen_tiep',
        'active',
        'hoan_thanh',
        'da_tham_muu'
    ];

    const HOAN_THANH_VB = 1;
    const CHUYEN_TIEP = 1;
    const GIAI_QUYET = 2;

    const ACTIVE_VB = [
        CHU_TICH => 1,
        PHO_CHU_TICH => 2,
        TRUONG_PHONG => 3,
        PHO_PHONG => 4,
        CHUYEN_VIEN => 5
    ];

    const ACTIVE = 1;

    public static function guiSMSArray($danhSachDonVi, $vanBanDenId)
    {
        if (!empty($danhSachDonVi) && count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donViId) {

                $donVi = DonVi::where('id', $donViId)->whereNull('deleted_at')->first();

                if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                    $roles = [CHU_TICH];
                    $nguoiDung = User::where('trang_thai', ACTIVE)
                        ->where('don_vi_id', $donViId)
                        ->whereHas('roles', function ($query) use ($roles) {
                            return $query->whereIn('name', $roles);
                        })
                        ->orderBy('id', 'asc')
                        ->whereNull('deleted_at')->first();

                } else {
                    $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];
                    $nguoiDung = User::where('trang_thai', ACTIVE)
                        ->where('don_vi_id', $donViId)
                        ->whereHas('roles', function ($query) use ($roles) {
                            return $query->whereIn('name', $roles);
                        })
                        ->orderBy('id', 'asc')
                        ->whereNull('deleted_at')->first();


                }
                $vanBanDenTY = VanBanDen::where('id', $vanBanDenId)->first();
                $noidungtn = $vanBanDenTY->so_den . ',' . $vanBanDenTY->trich_yeu . '. Thoi gian:' . $vanBanDenTY->gio_hop . ', ng??y:' . formatDMY($vanBanDenTY->ngay_hop) . ', T???i:' . $vanBanDenTY->dia_diem;
                $conVertTY = vn_to_str($noidungtn);
                if ($nguoiDung->so_dien_thoai != null) {
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
                    $arayOffice['RQST']['MOBILELIST'] = $nguoiDung->so_dien_thoai;
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
    }

    public static function luuDonViPhoiHop($arrDonViId, $vanBanDenId)
    {

        if (!empty($arrDonViId) && count($arrDonViId) > 0) {
            DonViPhoiHop::where([
                'van_ban_den_id' => $vanBanDenId,
                'chuyen_tiep' => null,
                'parent_don_vi_id' => null,
                'hoan_thanh' => null
            ])->delete();
            $active = null;
            foreach ($arrDonViId as $donViId) {

                $donVi = DonVi::where('id', $donViId)->whereNull('deleted_at')->first();

                if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                    // chu tich cap xa nhan van ban
                    $role = [CHU_TICH];
                    $nguoiDung = User::where('trang_thai', ACTIVE)
                        ->where('don_vi_id', $donViId)
                        ->whereHas('roles', function ($query) use ($role) {
                            return $query->whereIn('name', $role);
                        })
                        ->orderBy('id', 'DESC')
                        ->whereNull('deleted_at')->first();

                    $active = self::ACTIVE;

                } else {
                    $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];


                    if ($donVi->id == 10084 || $donVi->id == 10085) {
                        $nguoiDung = User::where('trang_thai', ACTIVE)
                            ->where('don_vi_id', $donViId)
                            ->whereHas('roles', function ($query) use ($roles) {
                                return $query->whereIn('name', $roles);
                            })
                            ->orderBy('thu_tu_tp', 'desc')
                            ->whereNull('deleted_at')->first();

                    } else {
                        $nguoiDung = User::where('trang_thai', ACTIVE)
                            ->where('don_vi_id', $donViId)
                            ->whereHas('roles', function ($query) use ($roles) {
                                return $query->whereIn('name', $roles);
                            })
                            ->orderBy('id', 'asc')
                            ->whereNull('deleted_at')->first();
                    }

                    $active = self::ACTIVE;
                }

                $noiDung = !empty($donVi) ? 'Chuy???n ????n v??? ph???i h???p: ' . $donVi->ten_don_vi : Null;
                $donViPhoiHop = new DonViPhoiHop();
                $donViPhoiHop->van_ban_den_id = $vanBanDenId;
                $donViPhoiHop->can_bo_chuyen_id = auth::user()->id;
                $donViPhoiHop->can_bo_nhan_id = $nguoiDung->id ?? null;
                $donViPhoiHop->noi_dung = $noiDung;
                $donViPhoiHop->don_vi_id = $donViId;
                $donViPhoiHop->don_vi_co_dieu_hanh = $donVi->dieu_hanh ?? null;
                $donViPhoiHop->vao_so_van_ban = !empty($donVi) && $donVi->dieu_hanh == 0 ? 1 : null;
                $donViPhoiHop->active = $active;
                $donViPhoiHop->save();

                // save th??nh ph???n d??? h???p
                $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'gi???y m???i')->select('id')->first();
                $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
                ThanhPhanDuHop::store($giayMoi, $vanBanDen, [$nguoiDung->id ?? null], null, $nguoiDung->don_vi_id ?? null);
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
        $tenDonVi = $donVi->ten_don_vi;
        $donViId = $donVi->id;
        $dieuHanh = $donVi->dieu_hanh;
        $daThamMuu = DonViChuTri::DA_THAM_MUU;
        $active = DonViPhoiHop::ACTIVE;

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
                $active = null;
            }

            $parentDonVi = DonVi::where('id', $donVi->parent_id)->first();
            $tenDonVi = $parentDonVi->ten_don_vi ?? $donVi->ten_don_vi;
            $donViId = $parentDonVi->id ?? $donVi->id;
            $dieuHanh = $donVi->dieu_hanh;
        }

        $dataLuuDonViPhoiHop = [
            'van_ban_den_id' => $vanBanDenId,
            'can_bo_chuyen_id' => auth::user()->id,
            'can_bo_nhan_id' => $nguoiDung->id ?? null,
            'don_vi_id' => $donViId,
            'noi_dung' => 'Chuy???n ????n v??? ph???i h???p: ' . $tenDonVi,
            'don_vi_co_dieu_hanh' => $dieuHanh,
            'vao_so_van_ban' => 1,
            'type' => 1,
            'user_id' => auth::user()->id,
            'da_tham_muu' => $daThamMuu,
            'active' => $active
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

    public function giayMoiDen()
    {
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'gi???y m???i')->select('id')->first();
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
    }

    public function vanBanDenDen()
    {
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'gi???y m???i')->select('id')->first();
        return $this->belongsTo(VanBanDen::class, 'van_ban_den_id', 'id')->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
    }

    public static function luuDonViPhoiHopCapXa($arrDonViId, $vanBanDenId, $phoChuTichId)
    {
        if (!empty($arrDonViId) && count($arrDonViId) > 0) {
            DonViPhoiHop::where([
                'van_ban_den_id' => $vanBanDenId,
                'chuyen_tiep' => null,
                'hoan_thanh' => null,
                'parent_don_vi_id' => auth::user()->don_vi_id
            ])->delete();

            foreach ($arrDonViId as $donViId) {

                $donVi = DonVi::where('id', $donViId)->whereNull('deleted_at')->first();

                // Truong ban cap xa nhan van ban
                $role = [TRUONG_BAN, TRUONG_PHONG];
                $nguoiDung = User::where('trang_thai', ACTIVE)
                    ->where('don_vi_id', $donViId)
                    ->whereHas('roles', function ($query) use ($role) {
                        return $query->whereIn('name', $role);
                    })
                    ->orderBy('id', 'DESC')
                    ->whereNull('deleted_at')->first();


                $noiDung = !empty($donVi) ? 'Chuy???n ????n v??? ph???i h???p: ' . $donVi->ten_don_vi : Null;
                $donViPhoiHop = new DonViPhoiHop();
                $donViPhoiHop->van_ban_den_id = $vanBanDenId;
                $donViPhoiHop->can_bo_chuyen_id = auth::user()->id;
                $donViPhoiHop->can_bo_nhan_id = $nguoiDung->id ?? null;
                $donViPhoiHop->noi_dung = $noiDung;
                $donViPhoiHop->don_vi_id = $donViId;
                $donViPhoiHop->don_vi_co_dieu_hanh = $donVi->dieu_hanh ?? null;
                $donViPhoiHop->vao_so_van_ban = !empty($donVi) && $donVi->dieu_hanh == 0 ? 1 : null;
                $donViPhoiHop->parent_don_vi_id = auth::user()->can(AllPermission::thamMuu()) ? auth::user()->donVi->parent_id : auth::user()->don_vi_id;
                $donViPhoiHop->active = empty($phoChuTichId) ? self::ACTIVE : null;
                $donViPhoiHop->save();

                // save th??nh ph???n d??? h???p
                $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'gi???y m???i')->select('id')->first();
                $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
                ThanhPhanDuHop::store($giayMoi, $vanBanDen, [$nguoiDung->id ?? null], null, $nguoiDung->don_vi_id ?? null);
            }
        }
    }

    public static function luuLichHop($vanBanDenId, $lanhDaoDuHopId, $donViDuHop, $donViChuTriId, $phoPhong, $chuyeVien, $trangThai, $donViPhoiHop)
    {
        $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
        $currentUser = auth::user();
        $donVi = DonVi::where('id', $donViChuTriId)->whereNull('deleted_at')->first();
        $parentDonVi = DonVi::where('id', $donVi->parent_id ?? null)->whereNull('deleted_at')->first();
        $tuan = date('W', strtotime($vanBanDen->ngay_hop_chinh));




        $dataLichCongTac1 = array(
            'object_id' => $vanBanDen->id,
            'lanh_dao_id' => $lanhDaoDuHopId,
            'ngay' => $vanBanDen->ngay_hop,
            'gio' => $vanBanDen->gio_hop,
            'tuan' => $tuan,
            'buoi' => ($vanBanDen->gio_hop <= '12:00') ? 1 : 2,
            'noi_dung' => !empty($vanBanDen->noi_dung_hop) ? $vanBanDen->noi_dung_hop : $vanBanDen->trich_yeu,
            'dia_diem' => !empty($vanBanDen->dia_diem) ? $vanBanDen->dia_diem : null,
            'user_id' => $currentUser->id,
            'don_vi_du_hop' => !empty($donViDuHop) ? $donViChuTriId : null,
            'parent_don_vi_id' => !empty($parentDonVi) ? $parentDonVi->id : $donVi->id ?? null
        );
        $dataLichCongTac2 = array(
            'object_id' => $vanBanDen->id,
            'lanh_dao_id' => $phoPhong,
            'ngay' => $vanBanDen->ngay_hop,
            'gio' => $vanBanDen->gio_hop,
            'tuan' => $tuan,
            'buoi' => ($vanBanDen->gio_hop <= '12:00') ? 1 : 2,
            'noi_dung' => !empty($vanBanDen->noi_dung_hop) ? $vanBanDen->noi_dung_hop : $vanBanDen->trich_yeu,
            'dia_diem' => !empty($vanBanDen->dia_diem) ? $vanBanDen->dia_diem : null,
            'user_id' => $currentUser->id,
            'don_vi_du_hop' => !empty($donViDuHop) ? $donViChuTriId : null,
            'parent_don_vi_id' => !empty($parentDonVi) ? $parentDonVi->id : $donVi->id ?? null
        );
        $dataLichCongTac3 = array(
            'object_id' => $vanBanDen->id,
            'lanh_dao_id' => $chuyeVien,
            'ngay' => $vanBanDen->ngay_hop,
            'gio' => $vanBanDen->gio_hop,
            'tuan' => $tuan,
            'buoi' => ($vanBanDen->gio_hop <= '12:00') ? 1 : 2,
            'noi_dung' => !empty($vanBanDen->noi_dung_hop) ? $vanBanDen->noi_dung_hop : $vanBanDen->trich_yeu,
            'dia_diem' => !empty($vanBanDen->dia_diem) ? $vanBanDen->dia_diem : null,
            'user_id' => $currentUser->id,
            'don_vi_du_hop' => !empty($donViDuHop) ? $donViChuTriId : null,
            'parent_don_vi_id' => !empty($parentDonVi) ? $parentDonVi->id : $donVi->id ?? null
        );
        //l???ch l??nh ?????o
        if ($trangThai == 1) {
            $lichCongTac = new LichCongTac();
            $lichCongTac->fill($dataLichCongTac1);
            $lichCongTac->save();
            $lichCongTac->trang_thai = LichCongTac::TRANG_THAI_HOAT_DONG;
            if ($trangThai == 1) {
                $lichCongTac->chu_tri = LichCongTac::TRANG_THAI_HOAT_DONG;

            }
            $lichCongTac->ct_ph = LichCongTac::TRANG_THAI_HOAT_DONG;
            $lichCongTac->save();
        }
        if ($trangThai == 2) {
            if (!empty($phoPhong)) {
                $lichCongTac = new LichCongTac();
                $lichCongTac->fill($dataLichCongTac2);
                $lichCongTac->save();
                $lichCongTac->trang_thai = LichCongTac::TRANG_THAI_HOAT_DONG;
                if ($trangThai == 2) {
                    $lichCongTac->chu_tri = LichCongTac::TRANG_THAI_HOAT_DONG;

                }
                $lichCongTac->ct_ph = LichCongTac::TRANG_THAI_HOAT_DONG;
                $lichCongTac->save();
            }
        }
        // l???ch c???a ph?? ph??ng

        // l???ch c???a chuy??n vi??n
        if($trangThai == 3)
        {
            if (!empty($chuyeVien)) {
                $lichCongTac = new LichCongTac();
                $lichCongTac->fill($dataLichCongTac3);
                $lichCongTac->save();
                $lichCongTac->trang_thai = LichCongTac::TRANG_THAI_HOAT_DONG;
                if ($trangThai == 3) {
                    $lichCongTac->chu_tri = LichCongTac::TRANG_THAI_HOAT_DONG;

                }
                $lichCongTac->ct_ph = LichCongTac::TRANG_THAI_HOAT_DONG;
                $lichCongTac->save();
            }
        }
        if ($donViPhoiHop != null) {
            foreach ($donViPhoiHop as $dataHop) {
                $dataLichCongTac = array(
                    'object_id' => $vanBanDen->id,
                    'lanh_dao_id' => $dataHop,
                    'ngay' => $vanBanDen->ngay_hop,
                    'gio' => $vanBanDen->gio_hop,
                    'tuan' => $tuan,
                    'buoi' => ($vanBanDen->gio_hop <= '12:00') ? 1 : 2,
                    'noi_dung' => !empty($vanBanDen->noi_dung_hop) ? $vanBanDen->noi_dung_hop : $vanBanDen->trich_yeu,
                    'dia_diem' => !empty($vanBanDen->dia_diem) ? $vanBanDen->dia_diem : null,
                    'user_id' => $currentUser->id,
                    'don_vi_du_hop' => !empty($donViDuHop) ? $donViChuTriId : null,
                    'parent_don_vi_id' => !empty($parentDonVi) ? $parentDonVi->id : $donVi->id ?? null
                );
                $lichCongTac = new LichCongTac();
                $lichCongTac->fill($dataLichCongTac);
                $lichCongTac->save();
                $lichCongTac->trang_thai = LichCongTac::TRANG_THAI_HOAT_DONG;
                $lichCongTac->du_hop = LichCongTac::TRANG_THAI_HOAT_DONG;
                $lichCongTac->ct_ph = LichCongTac::TRANG_THAI_HOAT_DONG;
                $lichCongTac->save();
            }
        }


    }

    public static function luuVanBanPhoiHopCapXa($donViId, $txtDonViChuTri, $vanBanDenId, $donViChuTri)
    {
        // luu don vi chu tri
        $donVi = DonVi::where('id', $donViId)->select('id', 'ten_don_vi')->first();

        $role = [TRUONG_BAN, TRUONG_PHONG];
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
            'parent_don_vi_id' => auth::user()->don_vi_id,
            'noi_dung' => $txtDonViChuTri,
            'don_vi_co_dieu_hanh' => $donViChuTri->don_vi_co_dieu_hanh,
            'vao_so_van_ban' => $donViChuTri->vao_so_van_ban,
            'type' => $donViChuTri->type ?? null,
            'user_id' => auth::user()->id,

        ];

        $donViChuTri = new DonViPhoiHop();
        $donViChuTri->fill($dataLuuDonViChuTri);
        $donViChuTri->save();
    }
}
