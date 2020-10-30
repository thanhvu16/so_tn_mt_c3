<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use auth;

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
//            dd(1);
//        }else{
//            dd(0);
//        }
        $soden =0;
        $domat = DoMat::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $sovanban = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission('tham mưu')->where('trang_thai', ACTIVE)->get();

        return view('vanbanden::van_ban_den.create',compact('domat','dokhan','loaivanban','sovanban','users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
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
