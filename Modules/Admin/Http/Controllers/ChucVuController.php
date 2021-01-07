<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\NhomDonVi;
use Modules\Admin\Entities\NhomDonVi_chucVu;

class ChucVuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('admin::Chuc_vu.index');
    }

    public function danhsach(Request $request)
    {
        $tenchucvu = $request->get('ten_chuc_vu');
        $tenviettat = $request->get('ten_viet_tat');
        $ds_chucvu = ChucVu::wherenull('deleted_at')->orderBy('ten_chuc_vu', 'asc')
            ->where(function ($query) use ($tenchucvu) {
                if (!empty($tenchucvu)) {
                    return $query->where('ten_chuc_vu', 'LIKE', "%$tenchucvu%");
                }
            })->where(function ($query) use ($tenviettat) {
                if (!empty($tenviettat)) {
                    return $query->where('ten_viet_tat', 'LIKE', "%$tenviettat%");
                }
            })
            ->paginate(PER_PAGE);
        $nhom_don_vi = NhomDonVi::wherenull('deleted_at')->get();


        return view('admin::chuc_vu.danh_sach', compact('nhom_don_vi', 'ds_chucvu'));
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
        $nhomDonVi = $request->nhom_don_vi;
        $nhom_don_vi = json_encode($request->nhom_don_vi);
        $chucvu = new ChucVu();
        $chucvu->ten_chuc_vu = $request->ten_chuc_vu;
        $chucvu->ten_viet_tat = $request->ten_viet_tat;
        $chucvu->nhom_don_vi = $nhom_don_vi;
        $chucvu->save();
        if ($nhomDonVi && count($nhomDonVi) > 0) {
            foreach ($nhomDonVi as $item) {
                $nhom_don_vi_chuc_vu = new NhomDonVi_chucVu();
                $nhom_don_vi_chuc_vu->id_chuc_vu = $chucvu->id;
                $nhom_don_vi_chuc_vu->id_nhom_don_vi = $item;
                $nhom_don_vi_chuc_vu->save();
            }

        }


        return redirect()->route('danhsachchucvu')->with('success', 'Thêm mới thành công !');
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
        $lay_nhom_don_vi =NhomDonVi_chucVu::where('id_chuc_vu',$id)->get();
        $chucvu = ChucVu::where('id', $id)->first();
        $nhom_don_vi = NhomDonVi::wherenull('deleted_at')->get();
        return view('admin::Chuc_vu.edit', compact('chucvu', 'nhom_don_vi','lay_nhom_don_vi'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $nhom_don_vi = json_encode($request->nhom_don_vi);
        $chucvu = ChucVu::where('id', $id)->first();
        $chucvu->ten_chuc_vu = $request->ten_chuc_vu;
        $chucvu->ten_viet_tat = $request->ten_viet_tat;
        $chucvu->nhom_don_vi = $nhom_don_vi;
        $chucvu->save();
        $xoanhomcu = NhomDonVi_chucVu::where('id_chuc_vu', $chucvu->id)->get();
        if(count($xoanhomcu) > 0)
        {
            foreach ($xoanhomcu as $item) {
                $xoanhomcu = NhomDonVi_chucVu::where('id', $item->id)->first();
                $xoanhomcu->delete();
            }
        }

        $nhomDonVi = $request->nhom_don_vi;
        if ($nhomDonVi && count($nhomDonVi) > 0) {
            foreach ($nhomDonVi as $item) {
                $nhom_don_vi_chuc_vu = new NhomDonVi_chucVu();
                $nhom_don_vi_chuc_vu->id_chuc_vu = $chucvu->id;
                $nhom_don_vi_chuc_vu->id_nhom_don_vi = $item;
                $nhom_don_vi_chuc_vu->save();
            }

        }
        return redirect()->route('danhsachchucvu')->with('success', 'Cập nhật thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $chucvu = ChucVu::where('id', $id)->first();
        $xoanhomcu = NhomDonVi_chucVu::where('id_chuc_vu', $chucvu->id)->get();
        foreach ($xoanhomcu as $item) {
            $xoanhomcu = NhomDonVi_chucVu::where('id', $item->id)->first();
            $xoanhomcu->delete();
        }
        $chucvu->delete();

        return redirect()->route('danhsachchucvu')->with('success', 'Xóa thành công !');
    }
}

