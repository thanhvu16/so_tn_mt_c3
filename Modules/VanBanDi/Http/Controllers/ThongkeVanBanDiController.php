<?php

namespace Modules\VanBanDi\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Auth, Excel, PDF, DB;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDi\Entities\VanBanDi;
use App\Exports\VanbandiExport;

class ThongkeVanBanDiController extends Controller
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
        $ngaybatdau = formatYMD($request->tu_ngay);
        $ngayketthuc = formatYMD($request->den_ngay);
        $tu_so = $request->get('tu_so');
        $den_so = $request->get('den_so');
        $donVi = auth::user()->donVi;
        $vanbandi = null;
        if ($id) {
            $vanbandi = VanBanDi::where('id', $id)->first();
        }
        $user = auth::user();
        if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH)) {
            $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->first();
            $ds_vanBanDi_tim = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $lanhDaoSo->don_vi_id])
                ->where('so_di', '!=', null)->whereNull('deleted_at')
                ->where(function ($query) use ($timloaiso) {
                    if (!empty($timloaiso)) {
                        return $query->where('so_van_ban_id', $timloaiso);
                    }
                })
                ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                    if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                        return $query->where('ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('ngay_ban_hanh', '<=', $ngayketthuc);
                    }
                    if ($ngayketthuc == '' && $ngaybatdau != '') {
                        return $query->where('ngay_ban_hanh', $ngaybatdau);

                    }
                    if ($ngaybatdau == '' && $ngayketthuc != '') {
                        return $query->where('ngay_ban_hanh', $ngayketthuc);

                    }
                })
                ->where(function ($query) use ($tu_so, $den_so) {
                    if ($tu_so != '' && $den_so != '' && $tu_so <= $den_so) {

                        return $query->where('so_di', '>=', $tu_so)
                            ->where('so_di', '<=', $den_so);
                    }
                    if ($den_so == '' && $tu_so != '') {
                        return $query->where('so_di', $tu_so);

                    }
                    if ($tu_so == '' && $den_so != '') {
                        return $query->where('so_di', $den_so);

                    }
                })
                ->orderBy('created_at', 'desc');

        } elseif ($user->hasRole(CHUYEN_VIEN) || $user->hasRole(PHO_PHONG) || $user->hasRole(TRUONG_PHONG) || $user->hasRole(VAN_THU_DON_VI) ||
            $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {

            $donViId = $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id;
            $ds_vanBanDi_tim = VanBanDi::where(['loai_van_ban_giay_moi' => 1, 'phong_phat_hanh' => $donViId])
                ->where('so_di', '!=', null)->whereNull('deleted_at')
                ->where(function ($query) use ($timloaiso) {
                    if (!empty($timloaiso)) {
                        return $query->where('so_van_ban_id', $timloaiso);
                    }
                })
                ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                    if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                        return $query->where('ngay_ban_hanh', '>=', $ngaybatdau)
                            ->where('ngay_ban_hanh', '<=', $ngayketthuc);
                    }
                    if ($ngayketthuc == '' && $ngaybatdau != '') {
                        return $query->where('ngay_ban_hanh', $ngaybatdau);

                    }
                    if ($ngaybatdau == '' && $ngayketthuc != '') {
                        return $query->where('ngay_ban_hanh', $ngayketthuc);

                    }
                })
                ->where(function ($query) use ($tu_so, $den_so) {
                    if ($tu_so != '' && $den_so != '' && $tu_so <= $den_so) {

                        return $query->where('so_di', '>=', $tu_so)
                            ->where('so_di', '<=', $den_so);
                    }
                    if ($den_so == '' && $tu_so != '') {
                        return $query->where('so_di', $tu_so);

                    }
                    if ($tu_so == '' && $den_so != '') {
                        return $query->where('so_di', $den_so);

                    }
                })
                ->orderBy('created_at', 'desc');
        }
        $InDS = $ds_vanBanDi_tim->get();

        $ds_vanBanDi = $ds_vanBanDi_tim->paginate(10);
        $totalRecord = $ds_vanBanDi->total() + 1;

        if ($request->get('type') == 'pdf') {
            $fileName = 'in_so_van_ban_di' . date('d_m_Y') . '.pdf';

            $pdf = PDF::loadView('vanbandi::thong_ke.view_insovb_vbdi_pdf', compact('vanbandi', 'InDS'));

            return $pdf->download($fileName)->header('Content-Type', 'application/pdf');
        }

        //export word
        if ($request->get('type') == 'word') {
            $fileName = 'in_so_van_ban_di' . date('d_m_Y') . '.doc';

            $headers = array(
                "Content-type" => "text/html",
                "Content-Disposition" => "attachment;Filename=" . $fileName
            );

            $content = view('vanbandi::thong_ke.view_insovb_vbdi_pdf', compact('vanbandi', 'InDS'));

            return \Response::make($content, 200, $headers);

        }
        if ($request->get('type') == 'excel') {
            $fileName = 'in_so_van_ban_di' . date('d_m_Y') . '.xlsx';
            return Excel::download(new VanbandiExport($InDS, $totalRecord),
                $fileName);
        }


        if ($request->ajax()) {

            $html = view('vanbandi::thong_ke.view_insovb_vbdi', compact('vanbandi', 'ds_vanBanDi'))->render();;
            return response()->json([
                'html' => $html,
            ]);
        }


        return view('vanbandi::thong_ke.index', compact('vanbandi', 'ds_vanBanDi', 'sovanban'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('vanbandi::create');
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
        return view('vanbandi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('vanbandi::edit');
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
