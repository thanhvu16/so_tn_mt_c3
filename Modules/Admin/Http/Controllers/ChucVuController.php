<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\ChucVu;

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
        $ds_chucvu = ChucVu::wherenull('deleted_at')->orderBy('ten_chuc_vu','asc')
            ->where(function ($query) use ($tenchucvu) {
                if (!empty($tenchucvu)) {
                    return $query->where('ten_chuc_vu', 'LIKE', "%$tenchucvu%");
                }
            })->where(function ($query) use ($tenviettat) {
                if (!empty($tenviettat)) {
                    return $query->where('ten_viet_tat', 'LIKE', "%$tenviettat%");
                }
            })
            ->paginate(5);
        return view('admin::Chuc_vu.danh_sach',compact('ds_chucvu'));
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
        $chucvu = new ChucVu();
        $chucvu->ten_chuc_vu = $request->ten_chuc_vu;
        $chucvu->ten_viet_tat = $request->ten_viet_tat;
        $chucvu->save();
        return redirect()->route('danhsachchucvu')->with('success','Thêm mới thành công !');
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
        $chucvu = ChucVu::where('id',$id)->first();
        return view('admin::Chuc_vu.edit',compact('chucvu'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $chucvu = ChucVu::where('id',$id)->first();
        $chucvu->ten_chuc_vu = $request->ten_chuc_vu;
        $chucvu->ten_viet_tat = $request->ten_viet_tat;
        $chucvu->save();
        return redirect()->route('danhsachchucvu')->with('success','Cập nhật thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $chucvu = ChucVu::where('id',$id)->first();
        $chucvu->delete();
        return redirect()->route('danhsachchucvu')->with('success','Xóa thành công !');
    }
}

