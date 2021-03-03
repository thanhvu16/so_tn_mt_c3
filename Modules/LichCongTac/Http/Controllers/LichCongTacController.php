<?php

namespace Modules\LichCongTac\Http\Controllers;

use App\Common\AllPermission;
use App\Models\LichCongTac;
use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Auth;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;

class LichCongTacController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        $tuan = $request->get('tuan');
        $year = !empty($request->get('year')) ? $request->get('year') : date('Y');
        $week = $tuan ? $tuan : date('W');

        $lanhDaoId = $request->get('lanh_dao_id') ?? $currentUser->id;

        $donViId = null;
        $donViDuHop = null;

        $ngayTuan = [
            array('Thứ Hai', date('d/m/Y', strtotime($year . "W" . $week . 1))),
            array('Thứ Ba', date('d/m/Y', strtotime($year . "W" . $week . 2))),
            array('Thứ Tư', date('d/m/Y', strtotime($year . "W" . $week . 3))),
            array('Thứ Năm', date('d/m/Y', strtotime($year . "W" . $week . 4))),
            array('Thứ Sáu', date('d/m/Y', strtotime($year . "W" . $week . 5))),
            array('Thứ Bảy', date('d/m/Y', strtotime($year . "W" . $week . 6))),
            array('Chủ Nhật', date('d/m/Y', strtotime($year . "W" . $week . 7)))
        ];
        $start_date = strtotime($year . "W" . $week . 1);
        $end_date = strtotime($year . "W" . $week . 7);

        $ngaybd = date('Y-m-d', $start_date);
        $ngaykt = date('Y-m-d', $end_date);

        $totalWeekOfYear = max(date("W", strtotime($year . "-12-27")),
            date("W", strtotime($year . "-12-29")),
            date("W", strtotime($year . "-12-31")));
//        dd($totalWeekOfYear);

        $tuanTruoc = $week != 1 ? $week - 1 : 01;
        $tuanSau = $week != $totalWeekOfYear ? $week + 1 : $totalWeekOfYear;

        $tuanTruoc = $tuanTruoc < 10 ? '0' . $tuanTruoc : $tuanTruoc;
        $tuanSau = $tuanSau < 10 ? '0' . $tuanSau : $tuanSau;
        $roles = [CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, TRUONG_PHONG];

        $donVi = $currentUser->donVi;
        $donViCapXa = DonVi::whereNotNull('cap_xa')->whereNull('deleted_at')->first();

        if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
            $roles = [CHU_TICH, PHO_CHU_TICH, TRUONG_BAN];
        }

        $id = [];

        //
        if ($currentUser->hasRole(PHO_CHU_TICH)) {

            $id = [$currentUser->id];
//            array_push( $id, $currentUser->id);

            $roleDonVi = [CHANH_VAN_PHONG, TRUONG_PHONG];

            $danhSachTruongPhongDonVi = User::whereHas('roles', function ($query) use ($roleDonVi) {
                return $query->whereIn('name', $roleDonVi);
            })->select('id')->get();

            $arrLanhDaoDonViID = $danhSachTruongPhongDonVi->pluck('id')->toArray();
            $id = array_merge($id, $arrLanhDaoDonViID);

        }

        $danhSachLanhDao = User::whereHas('roles', function ($query) use ($roles) {
            return $query->whereIn('name', $roles);
        })
            ->where(function ($query) use ($id) {
                if (!empty($id)) {
                    return $query->whereIn('id', $id);
                }
            })
            ->where(function ($query) use ($donVi, $donViCapXa) {
                if (isset($donVi) && $donVi->cap_xa == DonVi::CAP_XA) {
                    return $query->where('don_vi_id', $donVi->id);
                } else {
                    return $query->whereNotIn('don_vi_id', [$donViCapXa->id]);

                }
            })
            ->where('trang_thai', ACTIVE)
            ->orderBy('uu_tien', 'ASC')
            ->get();


        if ($currentUser->hasRole([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN])) {
            $donViId = $currentUser->don_vi_id;
            $lanhDaoId = $currentUser->id;
            $donViDuHop = $currentUser->don_vi_id;
        }

        $danhSachLichCongTac = LichCongTac::with('vanBanDen', 'vanBanDi', 'congViecDonVi')
            ->where('ngay', '>=', $ngaybd)
            ->where('ngay', '<=', $ngaykt)
            ->where(function ($query) use ($lanhDaoId) {
                if (!empty($lanhDaoId)) {
                    return $query->where('lanh_dao_id', $lanhDaoId);
                }
            })
//            ->where(function ($query) use ($donViDuHop, $currentUser, $donViId) {
//
//                if ($currentUser->hasRole([TRUONG_PHONG, CHANH_VAN_PHONG, PHO_PHONG, PHO_CHANH_VAN_PHONG])) {
//                    return $query->where('don_vi_du_hop', $donViDuHop)
//                        ->orWhere('don_vi_id', $donViId);
//                }
//            })
            ->orderBy('buoi', 'ASC')->get();



        if ($danhSachLichCongTac) {
            foreach ($danhSachLichCongTac as $lichCongTac) {

                $lichCongTac->CanBoChiDao = null;
                if ($lichCongTac->chuanBiTruocCuocHop()) {
                    $lichCongTac->CanBoChiDao = XuLyVanBanDen::where('van_ban_den_id', $lichCongTac->object_id)
                        ->where('id', '>=', $lichCongTac->chuanBiTruocCuocHop())->get();
                }
                $lichCongTac->parent = $lichCongTac->getParent();
                $lichCongTac->truyenNhanVanBanDonVi = $lichCongTac->donViChuTri();
                $lichCongTac->giaiQuyetVanBanHoanThanh = isset($lichCongTac->vanBanDen) ? $lichCongTac->vanBanDen->giaiQuyetVanBanHoanThanh() : null;
            }
        }

        // don vi nhan vb xem lich ct cua ld
        if ($currentUser->hasRole([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN])) {

            $arrLanhDaoId = $danhSachLichCongTac->pluck('lanh_dao_id')->toArray();

            $danhSachLanhDao = User::whereIn('id', $arrLanhDaoId)
                ->orderBy('id', 'ASC')
                ->where('trang_thai', ACTIVE)
                ->get();
        }

        return view('lichcongtac::index', compact('danhSachLichCongTac', 'year',
            'tuanTruoc', 'tuanSau', 'totalWeekOfYear', 'week', 'ngayTuan', 'danhSachLanhDao'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('lichcongtac::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //canPermission(AllPermission::themLichCongTac());
        $currentUser = auth::user();
        $lichCongTacId = $request->get('lich_cong_tac_id') ?? null;
        $thanhPhanDuHopId = $request->get('thanh_phan_du_hop_id') ?? null;

        //tạo lịch công tác cá nhân từ lịch được mời tham dự
        if (!empty($lichCongTacId)) {
            $lichCongTac = LichCongTac::find($lichCongTacId);
            //
            if ($lichCongTac) {
                $dataTaoMoiLichCongTac = array(
                    'object_id' => $lichCongTac->object_id,
                    'parent_id' => $lichCongTac->id,
                    'type' => $lichCongTac->type,
                    'lanh_dao_id' => $currentUser->id,
                    'ngay' => $lichCongTac->ngay,
                    'gio' => $lichCongTac->gio,
                    'tuan' => $lichCongTac->tuan,
                    'noi_dung' => $lichCongTac->noi_dung,
                    'don_vi_id' => $lichCongTac->don_vi_id,
                    'buoi' => $lichCongTac->buoi,
                    'dia_diem' => $lichCongTac->dia_diem,
                    'trang_thai_lich' => $lichCongTac->trang_thai_lich,
                    'ghi_chu' => $lichCongTac->ghi_chu,
                    'user_id' => $currentUser->id,
                    'don_vi_du_hop' => $lichCongTac->don_vi_du_hop,
                    'thanh_phan_du_hop_id' => $thanhPhanDuHopId,
                );

                $newLichCongTac = new LichCongTac();
                $newLichCongTac->fill($dataTaoMoiLichCongTac);
                $newLichCongTac->save();

                // update
                $thanhPhanDuHop = ThanhPhanDuHop::where('id', $thanhPhanDuHopId)->first();
                if ($thanhPhanDuHop) {
                    $thanhPhanDuHop->trang_thai_lich = ThanhPhanDuHop::TRANG_THAI_LICH_DA_CHUYEN;
                    $thanhPhanDuHop->save();
                }

                return redirect()->route('lich-cong-tac.index')->with('success', 'Đã chuyển lịch công tác thành công.');
            }

            return redirect()->back()->with('warning', 'Không tìm thấy lịch này vui lòng thử lại.');
        }

        $tuan = date('W', strtotime($request->get('ngay')));

        $dataLichCongTac = array(
            'lanh_dao_id' => $request->get('lanh_dao_id'),
            'ngay' => $request->get('ngay'),
            'gio' => $request->get('gio'),
            'tuan' => $tuan,
            'buoi' => ($request->get('gio') <= '12:00') ? 1 : 2,
            'noi_dung' => $request->get('noi_dung'),
            'dia_diem' => $request->get('dia_diem'),
            'type' => LichCongTac::TYPE_NHAP_TRUC_TIEP,
            'trang_thai_lich' => $request->get('trang_thai_lich'),
            'ghi_chu' => $request->get('ghi_chu'),
            'user_id' => $currentUser->id,
        );
        //check lich cong tac
        $lichCongTac = LichCongTac::where('ngay', $request->get('ngay'))
            ->where('gio', $request->get('gio'))
            ->first();

        if (!empty($lichCongTac)) {

            return redirect()->back()->with('warning', 'Lịch này đã tồn tại, vui lòng chọn lịch khác.');
        }
        $lichCongTac = new LichCongTac();
        $lichCongTac->fill($dataLichCongTac);
        $lichCongTac->save();
        //log
        UserLogs::saveUserLogs('Thêm mới lịch công tác', $lichCongTac);

        return redirect()->back()->with('success', 'Đã thêm lịch công tác thành công.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('lichcongtac::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        canPermission(AllPermission::suaLichCongTac());

        $lichCongTac = LichCongTac::find($id);

        $roles = [CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_PHONG, PHO_PHONG];
        $danhSachLanhDao = User::whereHas('roles', function ($query) use ($roles) {
            return $query->whereIn('name', $roles);
        })
            ->orderBy('id', 'ASC')
            ->get();

        $returnHTML = view('lichcongtac::them_lich._edit',
            compact('lichCongTac', 'danhSachLanhDao'))->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $currentUser = auth::user();
        $tuan = date('W', strtotime($request->get('ngay')));

        $dataLichCongTac = array(
            'lanh_dao_id' => $request->get('lanh_dao_id'),
            'ngay' => $request->get('ngay'),
            'gio' => $request->get('gio'),
            'tuan' => $tuan,
            'buoi' => ($request->get('gio') <= '12:00') ? 1 : 2,
            'noi_dung' => $request->get('noi_dung'),
            'dia_diem' => $request->get('dia_diem'),
            'trang_thai_lich' => $request->get('trang_thai_lich'),
            'ghi_chu' => $request->get('ghi_chu'),
            'user_id' => $currentUser->id,
        );

        //check lich cong tac
        $lichCongTac = LichCongTac::findOrFail($id);
        $lichCongTac->fill($dataLichCongTac);
        $lichCongTac->save();

        UserLogs::saveUserLogs('Cập nhật lịch công tác', $lichCongTac);

        return redirect()->back()->with('success', 'Cập nhật lịch công tác thành công.');
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
