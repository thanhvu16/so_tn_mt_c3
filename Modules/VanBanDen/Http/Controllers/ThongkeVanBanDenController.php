<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Exports\thongKeVanBanDenExport;
use App\Exports\thongKeVanBanDenGiaiQuyetExport;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Excel, PDF, DB;
use App\Exports\VanbandenExport;
use App\Exports\thongKeVanBanSoExport;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\VanBanDen\Entities\VanBanDen;
use function GuzzleHttp\Promise\all;

class ThongkeVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $id = (int)$request->get('id');
        $sovanban = SoVanBan::whereNull('deleted_at')->whereIn('loai_so', [1, 3])->get();
        $timloaiso = $request->get('so_van_ban');
        $vanbanden = null;
        $user = auth::user();
        if ($id) {
            $vanbanden = VanBanDen::where('id', $id)->first();
        }
        if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH)) {
            $ds_vanBanDen = VanBanDen::where([
                'type' => 1])->where('so_van_ban_id', '!=', 100)->whereNull('deleted_at')
                ->where(function ($query) use ($timloaiso) {
                    if (!empty($timloaiso)) {
                        return $query->where('so_van_ban_id', $timloaiso);
                    }
                })->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        } elseif ($user->hasRole(CHUYEN_VIEN) || $user->hasRole(PHO_PHONG) || $user->hasRole(TRUONG_PHONG) ||
            $user->hasRole(VAN_THU_DON_VI) ||
            $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
            $ds_vanBanDen = VanBanDen::where([
                'don_vi_id' => auth::user()->don_vi_id,
                'type' => 2])
                ->where('so_van_ban_id', '!=', 100)->whereNull('deleted_at')
                ->where(function ($query) use ($timloaiso) {
                    if (!empty($timloaiso)) {
                        return $query->where('so_van_ban_id', $timloaiso);
                    }
                })->orderBy('created_at', 'desc')->paginate(PER_PAGE);

        }

        $totalRecord = $ds_vanBanDen->count();

        if ($request->get('type') == 'pdf') {
            $fileName = 'in_so_van_ban_den' . date('d_m_Y') . '.pdf';

            $pdf = PDF::loadView('vanbanden::thong_ke.view_index', compact('vanbanden', 'ds_vanBanDen'));

            return $pdf->download($fileName)->header('Content-Type', 'application/pdf');
        }

        //export word
        if ($request->get('type') == 'word') {
            $fileName = 'in_so_van_ban_di' . date('d_m_Y') . '.doc';

            $headers = array(
                "Content-type" => "text/html",
                "Content-Disposition" => "attachment;Filename=" . $fileName
            );

            $content = view('vanbanden::thong_ke.view_index', compact('vanbanden', 'ds_vanBanDen'));


            return \Response::make($content, 200, $headers);

        }
        if ($request->get('type') == 'excel') {
            $fileName = 'in_so_van_ban_den' . date('d_m_Y') . '.xlsx';
            return Excel::download(new vanbandenExport($ds_vanBanDen, $totalRecord),
                $fileName);
        }


        if ($request->ajax()) {

            $html = view('vanbanden::thong_ke.view_index', compact('vanbanden', 'ds_vanbanden'))->render();;
            return response()->json([
                'html' => $html,
            ]);
        }


        return view('vanbanden::thong_ke.index', compact('vanbanden', 'ds_vanBanDen', 'sovanban'));
    }

    public function tongSoVanBanDen(Request $request)
    {

        $currentDate = date('Y-m-d');

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();
        $page = $request->get('page') ?? null;

        $loaiVanBan = $request->get('loai_van_ban_id') ?? null;
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        $ds_vanBanDen = VanBanDen::query()->whereNull('deleted_at')
            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->where(function ($query) use ($loaiVanBan) {
                if (!empty($loaiVanBan)) {
                    return $query->where('loai_van_ban_id', $loaiVanBan);
                }
            })
//            ->where(function ($query) use ($loaiVanBanGiayMoi) {
//                if (!empty($loaiVanBanGiayMoi)) {
//                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
//                }
//            })
            ->where('type', 1)
//            ->get();
            ->paginate(PER_PAGE,['*'],'page',$page);





        if ($request->get('type') == 'excel') {
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');
            $day = Carbon::now()->format('d');


            $totalRecord = $ds_vanBanDen->count();
            $fileName = 'thong_ke_van_ban_den_' . date('d_m_Y') . '.xlsx';

            return Excel::download(new thongKeVanBanDenExport($ds_vanBanDen, $totalRecord,
                $month, $year, $day),
                $fileName);
        }
        return view('vanbanden::chi-tiet-thong-ke.chi_tiet_tong_so_van_ban', compact('ds_vanBanDen'));
    }


    public function thongkevbso(Request $request)
    {
//        dd($request->all());
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        $vanThuNhap = auth::user()->id;
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->get();
        $loai_van_ban_id = $request->get('loai_van_ban_id') ?? null;
        $don_vi_xu_ly_chinh = $request->get('don_vi_xu_ly_chinh') ?? null;
        canPermission(AllPermission::thongKeVanBanSo());
        $lanhDaoSo = User::role(CHU_TICH)->whereNull('deleted_at')->first();

        $danhSachDonVisearch = DonVi::where(function ($query) use ($lanhDaoSo) {
            if (!empty($lanhDaoSo)) {
                return $query->where('id', '!=', $lanhDaoSo->don_vi_id);
            }
        })
            ->where('parent_id', DonVi::NO_PARENT_ID)
            ->whereNull('deleted_at')
            ->orderBy('thu_tu', 'asc')->get();
        $danhSachDonVi = DonVi::where(function ($query) use ($lanhDaoSo) {
            if (!empty($lanhDaoSo)) {
                return $query->where('id', '!=', $lanhDaoSo->don_vi_id);
            }
        })
            ->where(function ($query) use ($don_vi_xu_ly_chinh) {
                if (!empty($don_vi_xu_ly_chinh)) {
                    return $query->where('id', $don_vi_xu_ly_chinh);
                }
            })
            ->where('parent_id', DonVi::NO_PARENT_ID)
            ->whereNull('deleted_at')
            ->orderBy('thu_tu', 'asc')->get();

        foreach ($danhSachDonVi as $donVi) {
            $donVi->vanBanDaGiaiQuyet = $this->vanBanGiaiQuyet($donVi, $tu_ngay, $den_ngay, $vanThuNhap, $loai_van_ban_id);

        }
        $soDonvi = $danhSachDonVi->count();

        if ($request->get('type') == 'excel') {
            $tongSoVB = $request->sovanbanden;
            $fileName = 'thongkeVb' . date('d_m_Y') . '.xlsx';
            return Excel::download(new thongKeVanBanSoExport($danhSachDonVi, $soDonvi, $tongSoVB, $tu_ngay, $den_ngay),
                $fileName);
        }
        if ($request->ajax()) {
            $tongSoVB = $request->sovanbanden;
            $html = view('vanbanden::thong_ke.TK_vb_so', compact('danhSachDonVi', 'tongSoVB', 'tu_ngay', 'den_ngay'))->render();;
            return response()->json([
                'html' => $html,
            ]);
        }

        $date = date('Y-m-d');

        //thống kê tổng số
        $allVanBanDen = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoVanBanDen = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoGiayMoiDen = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $allVanBanMoiNhan = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query)  {
                    return $query->where('trinh_tu_nhan_van_ban', 1)->orWhereNull('trinh_tu_nhan_van_ban');
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoVanBanMoiNhan = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query)  {
                    return $query->where('trinh_tu_nhan_van_ban', 1)->orWhereNull('trinh_tu_nhan_van_ban');
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoGiayMoiMoiNhan = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query)  {
                    return $query->where('trinh_tu_nhan_van_ban', 1)->orWhereNull('trinh_tu_nhan_van_ban');
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $allVanBanDangXuLy = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('trinh_tu_nhan_van_ban', '>', VanBanDen::CHU_TICH_NHAN_VB)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoVanBanDangXuLy = VanBanDen::whereNull('deleted_at')->where('type', 1)
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('trinh_tu_nhan_van_ban', '>', VanBanDen::CHU_TICH_NHAN_VB)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoGiayMoiDangXuLy = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('trinh_tu_nhan_van_ban', '>', VanBanDen::CHU_TICH_NHAN_VB)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $allVanBanDangXuLyQuaHan = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('han_xu_ly', '<', $date)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoVanBanDangXuLyQuaHan = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('han_xu_ly', '<', $date)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoGiayMoiDangXuLyQuaHan = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where('han_xu_ly', '<', $date)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();


            $allVanBanDaHoanThanh = VanBanDen::whereNull('deleted_at')->where('type', 1)
//                ->where('loai_van_ban_id', '!=',$donThu->id)
                ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoVanBanDaHoanThanh = VanBanDen::whereNull('deleted_at')->where('type', 1)
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        $tongSoGiayMoiDaHoanThanh = VanBanDen::whereNull('deleted_at')
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where('type', 1)->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })->count();
        return view('vanbanden::thong_ke.thong_ke_vb_so', compact('danhSachDonVi', 'ds_loaiVanBan', 'tongSoVanBanDen', 'tongSoVanBanDangXuLy'
            , 'tongSoVanBanDangXuLyQuaHan', 'tongSoVanBanDaHoanThanh', 'tongSoVanBanMoiNhan', 'danhSachDonVisearch','tongSoGiayMoiDen',
            'tongSoGiayMoiMoiNhan','tongSoGiayMoiDangXuLy','tongSoGiayMoiDangXuLyQuaHan','tongSoGiayMoiDaHoanThanh',
         'allVanBanDaHoanThanh','allVanBanDangXuLyQuaHan','allVanBanDangXuLy','allVanBanMoiNhan','allVanBanDen'));
    }

    public function vanBanGiaiQuyet($donVi, $tu_ngay, $den_ngay, $vanThuNhap, $loai_van_ban_id)
    {

        $donViId = null;
        $date = date('Y-m-d');
        $type = null;
        if ($donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;
            $type = 1;

        }
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();


        $danhSachVanBanDenDaHoanThanh = VanBanDen::whereNull('deleted_at')
//            ->where('loai_van_ban_id', '!=',$donThu->id)
//        where(function ($query) use ($donViId) {
//               if (!empty($donViId)) {
//                   return $query->where('don_vi_id', $donViId);
//               }
//            })
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->where('type', 1)
            ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
            ->whereNull('deleted_at')
            ->get();



        $danhSachVanBanDenChuaHoanThanh = VanBanDen::whereNull('deleted_at')
//            ->where('loai_van_ban_id', '!=',$donThu->id)
//            ->where(function ($query) use ($donViId) {
//                if (!empty($donViId)) {
//                    return $query->where('don_vi_id', $donViId) ;
//                }
//            })
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
                }
            })
//            ->where(function ($query) {
//                return $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
//                    ->orWhereNull('trinh_tu_nhan_van_ban');
//            })
            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)->where('trinh_tu_nhan_van_ban', '>', VanBanDen::CHU_TICH_NHAN_VB)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->where('type', 1)
            ->get();


        $tongSoGiayMoi = VanBanDen::whereNull('deleted_at')
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($loai_van_ban_id) {
                if (!empty($loai_van_ban_id)) {
                    return $query->where('loai_van_ban_id', $loai_van_ban_id);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                }
            })
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->where('type', 1)
            ->get();
        $arrGiayMoiDenId = $tongSoGiayMoi->pluck('id')->toArray();

        $danhSachGiayMoi = DonViChuTri::whereIn('van_ban_den_id', $arrGiayMoiDenId)
            ->select('van_ban_den_id')
            ->where('don_vi_id', $donVi->id)->distinct('van_ban_den_id')->count();



//        dd($danhSachVanBanDenChuaHoanThanh);

//        dd($danhSachVanBanDenChuaHoanThanh);


        //hoan thanh
        $vanBanDaGiaiQuyet = $this->getVanBanDenDaGiaiQuyet($danhSachVanBanDenDaHoanThanh, $donVi->id, $type);
        //chưa hoàn thành
        $vanBanChuaGiaiQuyet = $this->getVanBanDenchuaGiaiQuyet($danhSachVanBanDenChuaHoanThanh, $donVi->id, $type);

//        $tong = $danhSachVanBanDenDaHoanThanh->count() + $danhSachVanBanDenChuaHoanThanh->count();


//        if (empty($type)) {
        $tong = $vanBanDaGiaiQuyet['tong'] + $vanBanChuaGiaiQuyet['tong']+$danhSachGiayMoi;//        }

        ;
        return [
            'tong' => $tong,
            'giai_quyet_trong_han' => $vanBanDaGiaiQuyet['hoan_thanh_dung_han'],
            'giai_quyet_qua_han' => $vanBanDaGiaiQuyet['hoan_thanh_qua_han'],
            'chua_giai_quyet_giai_quyet_trong_han' => $vanBanChuaGiaiQuyet['chua_giai_quyet_hoan_thanh_dung_han'],
            'chua_giai_quyet_giai_quyet_qua_han' => $vanBanChuaGiaiQuyet['chua_giai_quyet_hoan_thanh_qua_han'],
            'giayMoi' => $danhSachGiayMoi,


        ];
    }


    public function getVanBanDenDaGiaiQuyet($danhSachVanBanDenDaHoanThanh, $donViId, $type)
    {
        $vanBanTrongHan = 0;
        $vanBanQuaHan = 0;
        $tongVanBanDonViKhongDieuHanh = 0;


        $arrVanBanDenId = $danhSachVanBanDenDaHoanThanh->pluck('id')->toArray();

//        $danhSachVanBanDenDonViDaHoanThanhTrongHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
//            ->whereHas('vanBanDen', function ($query) {
//                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN);
//            })->select('van_ban_den_id')
//            ->where('don_vi_id', $donViId)->distinct('van_ban_den_id')->count();
        $danhSachVanBanDenDonViDaHoanThanhTrongHan = DonViChuTri::where('don_vi_id', $donViId)
            ->whereHas('searchVanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_DUNG_HAN);
            })
            ->select('van_ban_den_id')->distinct('van_ban_den_id')->count();
        $vanBanTrongHan = $danhSachVanBanDenDonViDaHoanThanhTrongHan;

//        $danhSachVanBanDenDonViDaHoanThanhQuaHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
//            ->whereHas('vanBanDen', function ($query) {
//                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
//            })->select('van_ban_den_id')
//            ->where('don_vi_id', $donViId)->distinct('van_ban_den_id')->count();
        $danhSachVanBanDenDonViDaHoanThanhQuaHan = DonViChuTri::where('don_vi_id', $donViId)
            ->whereHas('searchVanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
            })->select('van_ban_den_id')->distinct('van_ban_den_id')->count();
        $vanBanQuaHan = $danhSachVanBanDenDonViDaHoanThanhQuaHan;
        $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;
//        }


        return [
            'hoan_thanh_dung_han' => $vanBanTrongHan,
            'hoan_thanh_qua_han' => $vanBanQuaHan,
            'tong' => $tongVanBanDonViKhongDieuHanh
        ];
    }

    public function getVanBanDenchuaGiaiQuyet($danhSachVanBanDenChuaHoanThanh, $donViId, $type)
    {

        $currentDate = date('Y-m-d');

        $arrVanBanDenId = $danhSachVanBanDenChuaHoanThanh->pluck('id')->toArray();
//        $danhSachVanBanDenDonViChuaHoanThanhTrongHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
//            ->whereHas('vanBanDen', function ($query) use ($currentDate) {
//                return $query->where('han_xu_ly', '>=', $currentDate);
//            })->select('van_ban_den_id')
//            ->where('don_vi_id', $donViId)->distinct('van_ban_den_id')->count();

        $danhSachVanBanDenDonViChuaHoanThanhTrongHan = DonViChuTri::where('don_vi_id', $donViId)
            ->whereHas('searchVanBanDenChuaHoanThanh', function ($query) use ($currentDate) {
                return $query->where('han_xu_ly', '>=', $currentDate);
            })->select('van_ban_den_id')->distinct('van_ban_den_id')->count();



        $vanBanTrongHan = $danhSachVanBanDenDonViChuaHoanThanhTrongHan;

//        $danhSachVanBanDenDonViChuaHoanThanhQuaHan = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
//            ->whereHas('vanBanDen', function ($query) use ($currentDate) {
//                return $query->where('han_xu_ly', '<', $currentDate);
//            })->select('van_ban_den_id')
//            ->where('don_vi_id', $donViId)->distinct('van_ban_den_id')->count();

        $danhSachVanBanDenDonViChuaHoanThanhQuaHan = DonViChuTri::where('don_vi_id', $donViId)
            ->whereHas('searchVanBanDenChuaHoanThanh', function ($query) use ($currentDate) {
                return $query->where('han_xu_ly', '<', $currentDate);
            })->select('van_ban_den_id')->distinct('van_ban_den_id')->count();


        $vanBanQuaHan = $danhSachVanBanDenDonViChuaHoanThanhQuaHan;
        $tongVanBanDonViKhongDieuHanh = $vanBanTrongHan + $vanBanQuaHan;

//        dd($danhSachVanBanDenDonViChuaHoanThanhTrongHan,$danhSachVanBanDenDonViChuaHoanThanhQuaHan);




        return [
            'chua_giai_quyet_hoan_thanh_dung_han' => $vanBanTrongHan,
            'chua_giai_quyet_hoan_thanh_qua_han' => $vanBanQuaHan,
            'tong' => $tongVanBanDonViKhongDieuHanh,
        ];
    }

    public function chiTietTongVanBanSo($id, Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id', $id)->first();
        $user = auth::user();
        $type = null;
        $vanThuNhap = auth::user()->id;
        if ($donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;


        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();


        $ds_vanBanDen = VanBanDen::
        whereNull('deleted_at')
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
//            ->where(function ($query) use ($loaiVanBanGiayMoi) {
//                if (!empty($loaiVanBanGiayMoi)) {
//                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
//                }
//            })
            ->where('type', 1)
            ->get();
        $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
        $ds_vanBanDen = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
            ->where('don_vi_id', $donVi->id)->distinct()->get();
        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong', compact('ds_vanBanDen'));

    }


    public function chiTietDaGiaiQuyetTrongHanVanBanSo($id, Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id', $id)->first();
        $user = auth::user();
        $vanThuNhap = auth::user()->id;
        $type = null;
        if ($donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;
        $loaiVanBan = $request->get('loai_van_ban_id') ?? null;

//        $ds_vanBanDen = VanBanDen::
//        whereNull('deleted_at')
////            ->where('loai_van_ban_id', '!=',$donThu->id)
//            ->where(function ($query) use ($tu_ngay, $den_ngay) {
//                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {
//
//                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
//                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
//                }
//                if ($den_ngay == '' && $tu_ngay != '') {
//                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));
//
//                }
//                if ($tu_ngay == '' && $den_ngay != '') {
//                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));
//
//                }
//            })
//            ->where(function ($query) use ($loaiVanBan) {
//                if (!empty($loaiVanBan)) {
//                    return $query->where('loai_van_ban_id', $loaiVanBan);
//                }
//            })
//            ->where(function ($query) use ($loaiVanBanGiayMoi) {
//                if (!empty($loaiVanBanGiayMoi)) {
//                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
//                }
//            })
//            ->where('type', 1)
//            ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
//            ->whereNull('deleted_at')
//            ->get();
//        $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();

        $danhSachVanBanDenDonVi = DonViChuTri::where('don_vi_id', $donVi->id)
            ->whereHas('searchVanBanDenHoanThanhDungHan')
//            ->whereHas('searchVanBanDenHoanThanhDungHan', function ($query) use ($tu_ngay, $den_ngay, $loaiVanBan, $loaiVanBanGiayMoi)  {
//
//                    if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {
//
//                        return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
//                            ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
//                    }
//                    if ($den_ngay == '' && $tu_ngay != '') {
//                        return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));
//
//                    }
//                    if ($tu_ngay == '' && $den_ngay != '') {
//                        return $query->where('ngay_ban_hanh', formatYMD($den_ngay));
//
//                    }
////                    ->where(function ($query) use ($loaiVanBan) {
//                        if (!empty($loaiVanBan)) {
//                            return $query->where('loai_van_ban_id', $loaiVanBan);
//                        }
////                    })
////                    ->where(function ($query) use ($loaiVanBanGiayMoi) {
//                        if (!empty($loaiVanBanGiayMoi)) {
//                            return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
//                        }
////                    })
//            })
            ->select('van_ban_den_id')
            ->distinct('van_ban_den_id')->paginate(10);



        if ($request->get('type') == 'excel') {
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');
            $day = Carbon::now()->format('d');


            $totalRecord = $danhSachVanBanDenDonVi->count();
            $fileName = 'thong_ke_van_ban_den_' . date('d_m_Y') . '.xlsx';

            return Excel::download(new thongKeVanBanDenGiaiQuyetExport($danhSachVanBanDenDonVi, $totalRecord,
                $month, $year, $day),
                $fileName);
        }
        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong', compact('danhSachVanBanDenDonVi'));


    }

    public function chiTietDaGiaiQuyetQuaHanVanBanSo($id, Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id', $id)->first();

        if ($donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();
        $loaiVanBan = $request->get('loai_van_ban_id') ?? null;


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;


//        $ds_vanBanDen = VanBanDen::
//        whereNull('deleted_at')
////            ->where('loai_van_ban_id', '!=',$donThu->id)
//            ->where(function ($query) use ($tu_ngay, $den_ngay) {
//                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {
//
//                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
//                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
//                }
//                if ($den_ngay == '' && $tu_ngay != '') {
//                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));
//
//                }
//                if ($tu_ngay == '' && $den_ngay != '') {
//                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));
//
//                }
//            })
//            ->where(function ($query) use ($loaiVanBan) {
//                if (!empty($loaiVanBan)) {
//                    return $query->where('loai_van_ban_id', $loaiVanBan);
//                }
//            })
//            ->where(function ($query) use ($loaiVanBanGiayMoi) {
//                if (!empty($loaiVanBanGiayMoi)) {
//                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
//                }
//            })
//            ->where('type', 1)
//            ->where('trinh_tu_nhan_van_ban', VanBanDen::HOAN_THANH_VAN_BAN)
//            ->whereNull('deleted_at')
//            ->get();
//        $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
        $danhSachVanBanDenDonVi = DonViChuTri::where('don_vi_id', $donVi->id)
            ->whereHas('searchVanBanDen', function ($query) {
                return $query->where('hoan_thanh_dung_han', VanBanDen::HOAN_THANH_QUA_HAN);
            })
            ->select('van_ban_den_id')->distinct('van_ban_den_id')->get();
        if ($request->get('type') == 'excel') {
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');
            $day = Carbon::now()->format('d');


            $totalRecord = $danhSachVanBanDenDonVi->count();
            $fileName = 'thong_ke_van_ban_den_' . date('d_m_Y') . '.xlsx';

            return Excel::download(new thongKeVanBanDenGiaiQuyetExport($danhSachVanBanDenDonVi, $totalRecord,
                $month, $year, $day),
                $fileName);
        }
        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong', compact('danhSachVanBanDenDonVi'));


//        }

//        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso',compact('ds_vanBanDen'));
    }

    public function chiTietChuaGiaiQuyetQuaHanVanBanSo($id, Request $request)
    {
        $donViId = null;
        $donVi = DonVi::where('id', $id)->first();
        $date = date('Y-m-d');
        $type = null;
        $vanThuNhap = auth::user()->id;
        if ($donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();
        $loaiVanBan = $request->get('loai_van_ban_id') ?? null;


        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;


//        $ds_vanBanDen = VanBanDen::
//        whereNull('deleted_at')
////            ->where('loai_van_ban_id', '!=',$donThu->id)
//            ->where(function ($query) {
//                return $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
//                    ->orWhereNull('trinh_tu_nhan_van_ban');
//            })
//            ->where('han_xu_ly', '<', $date)
//            ->where(function ($query) use ($tu_ngay, $den_ngay) {
//                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {
//
//                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
//                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
//                }
//                if ($den_ngay == '' && $tu_ngay != '') {
//                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));
//
//                }
//                if ($tu_ngay == '' && $den_ngay != '') {
//                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));
//
//                }
//            })
//            ->where(function ($query) use ($loaiVanBanGiayMoi) {
//                if (!empty($loaiVanBanGiayMoi)) {
//                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
//                }
//            })
//            ->where(function ($query) use ($loaiVanBan) {
//                if (!empty($loaiVanBan)) {
//                    return $query->where('loai_van_ban_id', $loaiVanBan);
//                }
//            })
//            ->where('type', 1)
//            ->get();
//        $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();

        $danhSachVanBanDenDonVi = DonViChuTri::where('don_vi_id', $donVi->id)
            ->where(function ($query) use ($date) {
                if (!empty($date)) {
                    return $query->whereHas('searchVanBanDenChuaGiaiQuyetQuaHan', function ($q) use($date) {
                        return $q->where('han_xu_ly', '<', $date);
                    });
                }
            })
            ->distinct('van_ban_den_id')
            ->get('van_ban_den_id');
        if ($request->get('type') == 'excel') {
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');
            $day = Carbon::now()->format('d');


            $totalRecord = $danhSachVanBanDenDonVi->count();
            $fileName = 'thong_ke_van_ban_den_' . date('d_m_Y') . '.xlsx';

            return Excel::download(new thongKeVanBanDenGiaiQuyetExport($danhSachVanBanDenDonVi, $totalRecord,
                $month, $year, $day),
                $fileName);
        }
        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong', compact('danhSachVanBanDenDonVi'));


    }

    public function chiTietChuaGiaiQuyetTrongHanVanBanSo($id, Request $request)
    {
        $vanThuNhap = auth::user()->id;
        $donViId = null;
        $donVi = DonVi::where('id', $id)->first();
        $user = auth::user();
        $date = date('Y-m-d');
        $type = null;
        $currentDate = date('Y-m-d');
        if ($donVi->dieu_hanh == DonVi::DIEU_HANH) {
            $donViId = $donVi->id;

        }
        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();


        $loaiVanBan = $request->get('loai_van_ban_id') ?? null;
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

//        $ds_vanBanDen = VanBanDen::whereNull('deleted_at')
////            ->where(function ($query) {
////                return $query->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
////                    ->orWhereNull('trinh_tu_nhan_van_ban');
////            })
////            ->where('loai_van_ban_id', '!=',$donThu->id)
//            ->where('trinh_tu_nhan_van_ban', '<', VanBanDen::HOAN_THANH_VAN_BAN)
//            ->where('trinh_tu_nhan_van_ban', '>', VanBanDen::CHU_TICH_NHAN_VB)
//            ->where('han_xu_ly', '>=', $date)
//            ->where(function ($query) use ($tu_ngay, $den_ngay) {
//                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {
//
//                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
//                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
//                }
//                if ($den_ngay == '' && $tu_ngay != '') {
//                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));
//
//                }
//                if ($tu_ngay == '' && $den_ngay != '') {
//                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));
//
//                }
//            })
//            ->where(function ($query) use ($loaiVanBan) {
//                if (!empty($loaiVanBan)) {
//                    return $query->where('loai_van_ban_id', $loaiVanBan);
//                }
//            })
//            ->where(function ($query) use ($loaiVanBanGiayMoi) {
//                if (!empty($loaiVanBanGiayMoi)) {
//                    return $query->where('loai_van_ban_id', '!=', $loaiVanBanGiayMoi->id);
//                }
//            })
//            ->where('type', 1)
//            ->get();
//        $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
        $danhSachVanBanDenDonVi = DonViChuTri::where('don_vi_id', $donVi->id)
//            ->whereHas('vanBanDen', function ($query) use ($currentDate) {
//                return $query->where('han_xu_ly', '>=', $currentDate);
//            })
            ->where(function ($query) use ($currentDate) {
                if (!empty($currentDate)) {
                    return $query->whereHas('searchVanBanDenChuaGiaiQuyetTrongHan', function ($q) use($currentDate) {
                        return $q->where('han_xu_ly', '>=', $currentDate);
                    });
                }
            })
            ->distinct('van_ban_den_id')
            ->get('van_ban_den_id');





        if ($request->get('type') == 'excel') {
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');
            $day = Carbon::now()->format('d');


            $totalRecord = $danhSachVanBanDenDonVi->count();
            $fileName = 'thong_ke_van_ban_den_' . date('d_m_Y') . '.xlsx';

            return Excel::download(new thongKeVanBanDenGiaiQuyetExport($danhSachVanBanDenDonVi, $totalRecord,
                $month, $year, $day),
                $fileName);
        }
        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong', compact('danhSachVanBanDenDonVi'));



    }

    public function chiTietgiayMoi($id, Request $request)
    {
        $donVi = DonVi::where('id', $id)->first();

        $loaiVanBanGiayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')
            ->select('id')
            ->first();
        $donThu = LoaiVanBan::where('ten_loai_van_ban','Like','Đơn thư')->first();


        $loaiVanBan = $request->get('loai_van_ban_id') ?? null;
        $tu_ngay = $request->get('tu_ngay') ?? null;
        $den_ngay = $request->get('den_ngay') ?? null;

        $ds_vanBanDen = VanBanDen::whereNull('deleted_at')
//            ->where('loai_van_ban_id', '!=',$donThu->id)
            ->where(function ($query) use ($tu_ngay, $den_ngay) {
                if ($tu_ngay != '' && $den_ngay != '' && $tu_ngay <= $den_ngay) {

                    return $query->where('ngay_ban_hanh', '>=', formatYMD($tu_ngay))
                        ->where('ngay_ban_hanh', '<=', formatYMD($den_ngay));
                }
                if ($den_ngay == '' && $tu_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($tu_ngay));

                }
                if ($tu_ngay == '' && $den_ngay != '') {
                    return $query->where('ngay_ban_hanh', formatYMD($den_ngay));

                }
            })
            ->where(function ($query) use ($loaiVanBan) {
                if (!empty($loaiVanBan)) {
                    return $query->where('loai_van_ban_id', $loaiVanBan);
                }
            })
            ->where(function ($query) use ($loaiVanBanGiayMoi) {
                if (!empty($loaiVanBanGiayMoi)) {
                    return $query->where('loai_van_ban_id', $loaiVanBanGiayMoi->id);
                }
            })
            ->where('type', 1)
            ->get();
        $arrVanBanDenId = $ds_vanBanDen->pluck('id')->toArray();
        $danhSachVanBanDenDonVi = DonViChuTri::whereIn('van_ban_den_id', $arrVanBanDenId)
            ->where('don_vi_id', $donVi->id)
            ->distinct('van_ban_den_id')
            ->get('van_ban_den_id');





        if ($request->get('type') == 'excel') {
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');
            $day = Carbon::now()->format('d');


            $totalRecord = $ds_vanBanDen->count();
            $fileName = 'thong_ke_van_ban_den_' . date('d_m_Y') . '.xlsx';

            return Excel::download(new thongKeVanBanDenGiaiQuyetExport($danhSachVanBanDenDonVi, $totalRecord,
                $month, $year, $day),
                $fileName);
        }
        return view('vanbanden::chi-tiet-thong-ke.tong_van_ban_tkso_phong', compact('danhSachVanBanDenDonVi'));



    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('vanbanden::create');
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
        return view('vanbanden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('vanbanden::edit');
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
