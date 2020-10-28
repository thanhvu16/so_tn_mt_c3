<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;

class LoaiVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')->get();
        return view('admin::Loai_van_ban.index', compact('donvi'));
    }

    public function danhsach(Request $request)
    {
        $tenloaivanban = $request->get('ten_loai_van_ban');
        $tenviettat = $request->get('ten_viet_tat');
        $loaiapdung = $request->get('loai_ap_dung');
        $ds_loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')
            ->where(function ($query) use ($tenloaivanban) {
                if (!empty($tenloaivanban)) {
                    return $query->where('ten_loai_van_ban', 'LIKE', "%$tenloaivanban%");
                }
            })->where(function ($query) use ($tenviettat) {
                if (!empty($tenviettat)) {
                    return $query->where('ten_viet_tat', 'LIKE', "%$tenviettat%");
                }
            })
            ->where(function ($query) use ($loaiapdung) {
                if (!empty($loaiapdung)) {
                    return $query->where('loai_van_ban', 'LIKE', "%$loaiapdung%");
                }
            })->paginate(PER_PAGE);
        return view('admin::Loai_van_ban.danh_sach', compact('ds_loaivanban'));
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
        $loaivanban = new LoaiVanBan();
        $loaivanban->ten_loai_van_ban = $request->ten_loai_van_ban;
        $loaivanban->ten_viet_tat = $request->ten_viet_tat;
        $loaivanban->loai_van_ban = $request->loai_so;
        if($request->loai_so == 4)
        {
            $loaivanban->loai_don_vi = $request->don_vi;
        }
        $loaivanban->mo_ta = $request->mo_ta;
        $loaivanban->save();
        return redirect()->route('danhsachloaivanban')->with('success', 'Thêm mới thành công !');
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
        $loaivanban= LoaiVanBan::where('id', $id)->first();
        $donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')->get();
        return view('admin::Loai_van_ban.edit', compact('loaivanban','donvi'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $loaivanban= LoaiVanBan::where('id', $id)->first();
        $loaivanban->ten_loai_van_ban = $request->ten_loai_van_ban;
        $loaivanban->ten_viet_tat = $request->ten_viet_tat;
        $loaivanban->loai_van_ban = $request->loai_van_ban;
        if($request->loai_van_ban == 4)
        {
            $loaivanban->loai_don_vi = $request->don_vi;
        }else{
            $loaivanban->loai_don_vi = null;
        }
        $loaivanban->mo_ta = $request->mo_ta;
        $loaivanban->save();
        return redirect()->route('danhsachloaivanban')->with('success', 'Cập nhât thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $loaivanban= LoaiVanBan::where('id', $id)->first();
        $loaivanban->delete();
        return redirect()->route('danhsachloaivanban')->with('success', 'Xóa thành công !');
    }
}
