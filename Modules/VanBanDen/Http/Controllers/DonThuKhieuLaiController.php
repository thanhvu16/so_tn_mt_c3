<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\SoVanBan;
use auth,DB;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\VanBanDen\Entities\TieuChuanVanBan;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;

class DonThuKhieuLaiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = auth::user();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [1, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 1])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $sovanban = $laysovanban;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $nam = date("Y");
        $soVanBan = SoVanBan::where('ten_so_van_ban', "LIKE", 'công văn')->first();
        $soDenvb = VanBanDen::where([
            'don_vi_id' => $lanhDaoSo->don_vi_id,
            'so_van_ban_id' => $soVanBan->id,
            'type' => 1
        ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        $soDen = $soDenvb + 1;
        $date = date("d/m/Y");
        $tieuChuan = TieuChuanVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission(AllPermission::thamMuu())->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->orderBy('id', 'DESC')->get();
        return view('vanbanden::don-thu.create',compact('sovanban','loaivanban','soDen','date','tieuChuan','users'));
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
        $user = auth::user();
        $nam = date("Y");
        $thamMuuId = !empty($request->lanh_dao_tham_muu) ?? null;
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        try {
            DB::beginTransaction();

            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                $soDenvb = VanBanDen::where([
                    'don_vi_id' => $lanhDaoSo->don_vi_id,
                    'so_van_ban_id' => $request->so_van_ban,
                    'type' => 1
                ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $soDenvb = VanBanDen::where([
                    'don_vi_id' => $user->donVi->parent_id,
                    'so_van_ban_id' => $request->so_van_ban,
                    'type' => 2
                ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
            }
            $soDenvb = $soDenvb + 1;
            if ($request->chu_tri_phoi_hop == null) {
                $request->chu_tri_phoi_hop = 0;
            }

                    $vanbandv = new VanBanDen();
                    $vanbandv->loai_van_ban_id = $request->loai_van_ban;
                    $vanbandv->so_van_ban_id = $request->so_van_ban;
                    $vanbandv->so_den = $soDenvb;
                    $vanbandv->so_ky_hieu = $request->so_ky_hieu;
                    $vanbandv->thong_tin_cong_dan = $request->thong_tin_cong_dan;
                    $vanbandv->ngay_ban_hanh = !empty($request->ngay_ban_hanh) ? formatYMD($request->ngay_ban_hanh) : null;
                    $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
                    $vanbandv->trich_yeu = $request->trich_yeu;
                    $vanbandv->chu_tri_phoi_hop = $request->chu_tri_phoi_hop;
                    $vanbandv->han_xu_ly = !empty($request->han_xu_ly) ? formatYMD($request->han_xu_ly) : null;
                    $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                    $vanbandv->don_vi_id = auth::user()->don_vi_id;
                    $vanbandv->type = 1;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->trinh_tu_nhan_van_ban = empty($thamMuuId) ? VanBanDen::CHU_TICH_NHAN_VB : null;
                    $vanbandv->save();

                    // nếu empty tham mưu thì chuyển thẳng giám đốc (chủ tịch)
            $uploadPath = UPLOAD_FILE_VAN_BAN_DEN;
            if ($request->File) {
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }
                $typeArray = explode('.', $request->File->getClientOriginalName());
                $tenchinhfile = strtolower($typeArray[0]);
                $extFile = $request->File->extension();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $request->File->getClientOriginalName();
                $urlFile = UPLOAD_FILE_VAN_BAN_DEN . '/' . $fileName;
                $request->File->move($uploadPath, $fileName);
                $vbDenFile = new FileVanBanDen();
                $vbDenFile->ten_file = $tenchinhfile;
                $vbDenFile->duong_dan = $urlFile;
                $vbDenFile->duoi_file = $extFile;
                $vbDenFile->vb_den_id = $vanbandv->id;
                $vbDenFile->nguoi_dung_id = auth::user()->id;
                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                $vbDenFile->save();
            }


            DB::commit();
            return redirect()->back()->with('success', 'Thêm văn bản thành công !');

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
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
