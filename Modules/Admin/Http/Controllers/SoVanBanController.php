<?php

namespace Modules\Admin\Http\Controllers;

use App\Common\AllPermission;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\SoVanBan;

class SoVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        canPermission(AllPermission::themSoVanBan());
        $donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')->get();
        return view('admin::So_van_ban.index', compact('donvi'));
    }

    public function danhsach(Request $request)
    {
        canPermission(AllPermission::themSoVanBan());
        $donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')->get();
        $tendonvi = $request->get('ten_don_vi');
        $tenviettat = $request->get('ten_viet_tat');
        $loaiso = $request->get('loai_so');
        $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')
            ->where(function ($query) use ($tendonvi) {
                if (!empty($tendonvi)) {
                    return $query->where('ten_so_van_ban', 'LIKE', "%$tendonvi%");
                }
            })->where(function ($query) use ($tenviettat) {
                if (!empty($tenviettat)) {
                    return $query->where('ten_viet_tat', 'LIKE', "%$tenviettat%");
                }
            })
            ->where(function ($query) use ($loaiso) {
                if (!empty($loaiso)) {
                    return $query->where('loai_so', 'LIKE', "%$loaiso%");
                }
            })->paginate(PER_PAGE);
        return view('admin::So_van_ban.danh_sach', compact('ds_sovanban','donvi'));
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
        canPermission(AllPermission::themSoVanBan());
        $sovanban = new SoVanBan();
        $sovanban->ten_so_van_ban = $request->ten_so_van_ban;
        $sovanban->ten_viet_tat = $request->ten_viet_tat;
        $sovanban->loai_so = $request->loai_so;
        if($request->loai_so == 4)
        {
            $sovanban->so_don_vi = $request->don_vi;
            $sovanban->type = $request->ap_dung;
        }
        $sovanban->mo_ta = $request->mo_ta;
        $sovanban->save();
        return redirect()->route('danhsachsovanban')->with('success', 'Thêm mới thành công !');
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
        canPermission(AllPermission::suaSoVanBan());
        $sovanban= SoVanBan::where('id', $id)->first();
        $donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')->get();
        return view('admin::So_van_ban.edit', compact('sovanban','donvi'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
//        canPermission(AllPermission::suaSoVanBan());
        $sovanban= SoVanBan::where('id', $id)->first();
        $sovanban->ten_so_van_ban = $request->ten_so_van_ban;
        $sovanban->ten_viet_tat = $request->ten_viet_tat;
        $sovanban->loai_so = $request->loai_so;
        if($request->loai_so == 4)
        {
            $sovanban->so_don_vi = $request->don_vi;
            $sovanban->type = $request->ap_dung;
        }
        $sovanban->mo_ta = $request->mo_ta;
        $sovanban->save();
        return redirect()->route('danhsachsovanban')->with('success', 'Cập nhât thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaSoVanBan());
        $sovanban= SoVanBan::where('id', $id)->first();
        $sovanban->delete();
        return redirect()->route('danhsachsovanban')->with('success', 'Xóa thành công !');
    }
}
