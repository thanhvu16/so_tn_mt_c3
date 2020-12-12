<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\GiaHanVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
use Modules\DieuHanhVanBanDen\Entities\VanBanQuanTrong;
use Modules\LayVanBanTuEmail\Entities\GetEmail;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use Modules\VanBanDi\Entities\Duthaovanbandi;
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


    public function index()
    {
        $vanThuVanBanDiPiceCharts = [];
        $vanThuVanBanDenPiceCharts = [];
        $vanThuVanBanDiCoLors = [];
        $vanThuVanBanDenCoLors = [];
        $duThaoCoLors = [];
        $duThaoPiceCharts = [];
        $user = auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('nguoi-dung.index');
        }
        $danhSachDuThao = Duthaovanbandi::where(['nguoi_tao' => auth::user()->id, 'stt' => 1])->count();
        $vanbandichoduyet = Vanbandichoduyet::where(['can_bo_nhan_id' => auth::user()->id, 'trang_thai' => 1])->count();
        $ds_giaymoiden = VanBanDen::where(['don_vi_id' => auth::user()->don_vi_id, 'so_van_ban_id' => 100])->count();
        $van_ban_di_tra_lai = Vanbandichoduyet::where(['can_bo_nhan_id' => auth::user()->id, 'trang_thai' => 0, 'tra_lai' => 1])->count();
        $canbogopy = CanBoPhongDuThao::where(['can_bo_id' => auth::user()->id, 'trang_thai' => 1])->get();
        $key2 = count($canbogopy);
        $canbogopyngoai = CanBoPhongDuThaoKhac::where(['can_bo_id' => auth::user()->id, 'trang_thai' => 1])->get();
        $key1 = count($canbogopyngoai);
        $gopy = $key2+$key1;

        //văn bản đến
        array_push($vanThuVanBanDenPiceCharts, array('Task', 'Danh sách'));
        array_push($vanThuVanBanDiPiceCharts, array('Task', 'Danh sách'));

        $homThuCong = 0;
        $danhSachVanBanDen = 0;
        $vanBanDenDonViChoVaoSo = 0;
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

            $giayMoiDen = VanBanDen::where('so_van_ban_id', '=', $giayMoi->id ?? null)
                ->where('type', VanBanDen::TYPE_VB_HUYEN)
                ->whereNull('deleted_at')
                ->count();

            array_push($vanThuVanBanDenPiceCharts, array('Danh sách giấy mời đến', $giayMoiDen));
            array_push($vanThuVanBanDenCoLors, COLOR_GREEN);

            //van ban di
            $giayMoiDi = VanBanDi::where([
                'loai_van_ban_giay_moi' => 2,
                'loai_van_ban_id' => $giayMoi->id ?? null
                ])
                ->whereNotNull('so_di')
                ->whereNull('deleted_at')->count();

            array_push($vanThuVanBanDiPiceCharts, array('Danh sách giấy mời đi', $giayMoiDi));
            array_push($vanThuVanBanDiCoLors, COLOR_RED);

            $vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi'=> 1, 'don_vi_soan_thao'=> null])
                ->where('so_di', '!=', null)->whereNull('deleted_at')
                ->count();

            array_push($vanThuVanBanDiPiceCharts, array('Danh sách văn bản đi', $vanBanDi));
            array_push($vanThuVanBanDiCoLors, COLOR_PRIMARY);

            $vanBanDiChoSo = VanBanDi::where(['cho_cap_so' => 2,'don_vi_soan_thao'=> null])
                ->count();

            array_push($vanThuVanBanDiPiceCharts, array('Văn bản đi chờ số', $vanBanDiChoSo));
            array_push($vanThuVanBanDiCoLors, COLOR_WARNING);
        }

        if ($user->hasRole(VAN_THU_DON_VI)) {

            $danhSachVanBanDen = VanBanDen::where('so_van_ban_id', '!=', $giayMoi->id ?? null)
                ->where('type', VanBanDen::TYPE_VB_DON_VI)
                ->where('don_vi_id', $user->don_vi_id)
                ->whereNull('deleted_at')
                ->count();

            array_push($vanThuVanBanDenPiceCharts, array('Danh sách văn bản đến', $danhSachVanBanDen));
            array_push($vanThuVanBanDenCoLors, COLOR_PINTEREST);

            $vanBanDenDonViChoVaoSo = DonViChuTri::where(['don_vi_id' => $user->don_vi_id])
                ->whereNull('vao_so_van_ban')
                ->whereNull('parent_id')
                ->whereNull('type')
                ->count();

            array_push($vanThuVanBanDenPiceCharts, array('Văn bản đến chờ vào sổ', $vanBanDenDonViChoVaoSo));
            array_push($vanThuVanBanDenCoLors, COLOR_WARNING);

            $giayMoiDen = VanBanDen::where('so_van_ban_id', '=', $giayMoi->id ?? null)
                ->where('type', VanBanDen::TYPE_VB_DON_VI)
                ->where('don_vi_id', $user->don_vi_id)
                ->whereNull('deleted_at')
                ->count();

            array_push($vanThuVanBanDenPiceCharts, array('Danh sách giấy mời đến', $giayMoiDen));
            array_push($vanThuVanBanDenCoLors, COLOR_GREEN);

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

            $vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi'=> 1, 'don_vi_soan_thao'=> $user->don_vi_id])
                ->where('so_di', '!=', null)->whereNull('deleted_at')
                ->count();

            array_push($vanThuVanBanDiPiceCharts, array('Danh sách văn bản đi', $vanBanDi));
            array_push($vanThuVanBanDiCoLors, COLOR_PRIMARY);

            $vanBanDiChoSo = VanBanDi::where(['cho_cap_so' => 2,'don_vi_soan_thao'=> $user->don_vi_id])
                ->count();

            array_push($vanThuVanBanDiPiceCharts, array('Văn bản đi chờ số', $vanBanDiChoSo));
            array_push($vanThuVanBanDiCoLors, COLOR_WARNING);

        }

        //dự thảo văn bản đi
        array_push($duThaoCoLors, COLOR_WARNING);
        array_push($duThaoCoLors, COLOR_INFO);
        array_push($duThaoCoLors, COLOR_GREEN);
        array_push($duThaoCoLors, COLOR_PINTEREST);
        //màu
        array_push($duThaoPiceCharts, array('Task', 'Danh sách'));
        array_push($duThaoPiceCharts, array('Danh sách cá nhân dự thảo',$danhSachDuThao));
        array_push($duThaoPiceCharts, array('dự thảo chờ góp ý',$gopy));
        array_push($duThaoPiceCharts, array('Danh sách văn bản đi chờ duyệt', $vanbandichoduyet));
        array_push($duThaoPiceCharts, array('Danh sách văn bản trả lại', $van_ban_di_tra_lai));

//        array_push($duThaoPiceCharts, array('Task', 'Danh sách'));
//        array_push($duThaoPiceCharts, array('Danh sách cá nhân dự thảo', $danhSachDuThao));
//        array_push($duThaoPiceCharts, array('dự thảo chờ góp ý', $gopy));
//        array_push($duThaoPiceCharts, array('Danh sách văn bản đi chờ duyệt', $vanbandichoduyet));
//        array_push($duThaoPiceCharts, array('Danh sách văn bản trả lại', $van_ban_di_tra_lai));

        // ho so cong viec
        $hoSoCongViecPiceCharts = [];
        $hoSoCongViecCoLors = [];
        $active = 0;
        $vanBanQuanTrong = 0;
        $vanBanChoXuLy = 0;
        $vanBanXinGiaHan = 0;
        $vanBanHoanThanhChoDuyet = 0;
        $donViPhoiHop = 0;
        $chuyenVienPhoiHop = 0;
        $vanBanChoPhanLoai = 0;
        array_push($hoSoCongViecPiceCharts, array('Task', 'Danh sách'));


        if ($user->hasRole(CHU_TICH)) {
            $active = VanBanDen::CHU_TICH_NHAN_VB;
        } else {
            $active = VanBanDen::PHO_CHU_TICH_NHAN_VB;
        }
        $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $user->id)
            ->whereNull('status')
            ->whereNull('hoan_thanh')
            ->get();

        $arrIdVanBanDenDonVi = $xuLyVanBanDen->pluck('van_ban_den_id')->toArray();

        $vanBanChoXuLy = VanBanDen::with('lanhDaoXemDeBiet', 'checkLuuVetVanBanDen', 'nguoiDung', 'vanBanTraLai')
            ->whereIn('id', $arrIdVanBanDenDonVi)
            ->where('trinh_tu_nhan_van_ban', $active)
            ->count();

        if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, PHO_PHONG, PHO_CHANH_VAN_PHONG, CHUYEN_VIEN])) {

            $trinhTuNhanVanBan = null;

            if ($user->hasRole(TRUONG_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                $trinhTuNhanVanBan = 3;
            }

            if ($user->hasRole(PHO_PHONG) || $user->hasRole(PHO_CHANH_VAN_PHONG)) {
                $trinhTuNhanVanBan = 4;
            }

            if ($user->hasRole(CHUYEN_VIEN)) {
                $trinhTuNhanVanBan = 5;
            }

            $donViChuTri = DonViChuTri::where('don_vi_id', $user->don_vi_id)
                ->where('can_bo_nhan_id', $user->id)
                ->whereNotNull('vao_so_van_ban')
                ->whereNull('hoan_thanh')
                ->get();

            $arrVanBanDenId = $donViChuTri->pluck('van_ban_den_id')->toArray();

            $vanBanChoXuLy = VanBanDen::with('donViChuTri', 'xuLyVanBanDen')->whereIn('id', $arrVanBanDenId)
                ->where('trinh_tu_nhan_van_ban', $trinhTuNhanVanBan)
                ->count();

            // VAN BAN HOAN THANH CHO DUYET
            if ($user->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG])) {
                $vanBanHoanThanhChoDuyet = GiaiQuyetVanBan::where('can_bo_duyet_id', $user->id)
                    ->whereNull('status')->count();


            } else {
                $vanBanHoanThanhChoDuyet = GiaiQuyetVanBan::where('user_id', $user->id)
                    ->whereNull('status')->count();

            }

            array_push($hoSoCongViecPiceCharts, array('VB hoàn thành chờ duyệt', $vanBanHoanThanhChoDuyet));
            array_push($hoSoCongViecCoLors, COLOR_PURPLE);

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
                ->whereNull('hoan_thanh')
                ->count();

            array_push($hoSoCongViecPiceCharts, array('VB đơn vị phối hợp chờ xử lý', $donViPhoiHop));
            array_push($hoSoCongViecCoLors, COLOR_PRIMARY);

            //CHUYEN VIEN PHOI HOP
            if ($user->hasRole(CHUYEN_VIEN)) {

                $chuyenVienPhoiHop = ChuyenVienPhoiHop::where('can_bo_nhan_id', $user->id)
                    ->whereNull('status')->count();

                array_push($hoSoCongViecPiceCharts, array('VB chuyên viên phối hợp chờ xử lý', $chuyenVienPhoiHop));
                array_push($hoSoCongViecCoLors, COLOR_GREEN);

            }

            // PHAN LOAI VAN BAN
            if ($user->hasRole(CHANH_VAN_PHONG)) {
                $vanBanChoPhanLoai = VanBanDen::where('lanh_dao_tham_muu', $user->id)
                    ->whereNull('trinh_tu_nhan_van_ban')
                    ->count();

                array_push($hoSoCongViecPiceCharts, array('VB chờ phân loại', $vanBanChoPhanLoai));
                array_push($hoSoCongViecCoLors, COLOR_GREEN);
            }

        }

        $vanBanXinGiaHan = GiaHanVanBan::with('vanBanDen',
            'CanBoChuyen', 'CanBoNhan')
            ->where('can_bo_nhan_id', $user->id)
            ->where('status', GiaHanVanBan::STATUS_CHO_DUYET)
            ->count();

        if ($user->hasRole([CHU_TICH, PHO_CHUC_TICH])) {

            $vanBanQuanTrong = VanBanQuanTrong::where('user_id', $user->id)
                ->count();

            array_push($hoSoCongViecPiceCharts, array('VB quan trọng', $vanBanQuanTrong));
            array_push($hoSoCongViecCoLors, COLOR_PRIMARY);
        }



        //$vanThuVanBanDenPiceCharts
        array_push($hoSoCongViecPiceCharts, array('VB chờ xử lý', $vanBanChoXuLy));
        array_push($hoSoCongViecPiceCharts, array('VB xin gia hạn', $vanBanXinGiaHan));

        //màu
        array_push($hoSoCongViecCoLors, COLOR_ORANGE);
        array_push($hoSoCongViecCoLors, COLOR_PINTEREST);



        return view('admin::index',compact(
//            'getEmail' => $getEmail,
            'danhSachDuThao' ,
            'danhSachVanBanDen',
            'vanBanDenDonViChoVaoSo',
            'homThuCong' ,
            'giayMoiDen',
            'giayMoiDi',
            'vanBanDi',
            'vanBanDiChoSo',
            'vanbandichoduyet' ,
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
            'vanBanHoanThanhChoDuyet',
            'donViPhoiHop',
            'chuyenVienPhoiHop',
            'vanBanChoPhanLoai'
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
}
