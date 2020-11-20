<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Http\Controllers\Controller;
use App\Models\QlvbVbDenDonVi as VbDenDonVi;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use File, auth, DB;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;

class VanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $trichyeu = $request->get('vb_trich_yeu');
        $so_ky_hieu = $request->get('vb_so_ky_hieu');
        $co_quan_ban_hanh = $request->get('co_quan_ban_hanh_id');

        $so_den = $request->get('vb_so_den');
        $loai_van_ban = $request->get('loai_van_ban_id');
        $so_van_ban = $request->get('so_van_ban_id');
        $nguoi_ky = $request->get('nguoi_ky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $ds_vanBanDen = VanBanDen::
//            where([
//                'don_vi_id' => $donvi->ma_don_vi_cha,
//                'type' => 2,
//                'trang_thai' => 1
            where('so_van_ban_id', '!=', 100)
            ->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where('vb_trich_yeu', 'LIKE', "%$trichyeu%");
                }
            })->where(function ($query) use ($so_den) {
                if (!empty($so_den)) {
                    return $query->where('so_den', 'LIKE', "%$so_den%");
                }
            })
            ->where(function ($query) use ($co_quan_ban_hanh) {
                if (!empty($co_quan_ban_hanh)) {
                    return $query->where('co_quan_ban_hanh', 'LIKE', "%$co_quan_ban_hanh%");
                }
            })
            ->where(function ($query) use ($nguoi_ky) {
                if (!empty($nguoi_ky)) {
                    return $query->where('nguoi_ky', 'LIKE', "%$nguoi_ky%");
                }
            })
            ->where(function ($query) use ($so_ky_hieu) {
                if (!empty($so_ky_hieu)) {
                    return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
                }
            })->where(function ($query) use ($loai_van_ban) {
                if (!empty($loai_van_ban)) {
                    return $query->where('loai_van_ban_id', "$loai_van_ban");
                }
            })->where(function ($query) use ($so_van_ban) {
                if (!empty($so_van_ban)) {
                    return $query->where('so_van_ban_id', "$so_van_ban");
                }
            })
            ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                    return $query->where('ngay_ban_hanh', '>=', $ngaybatdau)
                        ->where('ngay_ban_hanh', '<=', $ngayketthuc);
                }
                if ( $ngayketthuc == '' && $ngaybatdau != ''  ) {
                    return $query->where('ngay_ban_hanh', $ngaybatdau);

                }
                if ($ngaybatdau == '' && $ngayketthuc != '' ) {
                    return $query->where('ngay_ban_hanh', $ngayketthuc);

                }
            })
            ->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        $ds_loaiVanBan  = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $ds_soVanBan = $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('ten_muc_do','asc')->get();
        $ds_mucBaoMat =DoMat::wherenull('deleted_at')->orderBy('ten_muc_do','asc')->get();

        return view('vanbanden::van_ban_den.index',compact('ds_vanBanDen','ds_soVanBan','ds_doKhanCap','ds_mucBaoMat','ds_loaiVanBan'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themVanBanDen());
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
                $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                $vanbandv->don_vi_id = auth::user()->don_vi_id;
                $vanbandv->nguoi_tao = auth::user()->id;
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
            $vanbandv->don_vi_id = auth::user()->don_vi_id;
            $vanbandv->nguoi_tao = auth::user()->id;
            $vanbandv->save();
        }
        return redirect()->back()->with('success','Thêm văn bản thành công !!');
    }

    public function multiple_file(Request $request)
    {

        $uploadPath = UPLOAD_FILE_VAN_BAN_DEN;
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true, true);
        }
        $txtFiles = !empty($request['txt_file']) ? $request['txt_file'] : null;
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        if (empty($multiFiles) || count($multiFiles) == 0 || (count($multiFiles) > 19)) {
            return redirect()->back()->with('warning', 'Bạn phải chọn file hoặc phải chọn số lượng file nhỏ hơn 20 file   !');
        }
        foreach ($multiFiles as $key => $getFile) {
            $typeArray = explode('.', $getFile->getClientOriginalName());
            $tenchinhfile = strtolower($typeArray[0]);
            $extFile = $getFile->extension();
            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $urlFile = UPLOAD_FILE_VAN_BAN_DEN  . '/' . $fileName;
            $tachchuoi = explode("-", $tenchinhfile);
            $tenviettatso = strtoupper($tachchuoi[0]);
            $soden = (int)$tachchuoi[1];
            $yearsfile = (int)$tachchuoi[2];
            $sovanban = SoVanBan::where(['ten_viet_tat' => $tenviettatso])->whereNull('deleted_at')->first();
            $vanban = VanBanDen::where(['so_van_ban_id' => $sovanban->id, 'so_den' => $soden])->whereYear('ngay_ban_hanh', '=', $yearsfile)->first();
            if ($vanban) {
                $vbDenFile = new FileVanBanDen();
                $getFile->move($uploadPath, $fileName);
                $vbDenFile->ten_file = $tenchinhfile;
                $vbDenFile->duong_dan = $urlFile;
                $vbDenFile->duoi_file = $extFile;
                $vbDenFile->vb_den_id = $vanban->id;
                $vbDenFile->nguoi_dung_id = auth::user()->id;
                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                $vbDenFile->save();
            }

        }

        return redirect()->back()->with('success', 'Thêm file thành công !');
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

    public function chi_tiet_van_ban_den($id)
    {
        canPermission(AllPermission::suaVanBanDen());
        $user= auth::user();
        $van_ban_den = VanBanDen::where('id',$id)->WhereNull('deleted_at')->first();
        $domat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $sovanban = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission('tham mưu')->where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        return view('vanbanden::van_ban_den.edit',compact('van_ban_den','domat','dokhan','loaivanban','sovanban','users'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $noi_dung = !empty($request['noi_dung']) ? $request['noi_dung'] : null;
        $han_giai_quyet = !empty($request['han_giai_quyet']) ? $request['han_giai_quyet'] : null;
        $vanbandv = VanBanDen::where('id', $id)->first();
        $checktrungsoden = VanBanDen::where(['so_van_ban_id'=>$request->so_van_ban,'id'=>$vanbandv->id])->first();
        $vanbandv->loai_van_ban_id = $request->loai_van_ban;
        $vanbandv->so_van_ban_id = $request->so_van_ban;
        if($checktrungsoden == null)
        {
            $soDen = VanBanDen::where([
                'so_van_ban_id' => $request->so_van_ban
            ])->max('so_den');
            $soDenvb = $soDen + 1;
            $vanbandv->so_den = $soDenvb;
        }
        $vanbandv->so_ky_hieu = $request->so_ky_hieu;
        $vanbandv->ngay_ban_hanh = $request->ngay_ban_hanh;
        $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
        $vanbandv->trich_yeu = $request->trich_yeu;
        $vanbandv->nguoi_ky = $request->nguoi_ky;
        $vanbandv->han_xu_ly = $request->han_xu_ly;
        $vanbandv->do_khan_cap_id = $request->do_khan;
        $vanbandv->do_bao_mat_id = $request->do_mat;
        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;;
        $vanbandv->han_giai_quyet = $request->han_giai_quyet;
        $vanbandv->don_vi_id = auth::user()->don_vi_id;;

        $vanbandv->noi_dung = $noi_dung[0];
        $vanbandv->han_giai_quyet = $han_giai_quyet[0];
        $vanbandv->save();


        return redirect()->back()->with('success', 'Cập nhật dữ liệu thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function delete_vb_den(Request $request)
    {
        canPermission(AllPermission::xoaVanBanDen());
        $vanbanden = VanBanDen::where('id',$request->id_vb)->first();
        $vanbanden->delete();
        return redirect()->route('van-ban-den.index')->with('success','Xóa thành công !');
    }
}
