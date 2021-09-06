<?php

namespace Modules\Admin\Http\Controllers;

use App\Common\AllPermission;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use App\Http\Requests\LoaiVanBanRequest;

class LoaiVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        canPermission(AllPermission::themLoaiVanBan());
        $donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')->get();
        return view('admin::Loai_van_ban.index', compact('donvi'));
    }

    public function danhsach(Request $request)
    {
        canPermission(AllPermission::themLoaiVanBan());
        $donvi = DonVi::wherenull('deleted_at')->orderBy('ten_don_vi', 'asc')->get();
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
            })->orderBy('thu_tu', 'asc')->paginate(PER_PAGE);
        return view('admin::Loai_van_ban.danh_sach', compact('ds_loaivanban','donvi'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */

    public function dataSort(Request $request)
    {

        $dataSort = $request->all();

        for($i=0;$i<count($dataSort['sovanban_id']); $i++){
            LoaiVanBan::where('id',$dataSort['sovanban_id'][$i])->update(['thu_tu' => $dataSort['a_sapXep'][$i]]);
        }
        return redirect()->route('danhsachloaivanban')
            ->with('success','Đã sắp xếp lại thứ tự!');

    }
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(LoaiVanBanRequest $request)
    {
        canPermission(AllPermission::themLoaiVanBan());
//        dd($request->all());
        $loaivanban = new LoaiVanBan();
        $loaivanban->ten_loai_van_ban = $request->ten_loai_van_ban;
        $loaivanban->ten_viet_tat = $request->ten_viet_tat;
        $loaivanban->nam_truoc_skh = $request->nam_truoc_skh;
        $loaivanban->ma_van_ban = $request->ma_van_ban;
        $loaivanban->ma_phong_ban = $request->ma_phong_ban;
        $loaivanban->ma_don_vi = $request->ma_don_vi;
        $loaivanban->loai_van_ban = $request->loai_so;
        if($request->loai_so == 4)
        {
            $loaivanban->loai_don_vi = $request->don_vi;
        }
        $loaivanban->mo_ta = $request->mo_ta;
        $loaivanban->save();
//        return redirect()->back()->with(['capso'=>"$soDi"]);
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
        canPermission(AllPermission::suaLoaiVanBan());
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
        $loaivanban->nam_truoc_skh = $request->nam_truoc_skh;
        $loaivanban->ma_van_ban = $request->ma_van_ban;
        $loaivanban->ma_phong_ban = $request->ma_phong_ban;
        $loaivanban->ma_don_vi = $request->ma_don_vi;
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
        canPermission(AllPermission::xoaLoaiVanBan());
        $loaivanban= LoaiVanBan::where('id', $id)->first();
        $loaivanban->delete();
        return redirect()->route('danhsachloaivanban')->with('success', 'Xóa thành công !');
    }
}
