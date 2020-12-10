<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Modules\LayVanBanTuEmail\Entities\GetEmail;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\VanBanDi;
use Modules\VanBanDi\Entities\VanBanDiChoDuyet;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */


    public function index()
    {
        $vanThuVanBanDiPiceCharts = [];
        $vanThuVanBanDenPiceCharts = [];
        $vanThuVanBanDiCoLors = [];
        $vanThuVanBanDenCoLors = [];
        $duThaoCoLors = [];
        $duThaoPiceCharts = [];
        $user = auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('nguoi-dung.index');
        }
        $danhSachDuThao = Duthaovanbandi::where(['nguoi_tao' => auth::user()->id, 'stt' => 1])->count();
        $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi'=> 1, 'don_vi_soan_thao'=> auth::user()->don_vi_id])->where('so_di', '!=', null)->whereNull('deleted_at')->count();
        $ds_vanBanDen = VanBanDen::where('so_van_ban_id', '!=', 100)->whereNull('deleted_at')->count();
        $homthucong = GetEmail::where(['mail_active' => 1])->count();
        $vanbandichoduyet = Vanbandichoduyet::where(['can_bo_nhan_id' => auth::user()->id, 'trang_thai' => 1])->count();
        $vanbandichoso = VanBanDi::where(['cho_cap_so' => 2,'don_vi_soan_thao'=> auth::user()->don_vi_id])->count();
        $ds_giaymoiden = VanBanDen::where(['don_vi_id' => auth::user()->don_vi_id, 'so_van_ban_id' => 100])->count();
        $ds_giaymoidi = VanBanDi::where(['loai_van_ban_giay_moi' => 2, 'loai_van_ban_id' => 1000])->where('so_di', '!=', '')->whereNull('deleted_at')->count();
        $van_ban_di_tra_lai = Vanbandichoduyet::where(['can_bo_nhan_id' => auth::user()->id, 'trang_thai' => 0, 'tra_lai' => 1])->count();
        $canbogopy = CanBoPhongDuThao::where(['can_bo_id' => auth::user()->id, 'trang_thai' => 1])->get();
        $key2 = count($canbogopy);
        $canbogopyngoai = CanBoPhongDuThaoKhac::where(['can_bo_id' => auth::user()->id, 'trang_thai' => 1])->get();
        $key1 = count($canbogopyngoai);
        $gopy = $key2+$key1;
        //văn bản đến

        //$vanThuVanBanDenPiceCharts
        array_push($vanThuVanBanDenPiceCharts, array('Task', 'Danh sách'));
        array_push($vanThuVanBanDenPiceCharts, array('Hòm thư công', $homthucong));
        array_push($vanThuVanBanDenPiceCharts, array('Danh sách văn bản đến', $ds_vanBanDen));
        array_push($vanThuVanBanDenPiceCharts, array('Danh sách giấy mời đến', $ds_giaymoiden));
        //màu
        array_push($vanThuVanBanDenCoLors, COLOR_INFO_SHADOW);
        array_push($vanThuVanBanDenCoLors, COLOR_PINTEREST);
        array_push($vanThuVanBanDenCoLors, COLOR_WARNING);

        //văn bản đi
        array_push($vanThuVanBanDiCoLors, COLOR_INFO_SHADOW);
        array_push($vanThuVanBanDiCoLors, COLOR_WARNING);
        array_push($vanThuVanBanDiCoLors, COLOR_PINTEREST);
        //màu
        array_push($vanThuVanBanDiPiceCharts, array('Task', 'Danh sách'));
        array_push($vanThuVanBanDiPiceCharts, array('Văn bản đi chờ số', $vanbandichoso));
        array_push($vanThuVanBanDiPiceCharts, array('Danh sách văn bản đi', $ds_vanBanDi));
        array_push($vanThuVanBanDiPiceCharts, array('Danh sách giấy mời đi', $ds_giaymoidi));

        //dự thảo văn bản đi
        array_push($duThaoCoLors, COLOR_WARNING);
        array_push($duThaoCoLors, COLOR_INFO);
        array_push($duThaoCoLors, COLOR_GREEN);
        array_push($duThaoCoLors, COLOR_PINTEREST);
        //màu
        array_push($duThaoPiceCharts, array('Task', 'Danh sách'));
        array_push($duThaoPiceCharts, array('Danh sách cá nhân dự thảo',$danhSachDuThao));
        array_push($duThaoPiceCharts, array('dự thảo chờ góp ý',$gopy));
        array_push($duThaoPiceCharts, array('Danh sách văn bản đi chờ duyệt', $vanbandichoduyet));
        array_push($duThaoPiceCharts, array('Danh sách văn bản trả lại', $van_ban_di_tra_lai));

//        array_push($duThaoPiceCharts, array('Task', 'Danh sách'));
//        array_push($duThaoPiceCharts, array('Danh sách cá nhân dự thảo', $danhSachDuThao));
//        array_push($duThaoPiceCharts, array('dự thảo chờ góp ý', $gopy));
//        array_push($duThaoPiceCharts, array('Danh sách văn bản đi chờ duyệt', $vanbandichoduyet));
//        array_push($duThaoPiceCharts, array('Danh sách văn bản trả lại', $van_ban_di_tra_lai));



        return view('admin::index',compact(
//            'getEmail' => $getEmail,
            'danhSachDuThao' ,
            'ds_vanBanDi' ,
            'ds_vanBanDen' ,
            'homthucong' ,
            'vanbandichoduyet' ,
            'vanThuVanBanDiPiceCharts',
            'vanThuVanBanDiCoLors',
            'vanbandichoso' ,
            'van_ban_di_tra_lai',
            'vanThuVanBanDenCoLors',
            'vanThuVanBanDenPiceCharts',
            'ds_giaymoiden',
            'gopy',
            'duThaoPiceCharts',
            'duThaoCoLors',
            'ds_giaymoidi'
        ));
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
        //
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
        return view('admin::edit');
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
