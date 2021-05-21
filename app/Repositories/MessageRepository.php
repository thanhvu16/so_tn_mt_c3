<?php

namespace App\Repositories;

use App\Common\AllPermission;
use App\User;
use Modules\Admin\Entities\DonVi;
use Modules\VanBanDen\Entities\VanBanDen;

class MessageRepository
{
    protected $homeRepository;

    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function getMessage()
    {
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();

        $users = User::where('trang_thai', ACTIVE)->whereNull('deleted_at')->get();
        $data = [];
        $trinhTuNhanVanBan = null;

        foreach ($users as $user) {
            $donVi = $user->donVi;
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

            // tong so cho xu ly
            if ($user->hasRole(VAN_THU_HUYEN)) {
                $vanBanDenTrongDonVi = $this->homeRepository->vanBanDenTrongDonVi($lanhDaoSo);
                $vanBanDiChoSo = $this->homeRepository->vanBanDiChoSo($lanhDaoSo);
                $data[$user->id] = $vanBanDiChoSo + $vanBanDenTrongDonVi;
            }

            if ($user->hasRole(VAN_THU_DON_VI)) {
                $vanBanChoVaoSo = $this->homeRepository->vanBanChoVaoSo($user);
                $vanBanDenBiTraLai = $this->homeRepository->vanBanBiTraLai($user);
                $vanBanChoPhanLoai = 0;
                $vanBanPhoiHopChoPhanLoai = 0;

                if ($user->can(AllPermission::thamMuu())) {
                    if ($donVi->parent_id != 0) {
                        //phân loại văn bản cấp chi cục
                        $vanBanChoPhanLoai = $this->homeRepository->vanBanChoPhanLoaiDonVi($user);
                        //phan loai van ban don vi phoi hop
                        $vanBanPhoiHopChoPhanLoai = $this->homeRepository->vanBanPhoiHopChoPhanLoaiDonVi($user);
                    }
                }
                $vanBanDiChoSo = $this->homeRepository->vanBanDiChoSoCuaDonVi($user);
                $data[$user->id] = $vanBanDiChoSo + $vanBanChoPhanLoai + $vanBanPhoiHopChoPhanLoai +
                    $vanBanChoVaoSo + $vanBanDenBiTraLai;
            }

            if ($user->hasRole([CHU_TICH, PHO_CHU_TICH])) {
                $vanBanChoXuLy = $this->homeRepository->vanBanChoXuLy($user, $trinhTuNhanVanBan);
                $vanBanXinGiaHan = $this->homeRepository->getVanBanXinGiaHan($user);
                $lichCongTac = $this->homeRepository->getLichCongTac($user);
                $thamDuCuocHop = $this->homeRepository->getUserThamDuCuocHop($user);

                $duThaoChoGopY = $this->homeRepository->getDuThaoChoGopY($user);
                $vanBanDiChoDuyet = $this->homeRepository->vanBanDiChoDuyet($user);
                $vanBanDiTraLai = $this->homeRepository->getVanBanDiTraLai($user);
                $donViPhoiHopChoXuLy = 0;

                if ($donVi->cap_xa == DonVi::CAP_XA) {
                    //VB DON VI PHOI HOP
                    $donViPhoiHopChoXuLy = $this->homeRepository->donViPhoiHopChoXuLy($user);
                }

                $data[$user->id] = $vanBanChoXuLy + $vanBanXinGiaHan + $lichCongTac +
                    $thamDuCuocHop + $duThaoChoGopY + $vanBanDiChoDuyet + $vanBanDiTraLai + $donViPhoiHopChoXuLy;
            }

            if ($user->hasRole([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {

                $vanBanChoXuLy = $this->homeRepository->getVanBanChoXuLy($user, $trinhTuNhanVanBan);
                $vanBanXinGiaHan = $this->homeRepository->getVanBanXinGiaHan($user);
                $duyetVanBanCapDuoiTrinh = $this->homeRepository->getDataDuyetVanBanCapDuoiTrinh($user);
                $vanBanDonViPhoiHopChoXuLy = $this->homeRepository->getDataVanBanDonViPhoiHopChoXuLy($user);
                $lichCongTac = $this->homeRepository->getLichCongTac($user);
                $thamDuCuocHop = $this->homeRepository->getUserThamDuCuocHop($user);
                $vanBanChoPhanLoai = 0;

                if ($user->hasRole(CHANH_VAN_PHONG) && $user->can(AllPermission::thamMuu())
                    && $user->donVi->parent_id == DonVi::NO_PARENT_ID) {
                    $vanBanChoPhanLoai = $this->homeRepository->vanBanChoPhanLoaiChanhVP($user);
                }

                $duThaoChoGopY = $this->homeRepository->getDuThaoChoGopY($user);
                $vanBanDiChoDuyet = $this->homeRepository->vanBanDiChoDuyet($user);
                $vanBanDiTraLai = $this->homeRepository->getVanBanDiTraLai($user);

                $congViecPhongBanChoXuLy = $this->homeRepository->getCongViecPhongChoXuLy($user);
                $congViecPhongBanXinGiaHan = $this->homeRepository->getCongViecPhongBanXinGiaHan($user);
                $congViecPhongBanHoanThanhChoDuyet = $this->homeRepository->getCongViecPhongBanHoanThanhChoDuyet($user);
                $congViecPhongBanPhoiHopChoXuLy = $this->homeRepository->getCongViecPhongBanPhoiHopChoXuLy($user);

                $data[$user->id] = $vanBanChoXuLy + $vanBanXinGiaHan + $duyetVanBanCapDuoiTrinh +
                    $vanBanDonViPhoiHopChoXuLy + $lichCongTac + $thamDuCuocHop + $vanBanChoPhanLoai
                    + $duThaoChoGopY + $vanBanDiChoDuyet + $vanBanDiTraLai + $congViecPhongBanChoXuLy +
                    $congViecPhongBanXinGiaHan + $congViecPhongBanHoanThanhChoDuyet + $congViecPhongBanPhoiHopChoXuLy;
            }

            if ($user->hasRole(CHUYEN_VIEN)) {
                $vanBanChoXuLy = $this->homeRepository->getVanBanChoXuLy($user, $trinhTuNhanVanBan);
                $vanBanChuyenVienPhoiHopChoXuLy = $this->homeRepository->getDataVanBanChuyenVienPhoiHopChoXuy($user);
                $vanBanDonViPhoiHopChoXuLy = $this->homeRepository->getDataVanBanDonViPhoiHopChoXuLy($user);
                $thamDuCuocHop = $this->homeRepository->getUserThamDuCuocHop($user);

                $duThaoChoGopY = $this->homeRepository->getDuThaoChoGopY($user);
                $vanBanDiChoDuyet = $this->homeRepository->vanBanDiChoDuyet($user);
                $vanBanDiTraLai = $this->homeRepository->getVanBanDiTraLai($user);

                $congViecPhongBanChoXuLy = $this->homeRepository->getCongViecPhongChoXuLy($user);
                $congViecChuyenVienPhoiHopChoXuLy = $this->homeRepository->getCongViecPhongBanChuyenVienPhoiHopChoXuLy($user);
                $congViecPhongBanPhoiHopChoXuLy = $this->homeRepository->getCongViecPhongBanPhoiHopChoXuLy($user);

                $data[$user->id] = $vanBanChoXuLy + $vanBanChuyenVienPhoiHopChoXuLy + $vanBanDonViPhoiHopChoXuLy +
                    $thamDuCuocHop + $duThaoChoGopY + $vanBanDiChoDuyet + $vanBanDiTraLai + $congViecPhongBanChoXuLy +
                    $congViecChuyenVienPhoiHopChoXuLy + $congViecPhongBanPhoiHopChoXuLy;
            }
        }

        return $data;
    }
}
