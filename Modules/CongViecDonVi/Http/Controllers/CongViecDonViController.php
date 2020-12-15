<?php

namespace Modules\CongViecDonVi\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB ,auth;
use Modules\Admin\Entities\DonVi;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonViPhoiHop;

class CongViecDonViController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $currentUser = auth::user();
        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')->where('can_bo_nhan_id', $currentUser->id)
            ->whereNull('type')
            ->whereNull('chuyen_tiep')
            ->orWhere('chuyen_tiep', 0)
            ->whereNull('hoan_thanh')
            ->paginate(PER_PAGE);

        $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG];

        $danhSachPhoPhong = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->wherenull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $danhSachChuyenVien =User::role(CHUYEN_VIEN)->where('don_vi_id', $currentUser->don_vi_id)->wherenull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $order = ($chuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;
        if ($currentUser->hasRole(CHUYEN_VIEN) ) {

            return view('congviecdonvi::cong-viec-don-vi.chuyen-vien', compact('chuyenNhanCongViecDonVi',
                'danhSachPhoPhong', 'danhSachChuyenVien', 'order'));
        }

        return view('congviecdonvi::cong-viec-don-vi.index', compact('chuyenNhanCongViecDonVi',
            'danhSachPhoPhong', 'danhSachChuyenVien', 'order'));
    }
    public function chuyenVienPhoiHop(Request $request)
    {
        $currentUser = auth::user();
        $congViecDonViPhoiHop = CongViecDonViPhoiHop::where('can_bo_nhan_id', $currentUser->id)
            ->whereNull('status')
            ->whereNull('type')->get();

        $arrChuyenNhanCongViecDonViId = $congViecDonViPhoiHop->pluck('chuyen_nhan_cong_viec_don_vi_id');

        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')
            ->whereIn('id', $arrChuyenNhanCongViecDonViId)
            ->whereNull('hoan_thanh')
            ->paginate(PER_PAGE);

        $order = ($chuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        return view('congviecdonvi::cong-viec-don-vi.chuyen-vien-phoi-hop', compact('chuyenNhanCongViecDonVi', 'order'));
    }

    public function chuyenVienDaPhoiHop()
    {
        $currentUser = auth::user();
        $congViecDonViPhoiHop = CongViecDonViPhoiHop::where('can_bo_nhan_id', $currentUser->id)
            ->where('status', CongViecDonViPhoiHop::STATUS_GIAI_QUYET)
            ->whereNull('type')->get();

        $arrChuyenNhanCongViecDonViId = $congViecDonViPhoiHop->pluck('chuyen_nhan_cong_viec_don_vi_id');

        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')
            ->whereIn('id', $arrChuyenNhanCongViecDonViId)
            ->paginate(PER_PAGE);

        $order = ($chuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        $type = 'daXuLy';

        return view('congviecdonvi::cong-viec-don-vi.chuyen-vien-phoi-hop',
            compact('chuyenNhanCongViecDonVi', 'order', 'type'));
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $currentUser = auth::user();
        $danhSachDonViChutri = Donvi::
        where('id', '!=', auth::user()->don_vi_id)
            ->whereNull('deleted_at')
            ->get();

        $donViChuTri = Donvi::where('id',auth::user()->don_vi_id)
            ->whereNull('deleted_at')
            ->get();

        $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG];
        $danhSachPhoPhong = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->wherenull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $danhSachChuyenVien = User::Role('chuyên viên')->where('don_vi_id', $currentUser->don_vi_id)
//            ->whereNull('quyen_tham_muu')
//            ->where('trang_thai', User::TRANG_THAI_HOAT_DONG)
            ->orderBy('id', 'DESC')->get();

        return view('congviecdonvi::cong-viec-don-vi.create',
            compact('danhSachDonViChutri', 'donViChuTri', 'danhSachPhoPhong', 'danhSachChuyenVien'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function getDataDonVi(Request $request)
    {
//        dd($request->all());
        if ($request->ajax()) {

            $currentUser = auth::user();
            //don vi cap 2
            $danhSachDonViChutri = Donvi::
//            where('cap_don_vi', Donvi::CAP_3)
                whereNull('deleted_at')
                ->get();

            return response()->json([
                200,
                'danhSachDonViChutri' => $danhSachDonViChutri
            ]);

        }
    }

    public function getDonViPhoiHop($id, Request $request)
    {

        if ($request->ajax()) {

            $danhSachDonViChutri = Donvi::
                whereNotIn('id', json_decode($id))
                ->whereNull('deleted_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $danhSachDonViChutri
            ]);
        }
    }
    public function store(Request $request)
    {
        $currentUser = auth::user();
        $data = $request->all();
        $chuyenNhanCongViecIds = json_decode($data['van_ban_den_don_vi_id']);
        $danhSachPhoPhongIds = $data['pho_phong_id'] ?? null;
        $danhSachChuyenVienIds = $data['chuyen_vien_id'] ?? null;
        $textnoidungPhoPhong = $data['noi_dung_pho_phong'] ?? null;
        $textNoiDungChuyenVien = $data['noi_dung_chuyen_vien'] ?? null;
        $arrChuyenVienPhoiHopIds = $data['chuyen_vien_phoi_hop_id'] ?? null;
        $arrLanhDaoXemDeBiet = $data['lanh_dao_xem_de_biet'] ?? null;
        $typeDonViPhoiHop = $request->get('don_vi_phoi_hop') ?? null;
        if (isset($chuyenNhanCongViecIds) && count($chuyenNhanCongViecIds) > 0) {
            try {
                DB::beginTransaction();

                foreach ($chuyenNhanCongViecIds as $chuyenNhanCongViecId) {

                    // don vi phoi hop
                    if ($typeDonViPhoiHop && $typeDonViPhoiHop == ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP) {
                        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::where('id', $chuyenNhanCongViecId)
                            ->where('type', $typeDonViPhoiHop)
                            ->whereNull('hoan_thanh')
                            ->first();
                    } else {

                        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::where('id', $chuyenNhanCongViecId)
                            ->whereNull('type')
                            ->whereNull('hoan_thanh')
                            ->first();
                    }

                    if ($chuyenNhanCongViecDonVi) {

                        $chuyenNhanCongViecDonVi->chuyen_tiep = ChuyenNhanCongViecDonVi::CHUYEN_TIEP;
                        $chuyenNhanCongViecDonVi->save();

                        if ($typeDonViPhoiHop && $typeDonViPhoiHop == ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP) {
                            ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $chuyenNhanCongViecDonVi->cong_viec_don_vi_id)
                                ->where('id', '>', $chuyenNhanCongViecDonVi->id)
                                ->where('type', $typeDonViPhoiHop)
                                ->whereNull('hoan_thanh')
                                ->delete();
                        } else {
                            ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $chuyenNhanCongViecDonVi->cong_viec_don_vi_id)
                                ->where('id', '>', $chuyenNhanCongViecDonVi->id)
                                ->whereNull('type')
                                ->whereNull('hoan_thanh')
                                ->delete();
                        }
                    }

                    if (!empty($danhSachPhoPhongIds[$chuyenNhanCongViecId])) {
                        $dataChuyenNhanDonVi = [
                            'cong_viec_don_vi_id' => $chuyenNhanCongViecDonVi->cong_viec_don_vi_id ?? null,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachPhoPhongIds[$chuyenNhanCongViecId],
                            'noi_dung' => $chuyenNhanCongViecDonVi->noi_dung ?? null,
                            'noi_dung_chuyen' => $textnoidungPhoPhong[$chuyenNhanCongViecId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'han_xu_ly' => $chuyenNhanCongViecDonVi->han_xu_ly,
                            'parent_id' => $chuyenNhanCongViecDonVi ? $chuyenNhanCongViecDonVi->id : null,
                            'type' => $typeDonViPhoiHop
                        ];

                        //save pho phong
                        $chuyenNhanCongViecDonViPhoPhong = new ChuyenNhanCongViecDonVi();
                        $chuyenNhanCongViecDonViPhoPhong->fill($dataChuyenNhanDonVi);
                        $chuyenNhanCongViecDonViPhoPhong->save();
                    }

                    // save chuyen vien thuc hien
                    if (!empty($danhSachChuyenVienIds[$chuyenNhanCongViecId])) {
                        $dataChuyenNhanDonViChuyenVien = [
                            'cong_viec_don_vi_id' => $chuyenNhanCongViecDonVi->cong_viec_don_vi_id ?? null,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $danhSachChuyenVienIds[$chuyenNhanCongViecId],
                            'noi_dung' => $chuyenNhanCongViecDonVi->noi_dung ?? null,
                            'noi_dung_chuyen' => $textNoiDungChuyenVien[$chuyenNhanCongViecId],
                            'don_vi_id' => $currentUser->don_vi_id,
                            'han_xu_ly' => $chuyenNhanCongViecDonVi->han_xu_ly,
                            'type' => $typeDonViPhoiHop,
                            'parent_id' => $chuyenNhanCongViecDonVi ? $chuyenNhanCongViecDonVi->id : null
                        ];

                        //save pho phong
                        $chuyenNhanCongViecDonViChuyenVien = new ChuyenNhanCongViecDonVi();
                        $chuyenNhanCongViecDonViChuyenVien->fill($dataChuyenNhanDonViChuyenVien);
                        $chuyenNhanCongViecDonViChuyenVien->save();
                    }
                    // save chuyen vien phoi hop
                    if (!empty($arrChuyenVienPhoiHopIds[$chuyenNhanCongViecId])) {
                        //save chuyen vien phoi hop
                        CongViecDonViPhoiHop::savechuyenVienPhoiHop($arrChuyenVienPhoiHopIds[$chuyenNhanCongViecId],
                            $chuyenNhanCongViecDonVi->cong_viec_don_vi_id, $chuyenNhanCongViecDonVi->id, $currentUser->don_vi_id);
                    }

                    if (!empty($arrLanhDaoXemDeBiet[$chuyenNhanCongViecId])) {

                        CongViecDonViPhoiHop::saveCanBoXemDeBiet($arrLanhDaoXemDeBiet[$chuyenNhanCongViecId], $chuyenNhanCongViecDonVi->cong_viec_don_vi_id, $chuyenNhanCongViecDonVi->id);
                    }

                }

                DB::commit();

                return redirect()->back()->with('success', 'Đã gửi thành công.');

            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }

        }
    }

    public function dangXuLy(Request $request)
    {
        $currentUser = auth::user();
            $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')->where('can_bo_nhan_id', $currentUser->id)
                ->whereNull('type')
                ->where('chuyen_tiep', ChuyenNhanCongViecDonVi::CHUYEN_TIEP)
                ->whereNull('hoan_thanh')
                ->paginate(PER_PAGE);

            $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG];
            $danhSachPhoPhong = User::where('don_vi_id', $currentUser->don_vi_id)
                ->whereHas('roles', function ($query) use ($roles) {
                    return $query->whereIn('name', $roles);
                })
                ->wherenull('deleted_at')
                ->orderBy('id', 'DESC')->get();

            $danhSachChuyenVien = User::role(CHUYEN_VIEN)->where('don_vi_id', $currentUser->don_vi_id)->whereNull('deleted_at')
                ->where('trang_thai', User::TRANG_THAI_HOAT_DONG)
                ->orderBy('id', 'DESC')->get();

            $order = ($chuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

            return view('congviecdonvi::cong-viec-don-vi.dang-xu-ly', compact('chuyenNhanCongViecDonVi',
                'danhSachPhoPhong', 'danhSachChuyenVien', 'order'));

    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $currentUser = auth::user();
        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('giaHanCongViec')->findOrFail($id);

//
//        if ( $currentUser->hasRole(PHO_CHANH_VAN_PHONG,PHO_PHONG) ) {
//            $danhSachLanhDao = User::role([CHU_TICH, CHANH_VAN_PHONG,TRUONG_PHONG])->where('don_vi_id', $currentUser->donvi_id)
//                ->orderBy('id', 'ASC')->get();
//        }else{
//        $danhSachLanhDao = User::role([PHO_CHUC_TICH, PHO_CHANH_VAN_PHONG,PHO_PHONG])->where('don_vi_id', $currentUser->donvi_id)
//            ->orderBy('id', 'ASC')->get();
//    }

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                $danhSachLanhDao = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case PHO_PHONG:
                $danhSachLanhDao = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                $danhSachLanhDao = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;
            case PHO_CHUC_TICH:
                $danhSachLanhDao = User::role([CHU_TICH])->get();
                break;
            case CHU_TICH:
                $nguoinhan = null;
                break;
            case CHANH_VAN_PHONG:
                $danhSachLanhDao = User::role([PHO_CHUC_TICH, CHU_TICH])->get();
                break;
            case PHO_CHANH_VAN_PHONG:
                $danhSachLanhDao = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $danhSachLanhDao = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:
                $danhSachLanhDao = User::role([CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;

        }

        return view('congviecdonvi::cong-viec-don-vi.show',
            compact('chuyenNhanCongViecDonVi', 'danhSachLanhDao'));
    }

    public function congViecDaXuLy()
    {
        $currentUser = auth::user();
        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')->where('can_bo_nhan_id', $currentUser->id)
            ->whereNull('type')
            ->where('chuyen_tiep', ChuyenNhanCongViecDonVi::GIAI_QUYET)
            ->whereNull('hoan_thanh')
            ->paginate(PER_PAGE);

        $order = ($chuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        return view('congviecdonvi::cong-viec-don-vi.da_xu_ly', compact('chuyenNhanCongViecDonVi','order'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('congviecdonvi::edit');
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
    public function CongViecXemDeBiet(Request $request)
    {
        $currentUser = auth::user();
        $congViecDonViPhoiHop = CongViecDonViPhoiHop::where('can_bo_nhan_id', $currentUser->id)
            ->where('type', CongViecDonViPhoiHop::TYPE_XEM_DE_BIET)->get();

        $arrChuyenNhanCongViecDonViId = $congViecDonViPhoiHop->pluck('chuyen_nhan_cong_viec_don_vi_id');

        $danhSachChuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')
            ->whereIn('id', $arrChuyenNhanCongViecDonViId)
            ->paginate(PER_PAGE);

        $order = ($danhSachChuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        return view('congviecdonvi::cong-viec-don-vi.hoan-thanh.cong-viec-xem-de-biet', compact('danhSachChuyenNhanCongViecDonVi', 'order'));
    }

    public function getCanBoPhoiHop($id, Request $request)
    {
        if ($request->ajax()) {

            $currentUser = auth::user();

            $donVi = $currentUser->donVi;

            $danhSachNguoiDung = User::role(CHUYEN_VIEN)->where('don_vi_id', $donVi->id)
                ->whereNotIn('id', json_decode($id))->whereNull('deleted_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $danhSachNguoiDung
            ]);
        }
    }
}
