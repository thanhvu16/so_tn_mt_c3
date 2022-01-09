<?php

namespace Modules\Admin\Http\Controllers;

use App\Common\AllPermission;
use App\Models\LichCongTac;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonViGiaHan;
use Modules\CongViecDonVi\Entities\CongViecDonViPhoiHop;
use Modules\CongViecDonVi\Entities\GiaiQuyetCongViecDonVi;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoChiDao;
use Modules\DieuHanhVanBanDen\Entities\LanhDaoXemDeBiet;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\LayVanBanTuEmail\Entities\GetEmail;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use Modules\VanBanDi\Entities\VanBanDi;
use Modules\VanBanDi\Entities\VanBanDiChoDuyet;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

//    public static function layDBT()
//    {
//            $db = \Session()->get('tenDB');
//            dd($db);
//            return $db;
//    }
    public function setDB(Request $request)
    {
        if($request->year == 2021)
        {
            \Config::set('database.connections.sqlsrv.database', 'so_tai_nguyen_moi_truong');
            \Session::put('tenDB',  'so_tai_nguyen_moi_truong');
            \Session::put('nam',  $request->year);

        }else{
            \Config::set('database.connections.sqlsrv.database', 'so_tai_nguyen_moi_truong'.$request->get('year'));
            \Session::put('tenDB',  'so_tai_nguyen_moi_truong_'.$request->get('year'));
            \Session::put('nam',  $request->year);

        }
        return redirect()->back();

    }

    public function index()
    {
//        dd( \Config::get('database.connections.sqlsrv.database'));
//        dd( \Session()->get('tenDB'));
        $giayMoiPiceCharts = [];
        $giayMoiCoLors = [];
        $vanThuVanBanDiPiceCharts = [];
        $vanThuVanBanDenPiceCharts = [];
        $vanThuVanBanDiCoLors = [];
        $vanThuVanBanDenCoLors = [];
        $duThaoCoLors = [];
        $duThaoPiceCharts = [];
        $user = auth::user();
        $donVi = $user->donVi;
        $month = date('m');
        $year = date('Y');
        $vanBanChoPhanLoai = 0;
        $giayMoiChoPhanLoai = 0;
        $giayMoiChoPhanLoai = 0;
        $vanBanPhoiHopChoPhanLoai = 0;
        $giayMoiPhoiHopChoPhanLoai = 0;

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')->first();

        array_push($giayMoiPiceCharts, array('Task', 'Danh sách'));

        if ($user->hasRole(QUAN_TRI_HT)) {
            return redirect()->route('nguoi-dung.index');
        }
        $danhSachDuThao = Duthaovanbandi::where(['nguoi_tao' => $user->id, 'stt' => 1])->count();

        $vanbandichoduyet = Vanbandichoduyet::where(['can_bo_nhan_id' => $user->id, 'trang_thai' => 1])->count();
        $van_ban_di_tra_lai = Vanbandichoduyet::where(['can_bo_nhan_id' => $user->id, 'trang_thai' => 0, 'tra_lai' => 1])->count();
        $canbogopy = CanBoPhongDuThao::where(['can_bo_id' => $user->id, 'trang_thai' => 1])->get();
        $key2 = count($canbogopy);
        $canbogopyngoai = CanBoPhongDuThaoKhac::where(['can_bo_id' => $user->id, 'trang_thai' => 1])->get();
        $key1 = count($canbogopyngoai);
        $gopy = $key2 + $key1;

        //văn bản đến
        array_push($vanThuVanBanDenPiceCharts, array('Task', 'Danh sách'));
        array_push($vanThuVanBanDiPiceCharts, array('Task', 'Danh sách'));

        $homThuCong = 0;
        $vanBanTuDonViGui = 0;
        $danhSachVanBanDen = 0;
        $vanBanDenDonViChoVaoSo = 0;
        $vanBanDenTraLai = 0;
        $giayMoiDen = 0;
        $giayMoiDi = 0;
        $vanBanDi = 0;
        $vanBanDiChoSo = 0;

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->first();

        if ($user->hasRole(VAN_THU_HUYEN)) {

            $homThuCong = GetEmail::where(['mail_active' => ACTIVE])->count();

            array_push($vanThuVanBanDenPiceCharts, array('Hòm thư công', $homThuCong));
            array_push($vanThuVanBanDenCoLors, COLOR_INFO_SHADOW);

            $danhSachVanBanDen = VanBanDen::where('so_van_ban_id', '!=', $giayMoi->id ?? null)
                ->where('type', VanBanDen::TYPE_VB_HUYEN)
                ->whereNull('deleted_at')
                ->count();


            array_push($vanThuVanBanDenPiceCharts, array('Danh sách văn bản đến', $danhSachVanBanDen));
            array_push($vanThuVanBanDenCoLors, COLOR_PINTEREST);

            $giayMoiDen = VanBanDen::where('so_van_ban_id', $giayMoi->id ?? null)
                ->where('type', VanBanDen::TYPE_VB_HUYEN)
                ->whereNull('deleted_at')
                ->count();

            array_push($vanThuVanBanDenPiceCharts, array('Danh sách giấy mời đến', $giayMoiDen));
            array_push($vanThuVanBanDenCoLors, COLOR_GREEN);

            //van ban di
            $giayMoiDi = VanBanDi::where([
                'loai_van_ban_giay_moi' => 2,
                'loai_van_ban_id' => $giayMoi->id ?? null,
                'don_vi_soan_thao' => null
            ])
                ->whereNotNull('so_di')
                ->whereNull('deleted_at')->count();

            array_push($vanThuVanBanDiPiceCharts, array('Danh sách giấy mời đi', $giayMoiDi));
            array_push($vanThuVanBanDiCoLors, COLOR_RED);

            $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->first();

            $vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $lanhDaoSo->don_vi_id])
                ->where('so_di', '!=', null)->whereNull('deleted_at')
                ->count();

            array_push($vanThuVanBanDiPiceCharts, array('Danh sách văn bản đi', $vanBanDi));
            array_push($vanThuVanBanDiCoLors, COLOR_PRIMARY);


            $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->first();

            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                $vanBanDiChoSo = VanBanDi::
                    where(function ($query) use ($lanhDaoSo){
                        return  $query->where('phong_phat_hanh', $lanhDaoSo->don_vi_id);
                    })
                    ->whereNull('so_di')
                    ->orderBy('created_at', 'desc')
                    ->count();
            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $vanBanDiChoSo = VanBanDi::where(function ($query){
                        return  $query->where('phong_phat_hanh', auth::user()->donVi->parent_id);
                    })
                    ->whereNull('so_di')
                    ->orderBy('created_at', 'desc')
                    ->count();

            }

            array_push($vanThuVanBanDiPiceCharts, array('Văn bản đi chờ số', $vanBanDiChoSo));
            array_push($vanThuVanBanDiCoLors, COLOR_WARNING);

            // van ban tu chi cuc gui len so

            $vanBanTuDonViGui = NoiNhanVanBanDi::where(['don_vi_id_nhan' => $lanhDaoSo->don_vi_id]
            )->whereIn('trang_thai', [2])->count();

            array_push($vanThuVanBanDenPiceCharts, array('Văn bản đến trong đơn vi', $vanBanTuDonViGui));
            array_push($vanThuVanBanDenCoLors, COLOR_WARNING);
        }

        if ($user->hasRole(VAN_THU_DON_VI)) {
            // phan loai van ban neu van thu co quyen tham muu
            if ($user->can(AllPermission::thamMuu())) {
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

                array_push($vanThuVanBanDenPiceCharts, array('VB chờ phân loại', $vanBanChoPhanLoai));
                array_push($vanThuVanBanDenCoLors, COLOR_PURPLE);

                if ($donVi->parent_id != 0) {
                    //phan loai van ban don vi phoi hop
                    $vanBanPhoiHopChoPhanLoai = DonViPhoiHop::where('don_vi_id', $donVi->parent_id)
                        ->whereHas('vanBanDenDen')
                        ->where('can_bo_nhan_id', $user->id)
                        ->whereNull('chuyen_tiep')
                        ->where('active', DonViPhoiHop::ACTIVE)
                        ->whereNull('hoan_thanh')
                        ->whereNotNull('vao_so_van_ban')
//                        ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
                        ->count();
                    $giayMoiPhoiHopChoPhanLoai = DonViPhoiHop::where('don_vi_id', $donVi->parent_id)
                        ->whereHas('giayMoiDen')
                        ->where('can_bo_nhan_id', $user->id)
                        ->whereNull('chuyen_tiep')
                        ->where('active', DonViPhoiHop::ACTIVE)
                        ->whereNull('hoan_thanh')
                        ->whereNotNull('vao_so_van_ban')
//                        ->where('loai_van_ban_id',$loaiVanBanGiayMoi->id)
                        ->count();

                    array_push($vanThuVanBanDenPiceCharts, array('VB phối hợp chờ phân loại', $vanBanPhoiHopChoPhanLoai));
                    array_push($vanThuVanBanDenCoLors, COLOR_GREEN_LIGHT);
                    array_push($giayMoiPiceCharts, array('GM phối hợp chờ phân loại', $giayMoiPhoiHopChoPhanLoai));
                    array_push($giayMoiCoLors, COLOR_GREEN_LIGHT);
                }
            }
            $donViVT = DonVi::where('id',$user->don_vi_id)->first();
            if($donViVT->cap_chi_nhanh == 1)
            {
                $danhSachVanBanDen = VanBanDen::where('so_van_ban_id', '!=', $giayMoi->id ?? null)
                    ->where('type', VanBanDen::TYPE_VB_DON_VI)
                    ->where('van_ban_chi_nhanh', $user->donVi->id)
                    ->whereNull('deleted_at')
                    ->count();
            }else{
                $danhSachVanBanDen = VanBanDen::where('so_van_ban_id', '!=', $giayMoi->id ?? null)
                    ->where('type', VanBanDen::TYPE_VB_DON_VI)
                    ->where('don_vi_id', $user->donVi->parent_id)
                    ->whereNull('deleted_at')
                    ->count();
            }


            array_push($vanThuVanBanDenPiceCharts, array('Danh sách văn bản đến', $danhSachVanBanDen));
            array_push($vanThuVanBanDenCoLors, COLOR_PINTEREST);

            if($donViVT->cap_chi_nhanh == 1)
            {
                $vanBanDonViPhoiHop = DonViChuTri::with('canBoChuyen')
                    ->where(['don_vi_id' => $user->donVi->id])
                    ->where('van_thu_nhan',1)
                    ->whereNull('da_vao_so')
                    ->where('can_bo_nhan_id',auth::user()->id)
                   ->count();
                $vanBanDenDonViChoVaoSo = $vanBanDonViPhoiHop;
            }else{
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
                $vanBanDenDonViChoVaoSo = $vanBanDonViChuTri + $noiNhanVanBanDi + $vanBanDonViPhoiHop;
            }





            array_push($vanThuVanBanDenPiceCharts, array('Văn bản đến chờ vào sổ', $vanBanDenDonViChoVaoSo));
            array_push($vanThuVanBanDenCoLors, COLOR_WARNING);


            if($donViVT->cap_chi_nhanh == 1)
            {
                $giayMoiDen = VanBanDen::where('so_van_ban_id', '=', $giayMoi->id ?? null)
                    ->where('type', VanBanDen::TYPE_VB_DON_VI)
                    ->where('van_ban_chi_nhanh', $user->donVi->id)
                    ->whereNull('deleted_at')
                    ->count();
            }else{
                $giayMoiDen = VanBanDen::where('so_van_ban_id', '=', $giayMoi->id ?? null)
                    ->where('type', VanBanDen::TYPE_VB_DON_VI)
                    ->where('don_vi_id', $user->donVi->parent_id)
                    ->whereNull('deleted_at')
                    ->count();
            }

            array_push($vanThuVanBanDenPiceCharts, array('Danh sách giấy mời đến', $giayMoiDen));
            array_push($vanThuVanBanDenCoLors, COLOR_GREEN);

            $vanBanDenTraLai = VanBanTraLai::where('can_bo_nhan_id', $user->id)
                ->whereNull('status')->count();

            array_push($vanThuVanBanDenPiceCharts, array('Văn bản đến trả lại', $vanBanDenTraLai));
            array_push($vanThuVanBanDenCoLors, COLOR_PRIMARY);

            //van ban di
            $giayMoiDi = VanBanDi::where([
                'loai_van_ban_giay_moi' => 2,
                'van_ban_huyen_ky' => $user->don_vi_id,
                'loai_van_ban_id' => $giayMoi->id ?? null
            ])
                ->whereNotNull('so_di')
                ->whereNull('deleted_at')->count();

            array_push($vanThuVanBanDiPiceCharts, array('Danh sách giấy mời đi', $giayMoiDi));
            array_push($vanThuVanBanDiCoLors, COLOR_RED);

            $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;
            $vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $donViId])
                ->where('so_di', '!=', null)->whereNull('deleted_at')
                ->count();

            array_push($vanThuVanBanDiPiceCharts, array('Danh sách văn bản đi', $vanBanDi));
            array_push($vanThuVanBanDiCoLors, COLOR_PRIMARY);

            $vanBanDiChoSo = VanBanDi::where(function ($query){
                return  $query->where('phong_phat_hanh', auth::user()->donVi->parent_id);
            })
                ->whereNull('so_di')
                ->orderBy('created_at', 'desc')
                ->count();


            array_push($vanThuVanBanDiPiceCharts, array('Văn bản đi chờ số', $vanBanDiChoSo));
            array_push($vanThuVanBanDiCoLors, COLOR_WARNING);

        }

        //dự thảo văn bản đi
        array_push($duThaoCoLors, COLOR_WARNING);
        array_push($duThaoCoLors, COLOR_PRIMARY);
        array_push($duThaoCoLors, COLOR_GREEN);
        array_push($duThaoCoLors, COLOR_PINTEREST);
        //màu
        array_push($duThaoPiceCharts, array('Task', 'Danh sách'));
        array_push($duThaoPiceCharts, array('Danh sách cá nhân dự thảo', $danhSachDuThao));
        array_push($duThaoPiceCharts, array('dự thảo chờ góp ý', $gopy));
        array_push($duThaoPiceCharts, array('Danh sách văn bản đi chờ duyệt', $vanbandichoduyet));
        array_push($duThaoPiceCharts, array('Danh sách văn bản trả lại', $van_ban_di_tra_lai));

        // ho so cong viec
        $currentDate = date('Y-m-d');
        $hoSoCongViecPiceCharts = [];
        $hoSoCongViecCoLors = [];
        $active = 0;
        $vanBanQuanTrong = 0;
        $giayMoiQuanTrong = 0;
        $vanBanXemDeBiet = 0;
        $giayMoiXemDeBiet = 0;
        $vanBanChoXuLy = 0;
        $vanBanChoYKien = 0;
        $giayMoiChoXuLy = 0;
        $vanBanXinGiaHan = 0;
        $giayMoiXinGiaHan = 0;
        $duyetVanBanCapDuoiTrinh = 0;
        $duyetGiayMoiCapDuoiTrinh = 0;
        $donViPhoiHop = 0;
        $donViPhoiHopGM = 0;
        $chuyenVienPhoiHop = 0;
        $chuyenVienPhoiHopGM = 0;
        $vanBanQuaHanDangXuLy = 0;
        $giayMoiQuaHanDangXuLy = 0;
        $lichCongTac = 0;
        $thamDuCuocHop = 0;
        array_push($hoSoCongViecPiceCharts, array('Task', 'Danh sách'));

        $congViecPhongBanPiceCharts = [];
        $congViecPhongBanCoLors = [];
        $congViecDonViChoXuLy = 0;
        $giaHanCongViecDonVi = 0;
        $congViecDonViHoanThanhChoDuyet = 0;
        $congViecDonViPhoiHopChoXuLy = 0;
        $congViecChuyenVienPhoiHopChoXuLy = 0;
        $congViecChuyenVienDaXuLy = 0;
        array_push($congViecPhongBanPiceCharts, array('Task', 'Danh sách'));

        //LICH CONG TAC
        $year = date('Y');
        $week = date('W');

        $start_date = strtotime($year . "W" . $week . 1);
        $end_date = strtotime($year . "W" . $week . 7);

        $ngaybd = date('Y-m-d', $start_date);
        $ngaykt = date('Y-m-d', $end_date);

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

        // cap chu tich, pho chu tich nhan van ban
        $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $user->id)
            ->whereNull('status')
            ->whereNull('hoan_thanh')
            ->get();
        $vanBanChoXuLy = VanBanDen::whereHas('vanBanLanhDao')
            ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
            ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
            ->count();

        $giayMoiChoXuLy = VanBanDen::whereHas('vanBanLanhDao')
            ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
            ->where('loai_van_ban_id', $loaiVanBanGiayMoi->id)
            ->count();

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {

//            $xuLyVanBanDen = DonViChuTri::where('don_vi_id', $user->don_vi_id)
//                ->where('can_bo_nhan_id', $user->id)
//                ->select('id', 'van_ban_den_id')
//                ->whereNotNull('vao_so_van_ban')
//                ->whereNull('hoan_thanh')
//                ->select('id', 'van_ban_den_id')
//                ->get();
            $vanBanChoXuLy = VanBanDen::whereHas('vanBanCapXa')
                ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
                ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
                ->count();

            $giayMoiChoXuLy = VanBanDen::whereHas('vanBanCapXa')
                ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
                ->where('loai_van_ban_id', $loaiVanBanGiayMoi->id)
                ->count();
        }

//        $arrIdVanBanDenDonVi = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        $vanBanChoYKien = LanhDaoChiDao::where('lanh_dao_id', auth::user()->id)
            ->whereNull('trang_thai')
            ->count();
        array_push($hoSoCongViecPiceCharts, array('Văn bản chờ ý kiến', $vanBanChoYKien));
        array_push($hoSoCongViecCoLors, COLOR_GREEN);



        if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, PHO_PHONG, PHO_CHANH_VAN_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN])) {

//          *  $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
//                ->where('can_bo_nhan_id', $user->id)
//                ->whereNotNull('vao_so_van_ban')
//                ->whereNull('hoan_thanh')
//                ->get();
//
//            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

            $vanBanChoXuLy = VanBanDen::whereHas('vanBanPhong')
                ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
                ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
                ->count();
            $giayMoiChoXuLy = VanBanDen::whereHas('vanBanPhong')
                ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
                ->where('loai_van_ban_id', $loaiVanBanGiayMoi->id)
                ->count();

            // VAN BAN HOAN THANH CHO DUYET
            if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, PHO_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {
                $duyetVanBanCapDuoiTrinh = GiaiQuyetVanBan::where('can_bo_duyet_id', $user->id)
                    ->whereHas('vanBanDenDen')
                    ->whereNull('status')->count();

                $duyetGiayMoiCapDuoiTrinh = GiaiQuyetVanBan::where('can_bo_duyet_id', $user->id)
                    ->whereHas('giayMoiDen')
                    ->whereNull('status')->count();


                array_push($hoSoCongViecPiceCharts, array('Duyệt VB cấp dưới trình', $duyetVanBanCapDuoiTrinh));
                array_push($giayMoiPiceCharts, array('Duyệt GM cấp dưới trình', $duyetGiayMoiCapDuoiTrinh));
                array_push($hoSoCongViecCoLors, COLOR_PURPLE);
                array_push($giayMoiCoLors, COLOR_PURPLE);
            }

            //VB DON VI PHOI HOP
            $chuyenTiep = null;

            $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->whereHas('vanBanDenDen', function ($q)  {
                    return $q->where('trinh_tu_nhan_van_ban', '!=',VanBanDen::HOAN_THANH_VAN_BAN);
                })
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


            $donViPhoiHopGM = DonViPhoiHop::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->whereHas('giayMoiDen', function ($q)  {
                    return $q->where('trinh_tu_nhan_van_ban', '!=',VanBanDen::HOAN_THANH_VAN_BAN);
                })
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

            array_push($hoSoCongViecPiceCharts, array('VB đơn vị phối hợp chờ xử lý', $donViPhoiHop));
            array_push($giayMoiPiceCharts, array('GM đơn vị phối hợp chờ xử lý', $donViPhoiHopGM));
            array_push($hoSoCongViecCoLors, COLOR_PRIMARY);
            array_push($giayMoiCoLors, COLOR_PRIMARY);

            //CHUYEN VIEN PHOI HOP
            if ($user->hasRole(CHUYEN_VIEN)) {

                $chuyenVienPhoiHop = ChuyenVienPhoiHop::where('can_bo_nhan_id', $user->id)
                    ->whereHas('vanBanDenDen')
                    ->whereNull('status')
                    ->count();
                $chuyenVienPhoiHopGM = ChuyenVienPhoiHop::where('can_bo_nhan_id', $user->id)
                    ->whereHas('giayMoiDen')
                    ->whereNull('status')
                    ->count();

                array_push($hoSoCongViecPiceCharts, array('VB chuyên viên phối hợp chờ xử lý', $chuyenVienPhoiHop));
                array_push($giayMoiPiceCharts, array('GM chuyên viên phối hợp chờ xử lý', $chuyenVienPhoiHopGM));
                array_push($hoSoCongViecCoLors, COLOR_GREEN);
                array_push($giayMoiCoLors, COLOR_GREEN);

            }

            // PHAN LOAI VAN BAN (Chanh Van Phong)
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
//                        ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
                        ->count();
                    $giayMoiChoPhanLoai = VanBanDen::whereIn('id', $arrVanBanDenId)
                        ->where('trinh_tu_nhan_van_ban', VanBanDen::THAM_MUU_CHI_CUC_NHAN_VB)
                        ->where('loai_van_ban_id',$loaiVanBanGiayMoi->id)
                        ->count();

                    //phan loai van ban don vi phoi hop
                    $vanBanPhoiHopChoPhanLoai = DonViPhoiHop::where('don_vi_id', $donVi->parent_id)
                        ->whereHas('vanBanDenDen')
                        ->where('can_bo_nhan_id', $user->id)
                        ->whereNull('chuyen_tiep')
                        ->where('active', DonViPhoiHop::ACTIVE)
                        ->whereNull('hoan_thanh')
//                        ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
                        ->whereNotNull('vao_so_van_ban')
                        ->count();
                    $giayMoiPhoiHopChoPhanLoai = DonViPhoiHop::where('don_vi_id', $donVi->parent_id)
                        ->whereHas('giayMoiDen')
                        ->where('can_bo_nhan_id', $user->id)
                        ->whereNull('chuyen_tiep')
                        ->where('active', DonViPhoiHop::ACTIVE)
                        ->whereNull('hoan_thanh')
//                        ->where('loai_van_ban_id',$loaiVanBanGiayMoi->id)
                        ->whereNotNull('vao_so_van_ban')
                        ->count();

                    array_push($hoSoCongViecPiceCharts, array('VB phối hợp chờ phân loại', $vanBanPhoiHopChoPhanLoai));
                    array_push($hoSoCongViecCoLors, COLOR_GREEN_LIGHT);
                    array_push($giayMoiPiceCharts, array('GM phối hợp chờ phân loại', $giayMoiPhoiHopChoPhanLoai));
                    array_push($giayMoiCoLors, COLOR_GREEN_LIGHT);

                } else {

                    $vanBanChoPhanLoai = VanBanDen::
                    where('lanh_dao_tham_muu', $user->id)->
                        whereNull('trinh_tu_nhan_van_ban')
                        ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
                        ->count();
                    $giayMoiChoPhanLoai = VanBanDen::
                    where('lanh_dao_tham_muu', $user->id)->
                    whereNull('trinh_tu_nhan_van_ban')
                        ->where('loai_van_ban_id',$loaiVanBanGiayMoi->id)
                        ->count();
                }

                array_push($hoSoCongViecPiceCharts, array('VB chờ phân loại', $vanBanChoPhanLoai));
                array_push($hoSoCongViecCoLors, COLOR_GREEN);
                array_push($giayMoiPiceCharts, array('GM chờ phân loại', $giayMoiChoPhanLoai));
                array_push($giayMoiCoLors, COLOR_GREEN);
            }
            // CONG VIEC DON VI
            $congViecDonViChoXuLy = ChuyenNhanCongViecDonVi::where('can_bo_nhan_id', $user->id)
                ->whereNull('type')
                ->whereNull('chuyen_tiep')
                ->orWhere('chuyen_tiep', 0)
                ->whereNull('hoan_thanh')
                ->count();

            array_push($congViecPhongBanPiceCharts, array('CV chờ xử lý', $congViecDonViChoXuLy));
            array_push($congViecPhongBanCoLors, COLOR_ORANGE);

            if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, PHO_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {
                $giaHanCongViecDonVi = CongViecDonViGiaHan::where('can_bo_nhan_id', $user->id)
                    ->where('status', CongViecDonViGiaHan::STATUS_CHO_DUYET)
                    ->count();
                array_push($congViecPhongBanPiceCharts, array('CV xin gia hạn', $giaHanCongViecDonVi));
                array_push($congViecPhongBanCoLors, COLOR_PINTEREST);

                $congViecDonViHoanThanhChoDuyet = GiaiQuyetCongViecDonVi::where('lanh_dao_duyet_id', $user->id)
                    ->whereNull('status')
                    ->count();

                array_push($congViecPhongBanPiceCharts, array('CV hoàn thành chờ duyệt', $congViecDonViHoanThanhChoDuyet));
                array_push($congViecPhongBanCoLors, COLOR_PURPLE);

                //cv don vi phoi hop
                $congViecDonViPhoiHopChoXuLy = ChuyenNhanCongViecDonVi::where('can_bo_nhan_id', $user->id)
                    ->where('type', ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP)
                    ->whereNull('chuyen_tiep')
                    ->whereNull('hoan_thanh')
                    ->count();

                array_push($congViecPhongBanPiceCharts, array('CV đơn vị phối hợp chờ xử lý', $congViecDonViPhoiHopChoXuLy));
                array_push($congViecPhongBanCoLors, COLOR_PRIMARY);

                // cv phoi hop
                if ($user->hasRole(CHUYEN_VIEN)) {

                    $congViecChuyenVienPhoiHopChoXuLy = CongViecDonViPhoiHop::where('can_bo_nhan_id', $user->id)
                        ->whereNull('status')
                        ->whereNull('type')->count();

                    array_push($congViecPhongBanPiceCharts, array('CV chuyên viên phối hợp chờ xử lý', $congViecChuyenVienPhoiHopChoXuLy));
                    array_push($congViecPhongBanCoLors, COLOR_RED);

                    $congViecChuyenVienDaXuLy = ChuyenNhanCongViecDonVi::where('can_bo_nhan_id', $user->id)
                        ->whereNull('type')
                        ->where('chuyen_tiep', ChuyenNhanCongViecDonVi::GIAI_QUYET)
                        ->whereNull('hoan_thanh')
                        ->count();

                    array_push($congViecPhongBanPiceCharts, array('CV chuyên viên đã xử lý', $congViecChuyenVienDaXuLy));
                    array_push($congViecPhongBanCoLors, COLOR_PURPLE);
                }

                // van ban xem de biet
                if ($user->hasRole([PHO_PHONG, PHO_CHANH_VAN_PHONG, PHO_TRUONG_BAN])) {

                    $vanBanXemDeBiet = LanhDaoXemDeBiet::where('lanh_dao_id', $user->id)
                        ->whereHas('vanBanDenDen')
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->select('van_ban_den_id')
                        ->distinct('van_ban_den_id')
                        ->count();

                    $giayMoiXemDeBiet = LanhDaoXemDeBiet::where('lanh_dao_id', $user->id)
                        ->whereHas('giayMoiDen')
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->select('van_ban_den_id')
                        ->distinct('van_ban_den_id')
                        ->count();


                    array_push($hoSoCongViecPiceCharts, array('VB xem để biết', $vanBanXemDeBiet));
                    array_push($giayMoiPiceCharts, array('GM xem để biết', $giayMoiXemDeBiet));
                    array_push($hoSoCongViecCoLors, COLOR_INFO);
                    array_push($giayMoiCoLors, COLOR_INFO);
                }
            }
        }

        $vanBanXinGiaHan = GiaHanVanBan::where('can_bo_nhan_id', $user->id)
            ->whereHas('vanBanDenDen')
            ->where('status', GiaHanVanBan::STATUS_CHO_DUYET)
            ->count();
        $giayMoiXinGiaHan = GiaHanVanBan::where('can_bo_nhan_id', $user->id)
            ->whereHas('giayMoiDen')
            ->where('status', GiaHanVanBan::STATUS_CHO_DUYET)
            ->count();

        if ($user->hasRole([CHU_TICH])) {
            $giayMoiChoPhanLoai = VanBanDen::where('lanh_dao_tham_muu', 10551)->
            whereNull('trinh_tu_nhan_van_ban')
                ->where('loai_van_ban_id',$loaiVanBanGiayMoi->id)
                ->count();
            array_push($hoSoCongViecPiceCharts, array('VB chờ phân loại', $vanBanChoPhanLoai));
            array_push($hoSoCongViecCoLors, COLOR_GREEN);
        }
        if ($user->hasRole([CHU_TICH, PHO_CHU_TICH, TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {

            $vanBanQuanTrong = VanBanQuanTrong::where('user_id', $user->id)
                ->whereHas('vanBanDenDen')
                ->count();
            $giayMoiQuanTrong = VanBanQuanTrong::where('user_id', $user->id)
                ->whereHas('giayMoiDen')
                ->count();

            array_push($hoSoCongViecPiceCharts, array('VB quan trọng', $vanBanQuanTrong));
            array_push($hoSoCongViecCoLors, COLOR_PRIMARY);
            array_push($giayMoiPiceCharts, array('GM quan trọng', $giayMoiQuanTrong));
            array_push($giayMoiCoLors, COLOR_PRIMARY);


            $lichCongTac = LichCongTac::where('ngay', '>=', $ngaybd)
                ->where('ngay', '<=', $ngaykt)
                ->where('lanh_dao_id', $user->id)
                ->whereNotNull('trang_thai')
                ->count();

            array_push($hoSoCongViecPiceCharts, array('Lịch công tác', $lichCongTac));
            array_push($giayMoiPiceCharts, array('Lịch công tác', $lichCongTac));
            array_push($hoSoCongViecCoLors, COLOR_BLUE_DARK);
            array_push($giayMoiCoLors, COLOR_BLUE_DARK);

            if ($user->hasRole([CHU_TICH, PHO_CHU_TICH])) {
                $vanBanXemDeBiet = LanhDaoXemDeBiet::where('lanh_dao_id', $user->id)
                    ->whereHas('vanBanDenDen')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->orderBy('id', 'DESC')
                    ->count();
                $giayMoiXemDeBiet = LanhDaoXemDeBiet::where('lanh_dao_id', $user->id)
                    ->whereHas('giayMoiDen')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->orderBy('id', 'DESC')
                    ->count();

                array_push($hoSoCongViecPiceCharts, array('VB xem để biết', $vanBanXemDeBiet));
                array_push($giayMoiPiceCharts, array('GM xem để biết', $giayMoiXemDeBiet));
                array_push($hoSoCongViecCoLors, COLOR_INFO);
                array_push($giayMoiCoLors, COLOR_INFO);

                if ($user->donVi->cap_xa == DonVi::CAP_XA) {
                    //VB DON VI PHOI HOP
                    $chuyenTiep = null;

                    $donViPhoiHop = DonViPhoiHop::where('don_vi_id', $user->don_vi_id)
                        ->where('can_bo_nhan_id', $user->id)
                        ->whereHas('vanBanDenDen')
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
                    $donViPhoiHopGM = DonViPhoiHop::where('don_vi_id', $user->don_vi_id)
                        ->where('can_bo_nhan_id', $user->id)
                        ->whereHas('giayMoiDen')
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

                    array_push($hoSoCongViecPiceCharts, array('VB đơn vị phối hợp chờ xử lý', $donViPhoiHop));
                    array_push($giayMoiPiceCharts, array('GM đơn vị phối hợp chờ xử lý', $donViPhoiHopGM));
                    array_push($hoSoCongViecCoLors, COLOR_PURPLE);
                    array_push($giayMoiCoLors, COLOR_PURPLE);
                }

            }
        }

        //$vanThuVanBanDenPiceCharts
        array_push($hoSoCongViecPiceCharts, array('VB chờ xử lý', $vanBanChoXuLy));
        array_push($hoSoCongViecPiceCharts, array('VB xin gia hạn', $vanBanXinGiaHan));
        array_push($giayMoiPiceCharts, array('GM xin gia hạn', $giayMoiXinGiaHan));
        //giaymoi
        array_push($giayMoiPiceCharts, array('GM chờ xử lý', $giayMoiChoXuLy));
        array_push($giayMoiCoLors, COLOR_PINTEREST);
        array_push($giayMoiCoLors, COLOR_ORANGE);



        //màu

        array_push($hoSoCongViecCoLors, COLOR_ORANGE);
        array_push($hoSoCongViecCoLors, COLOR_PINTEREST);

        //VB DANG XU LY QUA HAN
        if ($user->hasRole([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN])) {
            $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $user->id)
                ->whereNull('status')
                ->whereNull('hoan_thanh')
                ->get();

            $arrVanBanDenId = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();


            if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, CHUYEN_VIEN, PHO_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN])) {

                $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
                    ->where('can_bo_nhan_id', $user->id)
                    ->whereNotNull('vao_so_van_ban')
                    ->whereNull('hoan_thanh')
                    ->get();

                $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

                $vanBanQuaHanDangXuLy = VanBanDen::whereHas('vanBanQuaHanPhong')
                    ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
                    ->where(function ($query) use ($currentDate) {
                        return $query->where('han_xu_ly', '<', $currentDate);
                    })
                    ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
                    ->count();
                $giayMoiQuaHanDangXuLy = VanBanDen::whereHas('vanBanQuaHanPhong')
                    ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
                    ->where(function ($query) use ($currentDate) {
                        return $query->where('han_xu_ly', '<', $currentDate);
                    })
                    ->where('loai_van_ban_id',$loaiVanBanGiayMoi->id)
                    ->count();
            }

            if ($donVi->cap_xa = DonVi::CAP_XA) {

                $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
                    ->where('can_bo_nhan_id', $user->id)
                    ->whereNotNull('vao_so_van_ban')
                    ->whereNull('hoan_thanh')
                    ->select('van_ban_den_id')
                    ->get();

                $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

                $vanBanQuaHanDangXuLy = VanBanDen::whereHas('vanBanQuaHanCapXa')
                    ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
                    ->where(function ($query) use ($currentDate) {
                        return $query->where('han_xu_ly', '<', $currentDate);
                    })
                    ->where('loai_van_ban_id', '!=',$loaiVanBanGiayMoi->id)
                    ->count();
                $giayMoiQuaHanDangXuLy = VanBanDen::whereHas('vanBanQuaHanCapXa')
                    ->where('trinh_tu_nhan_van_ban', '>=', $trinhTuNhanVanBan)
                    ->where(function ($query) use ($currentDate) {
                        return $query->where('han_xu_ly', '<', $currentDate);
                    })
                    ->where('loai_van_ban_id',$loaiVanBanGiayMoi->id)
                    ->count();
            }



            array_push($hoSoCongViecPiceCharts, array('VB quá hạn đang xử lý', $vanBanQuaHanDangXuLy));
            array_push($hoSoCongViecCoLors, COLOR_YELLOW);
            array_push($giayMoiPiceCharts, array('GM quá hạn đang xử lý', $giayMoiQuaHanDangXuLy));
            array_push($giayMoiCoLors, COLOR_YELLOW);
        }

        // tham du cuoc hop
        $danhSachthamDuCuocHop = ThanhPhanDuHop::where('user_id', $user->id)
            ->whereNull('lanh_dao_id')
            ->select('lich_cong_tac_id')
            ->get();
        $lichConTacId = $danhSachthamDuCuocHop->pluck('lich_cong_tac_id');
        $thamDuCuocHop = LichCongTac::whereIn('id', $lichConTacId)
            ->where('ngay', '>=', $ngaybd)
            ->where('ngay', '<=', $ngaykt)
            ->count();

        array_push($hoSoCongViecPiceCharts, array('Tham dự cuộc họp', $thamDuCuocHop));
        array_push($hoSoCongViecCoLors, COLOR_LIGHT_PINK);
        array_push($giayMoiPiceCharts, array('Tham dự cuộc họp', $thamDuCuocHop));
        array_push($giayMoiCoLors, COLOR_LIGHT_PINK);

        return view('admin::index',
            compact(
                'vanBanPhoiHopChoPhanLoai',
                'danhSachDuThao',
                'danhSachVanBanDen',
                'vanBanDenDonViChoVaoSo',
                'vanBanDenTraLai',
                'homThuCong',
                'vanBanTuDonViGui',
                'giayMoiDen',
                'giayMoiChoXuLy',
                'giayMoiDi',
                'vanBanDi',
                'vanBanDiChoSo',
                'vanbandichoduyet',
                'vanThuVanBanDiPiceCharts',
                'vanThuVanBanDiCoLors',
                'van_ban_di_tra_lai',
                'vanThuVanBanDenCoLors',
                'vanThuVanBanDenPiceCharts',
                'gopy',
                'duThaoPiceCharts',
                'duThaoCoLors',
                'hoSoCongViecPiceCharts',
                'hoSoCongViecCoLors',
                'vanBanChoXuLy',
                'vanBanXinGiaHan',
                'vanBanQuanTrong',
                'vanBanXemDeBiet',
                'duyetVanBanCapDuoiTrinh',
                'donViPhoiHop',
                'chuyenVienPhoiHop',
                'vanBanChoPhanLoai',
                'vanBanQuaHanDangXuLy',
                'lichCongTac',
                'congViecPhongBanPiceCharts',
                'congViecPhongBanCoLors',
                'congViecDonViChoXuLy',
                'giaHanCongViecDonVi',
                'congViecDonViHoanThanhChoDuyet',
                'congViecDonViPhoiHopChoXuLy',
                'congViecChuyenVienPhoiHopChoXuLy',
                'congViecChuyenVienDaXuLy',
                'thamDuCuocHop',
                'giayMoiCoLors',
                'giayMoiPiceCharts',
                'giayMoiXinGiaHan',
                'giayMoiXemDeBiet',
                'duyetGiayMoiCapDuoiTrinh',
                'chuyenVienPhoiHopGM',
                'donViPhoiHopGM',
                'giayMoiQuanTrong',
                'vanBanChoYKien',
                'giayMoiQuaHanDangXuLy',
                'giayMoiChoPhanLoai',
                'giayMoiPhoiHopChoPhanLoai'
            ));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function createBackup()
    {
        try {
            // start the backup process
            Artisan::call('backup:run --only-db');
            $output = Artisan::output();
            // log the results
            Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);
            // return the results as a response to the ajax call
            return redirect()->back()->with('success', 'Tạo mới sao lưu thành công.');
        } catch (Exception $e) {
            dd($e);
            return redirect()->back();
        }
    }

    public function exportDatabase()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];
        foreach ($files as $k => $f) {

            // only take the zip files into account
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $f),
                    'file_size' => $disk->size($f),
                    'last_modified' => $disk->lastModified($f),
                ];
            }
        }

        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);

        return view('admin::backup.index', compact('backups'));
    }

    public function downloadBackup($file_name)
    {
        $file = config('backup.backup.name') . '/' . $file_name;

        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists($file)) {
            $fs = Storage::disk(config('backup.backup.destination.disks')[0])->getDriver();
            $stream = $fs->readStream($file);

            return \Response::stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $fs->getMimetype($file),
                "Content-Length" => $fs->getSize($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    public function deleteBackup($file_name)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists(config('backup.backup.name') . '/' . $file_name)) {
            $disk->delete(config('backup.backup.name') . '/' . $file_name);
            return redirect()->back()->with('success', "Đã xoá sao lưu dữ liệu!");
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
}
