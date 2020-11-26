<?php

namespace Modules\GiayMoiDen\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDen\Entities\VanBanDen;
use File, auth;

class GiayMoiDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $ds_vanBanDen = VanBanDen::where([
            'don_vi_id' => $this->user->donvi_id,
            'so_van_ban_id' => 100
        ])->orderBy('ngay_tao', 'desc')->paginate($this->config['per_page']);
        return view('giaymoiden::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $soDen = VanBanDen::where([
            'don_vi_id' => auth::user()->don_vi_id,
            'so_van_ban_id' => 100
        ])->max('so_den');
        $date = Carbon::now()->format('Y-m-d');
//        dd($date);
//        dd($soDen);
        $sodengiaymoi = $soDen + 1;
        $gioHop = [];

        for ($i = 6; $i <= 20; $i++) {
            if ($i < 10) {
                $i = '0' . $i;
            }
            $gioHop[] = $i . ':00';
            $gioHop[] = $i . ':15';
            $gioHop[] = $i . ':30';
            $gioHop[] = $i . ':45';
        }



        $user= auth::user();
        $ds_nguoiKy = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan =LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();


        return view('giaymoiden::giay_moi_den.create',compact( 'ds_nguoiKy', 'ds_soVanBan', 'ds_loaiVanBan',
            'ds_doKhanCap', 'ds_mucBaoMat' , 'sodengiaymoi',
            'gioHop', 'date', 'nguoi_dung'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('giaymoiden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('giaymoiden::edit');
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
