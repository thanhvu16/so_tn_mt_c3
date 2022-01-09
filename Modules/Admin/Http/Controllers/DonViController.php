<?php

namespace Modules\Admin\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\NhomDonVi;

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
        $donVi = DonVi::where('ten_don_vi', 'LIKE', "%$tendonvi%")->first();

        $ds_donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')
            ->where(function ($query) use ($tendonvi, $donVi) {
                if (!empty($tendonvi)) {
                    return $query->where('ten_don_vi', 'LIKE', "%$tendonvi%")
                                    ->orWhere('parent_id', $donVi->id ?? 0);
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
        $nhom_don_vi = NhomDonVi::wherenull('deleted_at')->get();

        $donViCapXa = DonVi::whereNotNull('cap_xa')->select('id', 'ten_don_vi')->get();

        return view('admin::Don_vi.danh_sach', compact('ds_donvi','nhom_don_vi', 'donViCapXa'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $nhom_don_vi = NhomDonVi::wherenull('deleted_at')->get();
        $donViCapXa = DonVi::whereNotNull('cap_xa')->select('id', 'ten_don_vi')->get();

        return view('admin::Don_vi.create', compact('nhom_don_vi', 'donViCapXa'));
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
        $donvi->nhom_don_vi = $request->nhom_don_vi;
        $donvi->cap_xa = $request->cap_xa ?? null;
        $donvi->cap_chi_nhanh = $request->cap_chi_nhanh ?? null;
        if ($request->check_parent == 1) {
            $donvi->parent_id = $request->get('parent_id');
        }
        if (!empty($request->dieu_hanh) && strpos(strtolower($request->get('ten_don_vi')), TXT_CHI_CUC) !== false) {
            $donvi->type = DonVi::TYPE_CHI_CUC;
        } else {
            $donvi->type = DonVi::TYPE_TRUNG_TAM;
        }
        $donvi->save();

        // check update nguoi dung
        User::where('don_vi_id', $donvi->id)->update([
            'cap_xa' => $donvi->cap_xa
        ]);

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
        $nhom_don_vi = NhomDonVi::wherenull('deleted_at')->get();
        $donViCapXa = DonVi::whereNotNull('cap_xa')->select('id', 'ten_don_vi')->get();

        if ($donvi->parent_id != 0) {

            return view('admin::Don_vi.edit_cap_phong_ban', compact('donvi','nhom_don_vi', 'donViCapXa'));

        }

        return view('admin::Don_vi.edit', compact('donvi','nhom_don_vi', 'donViCapXa'));
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
        $donvi->nhom_don_vi = $request->nhom_don_vi;
        $donvi->cap_xa = $request->cap_xa ?? null;
        $donvi->parent_id = 0;
        if ($request->check_parent == 1) {
            $donvi->parent_id = $request->get('parent_id');
        }
        if (!empty($request->dieu_hanh) && strpos(strtolower($request->get('ten_don_vi')), TXT_CHI_CUC) !== false) {
            $donvi->type = DonVi::TYPE_CHI_CUC;
        } else {
            $donvi->type = DonVi::TYPE_TRUNG_TAM;
        }
        $donvi->save();

        // check update nguoi dung
        User::where('don_vi_id', $donvi->id)->update([
            'cap_xa' => $donvi->cap_xa
        ]);

        return redirect()->route('danhsachdonvi')->with('success', 'Cập nhật thành công !');
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

    public function getListPhongBan(Request $request, $id)
    {
        $phongBan = DonVi::where('parent_id', $id)->select('id', 'ten_don_vi', 'parent_id')->get();

        return response()->json([
            'success' => true,
            'data' => $phongBan
        ]);
    }
}
