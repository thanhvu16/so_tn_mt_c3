<?php

namespace Modules\Admin\Http\Controllers;

use App\Common\AllPermission;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\VanBanDen\Entities\TieuChuanVanBan;

class TieuChuanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $tentieuchuan = $request->get('ten_tieu_chuan');


        $ds_tieuChuan = TieuChuanVanBan::wherenull('deleted_at')->orderBy('ten_tieu_chuan', 'asc')
            ->where(function ($query) use ($tentieuchuan) {
                if (!empty($tentieuchuan)) {
                    return $query->where('ten_tieu_chuan', 'LIKE', "%$tentieuchuan%");
                }
            })->paginate(PER_PAGE);
        return view('admin::tieu_chuan.danh_sach', compact('ds_tieuChuan'));
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
        $tieuchuan = new TieuChuanVanBan();
        $tieuchuan->ten_tieu_chuan = $request->ten_tieu_chuan;
        $tieuchuan->so_ngay = $request->so_ngay;
        $tieuchuan->mo_ta = $request->mo_ta;
        $tieuchuan->save();
        return redirect()->back()->with('success', 'Thêm mới thành công !');
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
        $tieuChuan = TieuChuanVanBan::where('id', $id)->first();
        return view('admin::tieu_chuan.edit', compact('tieuChuan'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $tieuchuan = TieuChuanVanBan::where('id', $id)->first();
        $tieuchuan->ten_tieu_chuan = $request->ten_tieu_chuan;
        $tieuchuan->so_ngay = $request->so_ngay;
        $tieuchuan->mo_ta = $request->mo_ta;
        $tieuchuan->save();
        return redirect()->back()->with('success', 'Cập nhật thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaTieuChuan());
        $loaivanban= TieuChuanVanBan::where('id', $id)->first();
        $loaivanban->delete();
        return redirect()->back()->with('success', 'Xóa thành công !');
    }
}
