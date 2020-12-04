<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\NgayNghi;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use auth ;

class DonViNhanVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request )
    {
        $hienthi = $request->get('don_vi_van_ban');
        $donvinhan = NoiNhanVanBanDi::where(['don_vi_id_nhan'=>auth::user()->don_vi_id])->whereIn('trang_thai',[1,2,3])
            ->where(function ($query) use ($hienthi) {
                if (!empty($hienthi)) {
                    return $query->where('trang_thai', "$hienthi");
                }
            })
            ->paginate(PER_PAGE);
        return view('vanbanden::don_vi_nhan_van_ban.index',compact('donvinhan'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('vanbanden::create');
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
        $user = auth::user();
        $domat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $sovanban = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission('tham mưu')->where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $ngaynhan = date('Y-m-d');
        $songay = 10;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;

        $van_ban_den = NoiNhanVanBanDi::where('id',$id)->first();

        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }

        }

        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        return view('vanbanden::don_vi_nhan_van_ban.edit',compact('dokhan','domat','loaivanban','sovanban','users','hangiaiquyet','van_ban_den'));
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
