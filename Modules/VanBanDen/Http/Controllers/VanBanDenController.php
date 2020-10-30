<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\QlvbVbDenDonVi as VbDenDonVi;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use auth;
use Modules\VanBanDen\Entities\VanBanDen;

class VanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        return view('vanbanden::van_ban_den.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $user = auth::user();
        $user ->can('văn thư đơn vị');
//        dd($user ->can('văn thư đơn vị'));
//        if($user ->can('văn thư huyện') == true)
//        {
//            $soDen = VanBanDen::where([
//            'don_vi_id' => $datadonvi->ma_don_vi_cha,
//            'so_van_ban_id' => $request->so_van_ban_id,
//            'trang_thai' => 1
//        ])->whereYear('vb_ngay_ban_hanh', '=', $nam_sodi)->max('vb_so_den');
//        }else{
//            dd(0);
//        }

//        $soDenvb = $soDen + 1;
        $domat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $sovanban = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission('tham mưu')->where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();

        return view('vanbanden::van_ban_den.create',compact('domat','dokhan','loaivanban','sovanban','users'));
    }

    public function laysoden(Request $request)
    {
        $nam = date("Y");
        $soDenvb = VanBanDen::where([
//            'don_vi_id' => $request->donViId,
            'so_van_ban_id' => $request->soVanBanId
//            'trang_thai' => 1
        ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        $soDen= $soDenvb+1;
//        dd($soDen);
            return response()->json(
                [
                    'html' => $soDen
                ]
            );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $user = auth::user();
        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($requestData['noi_dung']) ? $requestData['noi_dung'] : null;
        if ($noi_dung && $noi_dung[0] != null) {
            foreach ($noi_dung as $key => $data) {
                $vanbandv = new VanBanDen();
                $vanbandv->loai_van_ban_id = $request->loai_van_ban;
                $vanbandv->so_van_ban_id = $request->so_van_ban;
                $vanbandv->so_den = $request->so_den;
                $vanbandv->so_ky_hieu = $request->so_ky_hieu;
                $vanbandv->ngay_ban_hanh = $request->ngay_ban_hanh;
                $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
                $vanbandv->trich_yeu = $request->trich_yeu;
                $vanbandv->nguoi_ky = $request->nguoi_ky;
                $vanbandv->do_khan_cap_id = $request->do_khan;
                $vanbandv->do_bao_mat_id = $request->do_mat;
//                $vanbandv->han_xu_ly = $request->han_xu_ly;
                $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
//                $vanbandv->don_vi_id = $user->don_vi_id;
//                $vanbandv->nguoi_tao = $user->id;
                $vanbandv->noi_dung = $data;
                if ($request->han_giai_quyet[$key] == null) {
                    $vanbandv->han_xu_ly = $request->han_xu_ly;
                } else {
                    $vanbandv->han_xu_ly = $han_gq[$key];
                }

                $vanbandv->save();
            }
        } else {
            $vanbandv = new VanBanDen();
            $vanbandv->loai_van_ban_id = $request->loai_van_ban;
            $vanbandv->so_van_ban_id = $request->so_van_ban;
            $vanbandv->so_den = $request->so_den;
            $vanbandv->so_ky_hieu = $request->so_ky_hieu;
            $vanbandv->ngay_ban_hanh = $request->ngay_ban_hanh;
            $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
            $vanbandv->trich_yeu = $request->trich_yeu;
            $vanbandv->nguoi_ky = $request->nguoi_ky;
            $vanbandv->do_khan_cap_id = $request->do_khan;
            $vanbandv->do_bao_mat_id = $request->do_mat;
            $vanbandv->han_xu_ly = $request->han_xu_ly;
            $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
//            $vanbandv->don_vi_id = $user->don_vi_id;
//            $vanbandv->nguoi_tao = $user->id;
            $vanbandv->save();
        }
        return redirect()->back()->with('success','Thêm văn bản thành công !!');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('vanbanden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('vanbanden::edit');
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
