<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\NhomDonVi;

class NhomDonViController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $tendonvi = $request->get('ten_nhom_don_vi');
        $ds_donvi = NhomDonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')
            ->where(function ($query) use ($tendonvi) {
                if (!empty($tendonvi)) {
                    return $query->where('ten_nhom_don_vi', 'LIKE', "%$tendonvi%");
                }
            })->paginate(PER_PAGE);
        return view('admin::nhom_don_vi.danh_sach', compact('ds_donvi'));
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
        $donvi = new NhomDonVi();
        $donvi->ten_nhom_don_vi = $request->ten_nhom_don_vi;
        $donvi->mo_ta = $request->mo_ta;
        $donvi->save();
        return redirect()->route('Nhom-don-vi.index')->with('success', 'Thêm mới thành công !');
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
        $donvi = NhomDonVi::where('id', $id)->first();
        return view('admin::nhom_don_vi.edit', compact('donvi'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $donvi = NhomDonVi::where('id', $id)->first();
        $donvi->ten_nhom_don_vi = $request->ten_nhom_don_vi;
        $donvi->mo_ta = $request->mo_ta;
        $donvi->save();
        return redirect()->route('Nhom-don-vi.index')->with('success', 'Cập nhật thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $phat = NhomDonVi::where('id', $id)->first();
        $phat->delete();
        return redirect()->route('Nhom-don-vi.index')->with('success', 'Xóa thành công !');
    }
}
