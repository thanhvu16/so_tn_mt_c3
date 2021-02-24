<?php

namespace Modules\DanhGiaCanBo\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use auth;
use Modules\DanhGiaCanBo\Entities\DuyetDanhGia;

class ChiCucDanhGiaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $thang = $request->get('thang_danh_gia');
        $nguoinhan = null;
        $month = Carbon::now()->format('m');
        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case PHO_PHONG:
                $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                $nguoinhan = User::role([CHU_TICH, PHO_CHUC_TICH])->get();
                break;
            case PHO_CHUC_TICH:
                $nguoinhan = User::role([CHU_TICH])->get();
                break;
            case CHU_TICH:
                $nguoinhan = null;
                break;
            case CHANH_VAN_PHONG:
                $nguoinhan = User::role([PHO_CHUC_TICH, CHU_TICH])->get();
                break;
            case PHO_CHANH_VAN_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:
                $nguoinhan = User::role([CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;

        }
        $laydanhgiacanhan=null;
        if (empty($thang))
        {
            $laydanhgiacanhan = DuyetDanhGia::where(['thang' => $month, 'cap_danh_gia' => 1,'can_bo_goc'=>auth::user()->id])->first();
            $laydanhgiaphophong = DuyetDanhGia::where(['thang' => $month, 'cap_danh_gia' => 3,'can_bo_goc'=>auth::user()->id])->first();
            $laydanhgiatruongphong = DuyetDanhGia::where(['thang' => $month, 'cap_danh_gia' => 2,'can_bo_goc'=>auth::user()->id])->first();
        }else{
            $laydanhgiacanhan = DuyetDanhGia::where(['thang' => $thang, 'cap_danh_gia' => 1,'can_bo_goc'=>auth::user()->id])->first();
            $laydanhgiaphophong = DuyetDanhGia::where(['thang' => $thang, 'cap_danh_gia' => 3,'can_bo_goc'=>auth::user()->id])->first();
            $laydanhgiatruongphong = DuyetDanhGia::where(['thang' => $thang, 'cap_danh_gia' => 2,'can_bo_goc'=>auth::user()->id])->first();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('danhgiacanbo::create');
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
        return view('danhgiacanbo::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('danhgiacanbo::edit');
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
