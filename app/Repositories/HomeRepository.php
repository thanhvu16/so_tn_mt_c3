<?php

namespace App\Repositories;

use App\Common\AllPermission;
use App\Models\LichCongTac;
use App\User;
use Auth;
use Modules\Admin\Entities\DonVi;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonViGiaHan;
use Modules\CongViecDonVi\Entities\CongViecDonViPhoiHop;
use Modules\CongViecDonVi\Entities\GiaiQuyetCongViecDonVi;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\LayVanBanTuEmail\Entities\GetEmail;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use Modules\VanBanDi\Entities\VanBanDi;
use Modules\VanBanDi\Entities\VanBanDiChoDuyet;

class HomeRepository
{
    public function getData()
    {
        $user = auth::user();

        $trinhTuNhanVanBan = null;
        if ($user->hasRole(CHU_TICH)) {
            $trinhTuNhanVanBan = VanBanDen::CHU_TICH_NHAN_VB;

            if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                $trinhTuNhanVanBan = VanBanDen::CHU_TICH_XA_NHAN_VB;
            }
        }

        if ($user->hasRole(PHO_CHU_TICH)) {
            $trinhTuNhanVanBan = VanBanDen::PHO_CHU_TICH_NHAN_VB;

            if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                $trinhTuNhanVanBan = VanBanDen::PHO_CHU_TICH_XA_NHAN_VB;
            }
        }

        if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::TRUONG_PHONG_NHAN_VB;
        }

        if ($user->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {
            $trinhTuNhanVanBan = VanBanDen::PHO_PHONG_NHAN_VB;
        }

        if ($user->hasRole(CHUYEN_VIEN)) {
            $trinhTuNhanVanBan = VanBanDen::CHUYEN_VIEN_NHAN_VB;
        }

        $responeData = [];

        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();

        if ($user->hasRole(VAN_THU_HUYEN)) {
            $responeData = $this->getDataVanThuSo($user, $lanhDaoSo);
        }

        if ($user->hasRole(VAN_THU_DON_VI)) {
            $responeData = $this->getDataVanThuDonVi($user, $lanhDaoSo);
        }

        if ($user->hasRole([CHU_TICH, PHO_CHU_TICH])) {
            $responeData = $this->getDataLanhDaoSo($user, $trinhTuNhanVanBan);
        }

        if ($user->hasRole([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {
            $responeData = $this->getDataLanhDaoPhong($user, $trinhTuNhanVanBan);
        }

        if ($user->hasRole(CHUYEN_VIEN)) {
            $responeData = $this->getDataChuyenVien($user, $trinhTuNhanVanBan);
        }

        return $responeData;
    }

    public function getDataVanThuSo($user, $lanhDaoSo)
    {
        $homThuCong = GetEmail::where(['mail_active' => ACTIVE])->count();
        $vanBanDenTrongDonVi = NoiNhanVanBanDi::where('don_vi_id_nhan', $lanhDaoSo->don_vi_id)->whereIn('trang_thai', [2])->count();
        $danhSachVanBanDen = VanBanDen::where('so_van_ban_id', '!=', $giayMoi->id ?? null)
            ->where('type', VanBanDen::TYPE_VB_HUYEN)
            ->whereNull('deleted_at')->count();
        $danhSachGiayMoiDen = VanBanDen::where('so_van_ban_id', $giayMoi->id ?? null)
            ->where('type', VanBanDen::TYPE_VB_HUYEN)
            ->whereNull('deleted_at')
            ->count();

        $arrVanBanDen = [
            'hom_thu_cong' => $this->responseData($homThuCong, route('dsvanbandentumail')),
            'van_ban_den_trong_don_vi' => $this->responseData($vanBanDenTrongDonVi, route('vanBanDonViGuiSo')),
            'danh_sach_van_ban_den' => $this->responseData($danhSachVanBanDen, route('van-ban-den.index')),
            'danh_sach_giay_moi_den' => $this->responseData($danhSachGiayMoiDen, route('giay-moi-den.index'))
        ];

        // van ban di
        $giayMoiDi = VanBanDi::where([
            'loai_van_ban_giay_moi' => 2,
            'loai_van_ban_id' => $giayMoi->id ?? null,
            'don_vi_soan_thao' => null
        ])
            ->whereNotNull('so_di')
            ->whereNull('deleted_at')->count();

        $vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $lanhDaoSo->don_vi_id])
            ->where('so_di', '!=', null)->whereNull('deleted_at')
            ->count();

        $vanBanDiChoSo = VanBanDi::where(['cho_cap_so' => 2,
            'phong_phat_hanh' => $lanhDaoSo->don_vi_id])
            ->orderBy('created_at', 'desc')
            ->orwhere('truong_phong_ky', 2)
            ->count();

        $arrVanBanDi = [
            'van_ban_di_cho_so' => $this->responseData($vanBanDiChoSo, route('vanbandichoso')),
            'danh_sach_van_ban_di' => $this->responseData($vanBanDi, route('van-ban-di.index')),
            'danh_sach_giay_moi_di' => $this->responseData($giayMoiDi, route('giay-moi-di.index'))

        ];

        return [
            'role' => $user->getRole(),
            'van_ban_den' => $arrVanBanDen,
            'van_ban_di' => $arrVanBanDi
        ];
    }

    public function getDataVanThuDonVi($user, $lanhDaoSo)
    {
        $donVi = $user->donVi;

        $vanBanDonViChuTri = DonViChuTri::where(['don_vi_id' => $user->donVi->parent_id])
            ->whereNull('vao_so_van_ban')
            ->whereNull('parent_id')
            ->whereNull('tra_lai')
            ->where('da_chuyen_xuong_don_vi', DonViChuTri::VB_DA_CHUYEN_XUONG_DON_VI)
            ->whereNull('type')
            ->count();

        $noiNhanVanBanDi = NoiNhanVanBanDi::where(['don_vi_id_nhan' => $user->donVi->parent_id])
            ->whereIn('trang_thai', [2])
            ->count();

        $vanBanDonViPhoiHop = DonViPhoiHop::where('don_vi_id', $user->donVi->parent_id)
            ->whereNull('vao_so_van_ban')
            ->whereNull('parent_id')
            ->whereNull('type')
            ->select('id', 'van_ban_den_id', 'can_bo_chuyen_id')
            ->count();

        $vanBanChoVaoSo = $vanBanDonViChuTri + $noiNhanVanBanDi + $vanBanDonViPhoiHop;
        $vanBanDenTraLai = VanBanTraLai::where('can_bo_nhan_id', $user->id)
            ->whereNull('status')->count();
        $danhSachVanBanDen = VanBanDen::where('so_van_ban_id', '!=', $giayMoi->id ?? null)
            ->where('type', VanBanDen::TYPE_VB_DON_VI)
            ->where('don_vi_id', $user->donVi->parent_id)
            ->whereNull('deleted_at')
            ->count();
        $giayMoiDen = VanBanDen::where('so_van_ban_id', '=', $giayMoi->id ?? null)
            ->where('type', VanBanDen::TYPE_VB_DON_VI)
            ->where('don_vi_id', $user->donVi->parent_id)
            ->whereNull('deleted_at')
            ->count();

        $arrVanBanDen = [
            'van_ban_cho_vao_so' => $this->responseData($vanBanChoVaoSo, route('don-vi-nhan-van-ban-den.index')),
            'van_ban_bi_tra_lai' => $this->responseData($vanBanDenTraLai, route('van-ban-tra-lai.index')),
            'danh_sach_van_ban_den' => $this->responseData($danhSachVanBanDen, route('van-ban-den.index')),
            'danh_sach_giay_moi_den' => $this->responseData($giayMoiDen, route('giay-moi-den.index'))
        ];

        if ($user->can(AllPermission::thamMuu())) {
            if ($donVi->parent_id != 0) {
                //phân loại văn bản cấp chi cục
                $donViChuTri = DonViChuTri::where('don_vi_id', $donVi->parent_id)
                    ->whereNull('da_tham_muu')
                    ->select('id', 'van_ban_den_id')
                    ->whereNotNull('vao_so_van_ban')
                    ->whereNull('hoan_thanh')
                    ->get();
                $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

                $vanBanChoPhanLoai = VanBanDen::whereIn('id', $arrVanBanDenId)
                    ->where('trinh_tu_nhan_van_ban', VanBanDen::THAM_MUU_CHI_CUC_NHAN_VB)
                    ->count();

                //phan loai van ban don vi phoi hop
                $vanBanPhoiHopChoPhanLoai = DonViPhoiHop::where('don_vi_id', $donVi->parent_id)
                    ->where('can_bo_nhan_id', $user->id)
                    ->whereNull('chuyen_tiep')
                    ->where('active', DonViPhoiHop::ACTIVE)
                    ->whereNull('hoan_thanh')
                    ->whereNotNull('vao_so_van_ban')
                    ->count();

                $arrVanBanDen['van_ban_cho_phan_loai'] = $this->responseData($vanBanChoPhanLoai, route('phan-loai-van-ban.index'));
                $arrVanBanDen['van_ban_phoi_hop_cho_phan_loai'] = $this->responseData($vanBanPhoiHopChoPhanLoai, route('phan-loai-van-ban-phoi-hop.index'));
            }
        }

        $giayMoiDi = VanBanDi::where([
            'loai_van_ban_giay_moi' => 2,
            'van_ban_huyen_ky' => $user->don_vi_id,
            'loai_van_ban_id' => $giayMoi->id ?? null
        ])
            ->whereNotNull('so_di')
            ->whereNull('deleted_at')->count();

        $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;

        $vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $donViId])
            ->where('so_di', '!=', null)->whereNull('deleted_at')
            ->count();

        $vanBanDiChoSo = VanBanDi::where([
            'cho_cap_so' => 2,
            'phong_phat_hanh' => auth::user()->donVi->parent_id
        ])->count();

        $arrVanBanDi = [
            'van_ban_di_cho_so' => $this->responseData($vanBanDiChoSo, route('vanbandichoso')),
            'danh_sach_van_ban_di' => $this->responseData($vanBanDi, route('van-ban-di.index')),
            'danh_sach_giay_moi_di' => $this->responseData($giayMoiDi, route('giay-moi-di.index'))
        ];

        return [
            'role' => $user->getRole(),
            'van_ban_den' => $arrVanBanDen,
            'van_ban_di' => $arrVanBanDi
        ];

    }

    public function getDataLanhDaoSo($user, $trinhTuNhanVanBan)
    {
        $donVi = $user->donVi;

        $vanBanChoXuLy = $this->vanBanChoXuLy($user, $trinhTuNhanVanBan);
        $vanBanXinGiaHan = $this->getVanBanXinGiaHan($user);
        $vanBanQuanTrong = $this->getVanBanQuanTrong($user);
        $vanBanQuaHanDangXuLy = $this->getVanBanQuaHanDangXuLy($user, $trinhTuNhanVanBan);
        $lichCongTac = $this->getLichCongTac($user);
        $thamDuCuocHop = $this->getUserThamDuCuocHop($user);
        $vanBanChiDaoGiamSat = $this->getVanBanChiDaoGiamSat($user);

        $arrVanBanDen = [
            'van_ban_cho_xu_ly' => $this->responseData($vanBanChoXuLy, route('van-ban-lanh-dao-xu-ly.index')),
            'van_ban_xin_gia_han' => $this->responseData($vanBanXinGiaHan, route('gia-han-van-ban.index')),
            'van_ban_quan_trong' => $this->responseData($vanBanQuanTrong, route('van-ban-den-don-vi.quan_trong')),
            'van_ban_qua_han_dang_xu_ly' => $this->responseData($vanBanQuaHanDangXuLy, route('van-ban-den-don-vi.dang_xu_ly', 'qua_han=1')),
            'lich_cong_tac' => $this->responseData($lichCongTac, route('lich-cong-tac.index')),
            'tham_du_cuoc_hop' => $this->responseData($thamDuCuocHop, route('tham-du-cuoc-hop.index')),
            'van_ban_chi_dao_giam_sat' => $this->responseData($vanBanChiDaoGiamSat, route('van-ban-den-don-vi.xem_de_biet'))
        ];

        if ($donVi->cap_xa == DonVi::CAP_XA) {
            //VB DON VI PHOI HOP
            $chuyenTiep = null;

            $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->where(function ($query) use ($chuyenTiep) {
                    if (!empty($chuyenTiep)) {
                        return $query->where('chuyen_tiep', $chuyenTiep);
                    } else {
                        return $query->whereNull('chuyen_tiep');
                    }
                })
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->count();

            $arrVanBanDen['don_vi_phoi_hop_cho_xu_ly'] = $this->responseData($donViPhoiHop, route('van-ban-den-phoi-hop.index'));
        }

        // van ban di
        $danhSachDuThao = $this->getDuThaoVanBanDi($user);
        $duThaoChoGopY = $this->getDuThaoChoGopY($user);
        $vanBanDiChoDuyet = $this->vanBanDiChoDuyet($user);
        $vanBanDiTraLai = $this->getVanBanDiTraLai($user);

        $arrVanBanDi = [
            'van_ban_ca_nhan_du_thao' => $this->responseData($danhSachDuThao, route('Danhsachduthao')),
            'du_thao_cho_gop_y' => $this->responseData($duThaoChoGopY, route('danhsachgopy')),
            'van_ban_di_cho_duyet' => $this->responseData($vanBanDiChoDuyet, route('danh_sach_vb_di_cho_duyet')),
            'van_ban_di_tra_lai' => $this->responseData($vanBanDiTraLai, route('vb_di_tra_lai'))
        ];

        return [
            'role' => $user->getRole(),
            'van_ban_den' => $arrVanBanDen,
            'van_ban_di' => $arrVanBanDi
        ];

    }

    public function getDataLanhDaoPhong($user, $trinhTuNhanVanBan)
    {
        $vanBanChoXuLy = $this->getVanBanChoXuLy($user, $trinhTuNhanVanBan);
        $vanBanXinGiaHan = $this->getVanBanXinGiaHan($user);
        $duyetVanBanCapDuoiTrinh = $this->getDataDuyetVanBanCapDuoiTrinh($user);
        $vanBanDonViPhoiHopChoXuLy = $this->getDataVanBanDonViPhoiHopChoXuLy($user);
        $vanBanQuaHanDangXuLy = $this->getVanBanQuaHanDangXuLy($user, $trinhTuNhanVanBan);
        $lichCongTac = $this->getLichCongTac($user);
        $thamDuCuocHop = $this->getUserThamDuCuocHop($user);

        $arrVanBanDen = [
            'van_ban_cho_xu_ly' => $this->responseData($vanBanChoXuLy, route('van-ban-den-don-vi.index')),
            'van_ban_xin_gia_han' => $this->responseData($vanBanXinGiaHan, route('gia-han-van-ban.index')),
            'duyet_van_ban_cap_duoi_trinh' => $this->responseData($duyetVanBanCapDuoiTrinh, route('duyet-van-ban-cap-duoi-trinh')),
            'don_vi_phoi_hop_cho_xu_ly' => $this->responseData($vanBanDonViPhoiHopChoXuLy, route('van-ban-den-phoi-hop.index')),
            'van_ban_qua_han_dang_xu_ly' => $this->responseData($vanBanQuaHanDangXuLy, route('van-ban-den-don-vi.dang_xu_ly', 'qua_han=1')),
            'lich_cong_tac' => $this->responseData($lichCongTac, route('lich-cong-tac.index')),
            'tham_du_cuoc_hop' => $this->responseData($thamDuCuocHop, route('tham-du-cuoc-hop.index'))
        ];

        if ($user->hasRole([PHO_PHONG, PHO_TRUONG_BAN, PHO_CHANH_VAN_PHONG])) {
            $vanBanChiDaoGiamSat = $this->getVanBanChiDaoGiamSat($user);
            $arrVanBanDen['van_ban_chi_dao_giam_sat'] = $vanBanChiDaoGiamSat;
        }

        // tham muu
        if ($user->hasRole(CHANH_VAN_PHONG) && $user->can(AllPermission::thamMuu())
            && $user->donVi->parent_id == DonVi::NO_PARENT_ID) {
            $vanBanChoPhanLoai = VanBanDen::where('lanh_dao_tham_muu', $user->id)
                ->whereNull('trinh_tu_nhan_van_ban')
                ->count();
            $arrVanBanDen['van_ban_cho_phan_loai'] = $vanBanChoPhanLoai;
        }

        // du thao van ban di
        $danhSachDuThao = $this->getDuThaoVanBanDi($user);
        $duThaoChoGopY = $this->getDuThaoChoGopY($user);
        $vanBanDiChoDuyet = $this->vanBanDiChoDuyet($user);
        $vanBanDiTraLai = $this->getVanBanDiTraLai($user);

        $arrVanBanDi = [
            'van_ban_ca_nhan_du_thao' => $this->responseData($danhSachDuThao, route('Danhsachduthao')),
            'du_thao_cho_gop_y' => $this->responseData($duThaoChoGopY, route('danhsachgopy')),
            'van_ban_di_cho_duyet' => $this->responseData($vanBanDiChoDuyet, route('danh_sach_vb_di_cho_duyet')),
            'van_ban_di_tra_lai' => $this->responseData($vanBanDiTraLai, route('vb_di_tra_lai'))
        ];

        // cong viec phong ban
        $congViecPhongBanChoXuLy = $this->getCongViecPhongChoXuLy($user);
        $congViecPhongBanXinGiaHan = $this->getCongViecPhongBanXinGiaHan($user);
        $congViecPhongBanHoanThanhChoDuyet = $this->getCongViecPhongBanHoanThanhChoDuyet($user);
        $congViecPhongBanPhoiHopChoXuLy = $this->getCongViecPhongBanPhoiHopChoXuLy($user);

        $arrCongViecPhongBan = [
            'cho_xu_ly' => $this->responseData($congViecPhongBanChoXuLy, route('cong-viec-don-vi.index')),
            'xin_gia_han' => $this->responseData($congViecPhongBanXinGiaHan, route('gia-han-cong-viec.index')),
            'hoan_thanh_cho_duyet' => $this->responseData($congViecPhongBanHoanThanhChoDuyet, route('cong-viec-hoan-thanh.cho-duyet')),
            'phoi_hop_cho_xu_ly' => $this->responseData($congViecPhongBanPhoiHopChoXuLy, route('cong-viec-don-vi-phoi-hop.index'))
        ];

        return [
            'role' => $user->getRole(),
            'van_ban_den' => $arrVanBanDen,
            'van_ban_di' => $arrVanBanDi,
            'cong_viec_phong_ban' => $arrCongViecPhongBan
        ];

    }

    public function getDataChuyenVien($user, $trinhTuNhanVanBan)
    {
        $vanBanChoXuLy = $this->getVanBanChoXuLy($user, $trinhTuNhanVanBan);
        $vanBanChuyenVienPhoiHopChoXuLy = $this->getDataVanBanChuyenVienPhoiHopChoXuy($user);
        $vanBanDonViPhoiHopChoXuLy = $this->getDataVanBanDonViPhoiHopChoXuLy($user);
        $vanBanQuaHanDangXuLy = $this->getVanBanQuaHanDangXuLy($user, $trinhTuNhanVanBan);
        $thamDuCuocHop = $this->getUserThamDuCuocHop($user);

        $arrVanBanDen = [
            'van_ban_cho_xu_ly' => $this->responseData($vanBanChoXuLy, route('van-ban-den-don-vi.index')),
            'van_ban_chuyen_vien_phoi_hop_cho_xu_ly' => $this->responseData($vanBanChuyenVienPhoiHopChoXuLy, route('van_ban_den_chuyen_vien.index')),
            'don_vi_phoi_hop_cho_xu_ly' => $this->responseData($vanBanDonViPhoiHopChoXuLy, route('van-ban-den-phoi-hop.index')),
            'van_ban_qua_han_dang_xu_ly' => $this->responseData($vanBanQuaHanDangXuLy, route('van-ban-den-don-vi.dang_xu_ly', 'qua_han=1')),
            'tham_du_cuoc_hop' => $this->responseData($thamDuCuocHop, route('tham-du-cuoc-hop.index'))
        ];

        // du thao van ban di
        $danhSachDuThao = $this->getDuThaoVanBanDi($user);
        $duThaoChoGopY = $this->getDuThaoChoGopY($user);
        $vanBanDiChoDuyet = $this->vanBanDiChoDuyet($user);
        $vanBanDiTraLai = $this->getVanBanDiTraLai($user);

        $arrVanBanDi = [
            'van_ban_ca_nhan_du_thao' => $this->responseData($danhSachDuThao, route('Danhsachduthao')),
            'du_thao_cho_gop_y' => $this->responseData($duThaoChoGopY, route('danhsachgopy')),
            'van_ban_di_cho_duyet' => $this->responseData($vanBanDiChoDuyet, route('danh_sach_vb_di_cho_duyet')),
            'van_ban_di_tra_lai' => $this->responseData($vanBanDiTraLai, route('vb_di_tra_lai'))
        ];

        // cong viec phong ban
        $congViecPhongBanChoXuLy = $this->getCongViecPhongChoXuLy($user);
        $congViecChuyenVienDaXuLy = $this->congViecPhongBanChuyenVienDaXuLy($user);
        $congViecChuyenVienPhoiHopChoXuLy = $this->getCongViecPhongBanChuyenVienPhoiHopChoXuLy($user);
        $congViecPhongBanPhoiHopChoXuLy = $this->getCongViecPhongBanPhoiHopChoXuLy($user);

        $arrCongViecPhongBan = [
            'cho_xu_ly' => $this->responseData($congViecPhongBanChoXuLy, route('cong-viec-don-vi.index')),
            'da_xu_ly' => $this->responseData($congViecChuyenVienDaXuLy, route('cong-viec-don-vi.da-xu-ly')),
            'chuyen_vien_phoi_hop_cho_xu_ly' => $this->responseData($congViecChuyenVienPhoiHopChoXuLy, route('cong-viec-don-vi.chuyen-vien-phoi-hop')),
            'don_vi_phoi_hop_cho_xu_ly' => $this->responseData($congViecPhongBanPhoiHopChoXuLy, route('cong-viec-don-vi-phoi-hop.index'))
        ];

        return [
            'role' => $user->getRole(),
            'van_ban_den' => $arrVanBanDen,
            'van_ban_di' => $arrVanBanDi,
            'cong_viec_phong_ban' => $arrCongViecPhongBan
        ];

    }

    public function getDataVanBanChuyenVienPhoiHopChoXuy($user)
    {
        $chuyenVienPhoiHopChoXuLy = ChuyenVienPhoiHop::where('can_bo_nhan_id', $user->id)
            ->whereNull('status')->count();

        return $chuyenVienPhoiHopChoXuLy;
    }

    public function getVanBanChoXuLy($user, $trinhTuNhanVanBan)
    {
        $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
            ->where('can_bo_nhan_id', $user->id)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->get();

        $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

        $vanBanChoXuLy = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
            ->count();

        return $vanBanChoXuLy;
    }

    public function getDataDuyetVanBanCapDuoiTrinh($user)
    {
        return GiaiQuyetVanBan::where('can_bo_duyet_id', $user->id)
            ->whereNull('status')->count();
    }

    public function getVanBanXinGiaHan($user)
    {
        return GiaHanVanBan::where('can_bo_nhan_id', $user->id)
            ->where('status', GiaHanVanBan::STATUS_CHO_DUYET)
            ->count();
    }

    public function vanBanChoXuLy($user, $trinhTuNhanVanBan)
    {
        $donVi = $user->donVi;
        $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $user->id)
            ->whereNull('status')
            ->whereNull('hoan_thanh')
            ->get();

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {

            $xuLyVanBanDen = DonViChuTri::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->select('id', 'van_ban_den_id')
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->select('id', 'van_ban_den_id')
                ->get();
        }

        $arrIdVanBanDenDonVi = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        $vanBanChoXuLy = VanBanDen::whereIn('id', $arrIdVanBanDenDonVi)
            ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
            ->count();

        return $vanBanChoXuLy;
    }

    public function getDataVanBanDonViPhoiHopChoXuLy($user)
    {
        $chuyenTiep = null;

        $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $user->don_vi_id)
            ->where('can_bo_nhan_id', $user->id)
            ->where(function ($query) use ($chuyenTiep) {
                if (!empty($chuyenTiep)) {
                    return $query->where('chuyen_tiep', $chuyenTiep);
                } else {
                    return $query->whereNull('chuyen_tiep');
                }
            })
            ->where('active', DonViPhoiHop::ACTIVE)
            ->whereNotNull('vao_so_van_ban')
            ->whereNull('hoan_thanh')
            ->count();

        return $donViPhoiHop;
    }

    public function getVanBanQuanTrong($user)
    {
        return VanBanQuanTrong::where('user_id', $user->id)
            ->count();
    }

    public function getVanBanQuaHanDangXuLy($user, $trinhTuNhanVanBan)
    {
        $currentDate = date('Y-m-d');
        $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $user->id)
            ->whereNull('status')
            ->whereNull('hoan_thanh')
            ->get();

        $arrVanBanDenId = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, CHUYEN_VIEN, PHO_PHONG,
            PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {

            $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        }

        if ($user->hasRole([CHU_TICH, PHO_CHU_TICH]) && $user->donVi->cap_xa == DonVi::CAP_XA) {

            $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();
        }

        $vanBanQuaHanDangXuLy = VanBanDen::whereIn('id', $arrVanBanDenId)
            ->where(function ($query) use ($currentDate) {
                return $query->where('han_xu_ly', '<', $currentDate);
            })
            ->count();

        return $vanBanQuaHanDangXuLy;
    }

    public function getLichCongTac($user)
    {
        //LICH CONG TAC
        $year = date('Y');
        $week = date('W');

        $start_date = strtotime($year . "W" . $week . 1);
        $end_date = strtotime($year . "W" . $week . 7);

        $ngayBatDau = date('Y-m-d', $start_date);
        $ngayKetThuc = date('Y-m-d', $end_date);

        $lichCongTac = LichCongTac::where('ngay', '>=', $ngayBatDau)
            ->where('ngay', '<=', $ngayKetThuc)
            ->where('lanh_dao_id', $user->id)
            ->whereNotNull('trang_thai')
            ->count();

        return $lichCongTac;
    }

    public function getUserThamDuCuocHop($user)
    {
        $year = date('Y');
        $week = date('W');

        $start_date = strtotime($year . "W" . $week . 1);
        $end_date = strtotime($year . "W" . $week . 7);

        $ngayBatDau = date('Y-m-d', $start_date);
        $ngayKetThuc = date('Y-m-d', $end_date);

        // tham du cuoc hop
        $danhSachthamDuCuocHop = ThanhPhanDuHop::where('user_id', $user->id)
            ->whereNull('lanh_dao_id')
            ->select('lich_cong_tac_id')
            ->get();
        $lichConTacId = $danhSachthamDuCuocHop->pluck('lich_cong_tac_id');

        $thamDuCuocHop = LichCongTac::whereIn('id', $lichConTacId)
            ->where('ngay', '>=', $ngayBatDau)
            ->where('ngay', '<=', $ngayKetThuc)
            ->count();

        return $thamDuCuocHop;
    }

    public function getVanBanChiDaoGiamSat($user)
    {
        $month = date('m');
        $year = date('Y');

        $vanBanXemDeBiet = LanhDaoXemDeBiet::where('lanh_dao_id', $user->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'DESC')
            ->count();

        return $vanBanXemDeBiet;
    }

    public function getDuThaoVanBanDi($user)
    {
        return Duthaovanbandi::where(['nguoi_tao' => $user->id, 'stt' => 1])->count();
    }

    public function getDuThaoChoGopY($user)
    {
        $canBoPhongGopY = CanBoPhongDuThao::where(['can_bo_id' => $user->id, 'trang_thai' => 1])->get();
        $canBoNgoaiGopY = CanBoPhongDuThaoKhac::where(['can_bo_id' => $user->id, 'trang_thai' => 1])->get();
        $tong = count($canBoPhongGopY) + count($canBoNgoaiGopY);

        return $tong;
    }

    public function vanBanDiChoDuyet($user)
    {
        return Vanbandichoduyet::where(['can_bo_nhan_id' => $user->id, 'trang_thai' => 1])->count();
    }

    public function getVanBanDiTraLai($user)
    {
        return Vanbandichoduyet::where(['can_bo_nhan_id' => $user->id, 'trang_thai' => 0, 'tra_lai' => 1])->count();
    }

    public function getCongViecPhongChoXuLy($user)
    {
        $congViecDonViChoXuLy = ChuyenNhanCongViecDonVi::where('can_bo_nhan_id', $user->id)
            ->whereNull('type')
            ->whereNull('chuyen_tiep')
            ->orWhere('chuyen_tiep', 0)
            ->whereNull('hoan_thanh')
            ->count();

        return $congViecDonViChoXuLy;
    }

    public function getCongViecPhongBanXinGiaHan($user)
    {
        $giaHanCongViecDonVi = CongViecDonViGiaHan::where('can_bo_nhan_id', $user->id)
            ->where('status', CongViecDonViGiaHan::STATUS_CHO_DUYET)
            ->count();

        return $giaHanCongViecDonVi;
    }

    public function getCongViecPhongBanHoanThanhChoDuyet($user)
    {
        $congViecDonViHoanThanhChoDuyet = GiaiQuyetCongViecDonVi::where('lanh_dao_duyet_id', $user->id)
            ->whereNull('status')
            ->count();

        return $congViecDonViHoanThanhChoDuyet;
    }

    public function getCongViecPhongBanPhoiHopChoXuLy($user)
    {
        $congViecDonViPhoiHopChoXuLy = ChuyenNhanCongViecDonVi::where('can_bo_nhan_id', $user->id)
            ->where('type', ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP)
            ->whereNull('chuyen_tiep')
            ->whereNull('hoan_thanh')
            ->count();

        return $congViecDonViPhoiHopChoXuLy;
    }

    public function congViecPhongBanChuyenVienDaXuLy($user)
    {
        $congViecChuyenVienDaXuLy = ChuyenNhanCongViecDonVi::where('can_bo_nhan_id', $user->id)
            ->whereNull('type')
            ->where('chuyen_tiep', ChuyenNhanCongViecDonVi::GIAI_QUYET)
            ->whereNull('hoan_thanh')
            ->count();

        return $congViecChuyenVienDaXuLy;
    }

    public function getCongViecPhongBanChuyenVienPhoiHopChoXuLy($user)
    {
        return CongViecDonViPhoiHop::where('can_bo_nhan_id', $user->id)
            ->whereNull('status')
            ->whereNull('type')->count();
    }

    public function responseData($data, $url)
    {
        return [
            'tong' => $data,
            'url'   => $url
        ];
    }
}
