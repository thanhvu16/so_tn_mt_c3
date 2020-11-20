<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;

class DonViController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        return view('admin::Don_vi.index');
    }

    public function danhsach(Request $request)
    {
        $tendonvi = $request->get('ten_don_vi');
        $tenviettat = $request->get('ten_viet_tat');
        $mahanhchinh = $request->get('ma_hanh_chinh');
        $ds_donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')
            ->where(function ($query) use ($tendonvi) {
                if (!empty($tendonvi)) {
                    return $query->where('ten_don_vi', 'LIKE', "%$tendonvi%");
                }
            })->where(function ($query) use ($tenviettat) {
                if (!empty($tenviettat)) {
                    return $query->where('ten_viet_tat', 'LIKE', "%$tenviettat%");
                }
            })
            ->where(function ($query) use ($mahanhchinh) {
                if (!empty($mahanhchinh)) {
                    return $query->where('ma_hanh_chinh', 'LIKE', "%$mahanhchinh%");
                }
            })->paginate(PER_PAGE);
        return view('admin::Don_vi.danh_sach', compact('ds_donvi'));
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

        $donvi = new DonVi();
        $donvi->ten_don_vi = $request->ten_don_vi;
        $donvi->ten_viet_tat = $request->ten_viet_tat;
        $donvi->ma_hanh_chinh = $request->ma_hanh_chinh;
        $donvi->dia_chi = $request->dia_chi;
        $donvi->so_dien_thoai = $request->dien_thoai;
        $donvi->email = $request->email;
        $donvi->dieu_hanh = $request->dieu_hanh;
        $donvi->save();
        return redirect()->route('danhsachdonvi')->with('success', 'Thêm mới thành công !');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $donvi = DonVi::where('id', $id)->first();
        return view('admin::Don_vi.edit', compact('donvi'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $donvi = DonVi::where('id', $id)->first();
        $donvi->ten_don_vi = $request->ten_don_vi;
        $donvi->ten_viet_tat = $request->ten_viet_tat;
        $donvi->ma_hanh_chinh = $request->ma_hanh_chinh;
        $donvi->dia_chi = $request->dia_chi;
        $donvi->so_dien_thoai = $request->dien_thoai;
        $donvi->email = $request->email;
        $donvi->dieu_hanh = $request->dieu_hanh;
        $donvi->save();
        return redirect()->route('danhsachdonvi')->with('success', 'Thêm mới thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $phat = DonVi::where('id', $id)->first();
        $phat->delete();
        return redirect()->route('danhsachdonvi')->with('success', 'Xóa thành công !');
    }
}
