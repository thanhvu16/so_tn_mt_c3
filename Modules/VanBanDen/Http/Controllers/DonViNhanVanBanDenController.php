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
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDen\Entities\VanBanDenDonVi;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use auth;

class DonViNhanVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $hienthi = $request->get('don_vi_van_ban');
        $donvinhan = NoiNhanVanBanDi::where(['don_vi_id_nhan' => auth::user()->don_vi_id])->whereIn('trang_thai', [2])
            ->where(function ($query) use ($hienthi) {
                if (!empty($hienthi)) {
                    return $query->where('trang_thai', "$hienthi");
                }
            })
            ->paginate(PER_PAGE);
        $donvinhancount = count($donvinhan);
        $vanbanhuyenxuongdonvi = DonViChuTri::where(['don_vi_id' => auth::user()->don_vi_id,])->whereNull('vao_so_van_ban')
            ->where(function ($query) use ($hienthi) {
                if (!empty($hienthi)) {
                    if ($hienthi == 2)
                        return $query->whereNull('vao_so_van_ban');
                    elseif ($hienthi == 3) {
                        return $query->where('vao_so_van_ban', 1);
                    }
                }
            })
            ->whereNull('parent_id')
            ->whereNull('type')
            ->select('id', 'van_ban_den_id', 'can_bo_chuyen_id')
            ->get();

        // don vi phoi hop
        $vanBanHuyenChuyenDonViPhoiHop = DonViPhoiHop::where('don_vi_id', auth::user()->don_vi_id)
            ->where(function ($query) use ($hienthi) {
                if (!empty($hienthi)) {
                    if ($hienthi == 2)
                        return $query->whereNull('vao_so_van_ban');
                    elseif ($hienthi == 3) {
                        return $query->where('vao_so_van_ban', 1);
                    }
                }
            })
            ->whereNull('vao_so_van_ban')
            ->whereNull('parent_id')
            ->whereNull('type')
            ->select('id', 'van_ban_den_id', 'can_bo_chuyen_id')
            ->get();

        $donvinhancount2 = count($vanbanhuyenxuongdonvi);
        $tong = $donvinhancount + $donvinhancount2 + $vanBanHuyenChuyenDonViPhoiHop->count();

        return view('vanbanden::don_vi_nhan_van_ban.index', compact('donvinhan',
            'vanbanhuyenxuongdonvi', 'donvinhancount', 'tong', 'vanBanHuyenChuyenDonViPhoiHop'));
    }

    public function chi_tiet_van_ban_den_don_vi(Request $request, $id)
    {
        $user = auth::user();
        $domat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $sovanban = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
        $ngaynhan = date('Y-m-d');
        $songay = 10;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;
        $type = $request->get('type') ?? null;

        $van_ban_den = DonViChuTri::where('id', $id)->first();

        // van ban don vi phoi hop
        if (!empty($type) && $type == 'phoi_hop') {

            $van_ban_den = DonViPhoiHop::where('id', $id)->first();
        }

        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }

        }

        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        return view('vanbanden::don_vi_nhan_van_ban.van_ban_den_don_vi', compact('dokhan', 'domat',
            'loaivanban', 'sovanban', 'users', 'id', 'hangiaiquyet', 'van_ban_den', 'type'));

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
        $domat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $sovanban = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();
        $ngaynhan = date('Y-m-d');
        $songay = 10;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;

        $van_ban_den = NoiNhanVanBanDi::where('id', $id)->first();

        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }

        }

        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        return view('vanbanden::don_vi_nhan_van_ban.edit', compact('dokhan', 'domat', 'loaivanban', 'sovanban', 'users', 'id', 'hangiaiquyet', 'van_ban_den'));
    }

    public function vaosovanbandvnhan(Request $request)
    {
        $user = auth::user();
        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($requestData['noi_dung']) ? $requestData['noi_dung'] : null;
        if (auth::user()->role_id == QUYEN_VAN_THU_HUYEN) {
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
                    $vanbandv->type = 1;
                    $vanbandv->noi_dung = $data;
                    if ($request->han_giai_quyet[$key] == null) {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->han_xu_ly;
                    } else {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $han_gq[$key];
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
                $vanbandv->han_giai_quyet = $request->han_xu_ly;
                $vanbandv->type = 1;
                $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                $vanbandv->don_vi_id = auth::user()->don_vi_id;
                $vanbandv->nguoi_tao = auth::user()->id;
                $vanbandv->save();
            }
        } elseif (auth::user()->role_id == QUYEN_VAN_THU_DON_VI) {
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
                    $vanbandv->type = 2;
                    if ($request->han_giai_quyet[$key] == null) {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->han_xu_ly;
                    } else {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $han_gq[$key];
                    }
                    $vanbandv->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                    $vanbandv->save();
                    DonViChuTri::saveDonViChuTri($vanbandv->id);
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
                $vanbandv->han_giai_quyet = $request->han_xu_ly;
                $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                $vanbandv->don_vi_id = auth::user()->don_vi_id;
                $vanbandv->type = 2;
                $vanbandv->nguoi_tao = auth::user()->id;
                $vanbandv->trinh_tu_nhan_van_ban = VanBanDen::TRUONG_PHONG_NHAN_VB;
                $vanbandv->save();
                DonViChuTri::saveDonViChuTri($vanbandv->id);
            }
        }
        $layvanbandi = NoiNhanVanBanDi::where('id', $request->id_van_ban_di)->first();
        $updatenoinhan = NoiNhanVanBanDi::where('van_ban_di_id', $layvanbandi->van_ban_di_id)->get();
        if ($updatenoinhan) {
            //update
            foreach ($updatenoinhan as $data) {
                $trangthai = NoiNhanVanBanDi::where('id', $data->id)->first();
                $trangthai->trang_thai = 3;
                $trangthai->save();
            }
        }

        if ($request->id_file) {
            $file = FileVanBanDi::where('id', $request->id_file)->first();
            if ($file) {
                $vbDenFile = new FileVanBanDen();
                $vbDenFile->ten_file = $file->ten_file;
                $vbDenFile->duong_dan = $file->duong_dan;
                $vbDenFile->duoi_file = $file->duoi_file;
                $vbDenFile->vb_den_id = $vanbandv->id;
                $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                $vbDenFile->save();

            }

        }


        return redirect()->route('van-ban-den.index')->with('success', 'Thêm văn bản thành công !!');
    }


    public function vaosovanbanhuyen(Request $request)
    {
        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($requestData['noi_dung']) ? $requestData['noi_dung'] : null;
        if (auth::user()->role_id == QUYEN_VAN_THU_HUYEN) {
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
                    $vanbandv->type = 1;
                    $vanbandv->noi_dung = $data;
                    if ($request->han_giai_quyet[$key] == null) {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->han_xu_ly;
                    } else {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $han_gq[$key];
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
                $vanbandv->han_giai_quyet = $request->han_xu_ly;
                $vanbandv->type = 1;
                $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                $vanbandv->don_vi_id = auth::user()->don_vi_id;
                $vanbandv->nguoi_tao = auth::user()->id;
                $vanbandv->save();
            }
        } elseif (auth::user()->role_id == QUYEN_VAN_THU_DON_VI)

            $type = $request->get('type') ?? null;
            $layvanbandi = DonViChuTri::where('id', $request->id_don_vi_chu_tri)->first();

            // don vi phoi hop
            if (!empty($type) && $type == 'phoi_hop') {
                $layvanbandi = DonViPhoiHop::where('id', $request->id_don_vi_chu_tri)->first();
            }
        {

            if ($noi_dung && $noi_dung[0] != null) {
                foreach ($noi_dung as $key => $data) {
                    $vanbandv = new VanBanDen();
                    $vanbandv->parent_id = $layvanbandi->van_ban_den_id ?? null;
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
                    $vanbandv->type = 2;
                    $vanbandv->loai_van_ban_don_vi = !empty($type) ? VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP : null;
                    if ($request->han_giai_quyet[$key] == null) {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->han_xu_ly;
                    } else {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $han_gq[$key];
                    }

                    $vanbandv->save();
                }
            } else {
                $vanbandv = new VanBanDen();
                $vanbandv->parent_id = $layvanbandi->van_ban_den_id ?? null;
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
                $vanbandv->han_giai_quyet = $request->han_xu_ly;
                $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                $vanbandv->don_vi_id = auth::user()->don_vi_id;
                $vanbandv->type = 2;
                $vanbandv->loai_van_ban_don_vi = !empty($type) ? VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP : null;
                $vanbandv->nguoi_tao = auth::user()->id;
                $vanbandv->save();
            }
        }

        if ($layvanbandi) {
            //update
            $layvanbandi->vao_so_van_ban = 1;
            $layvanbandi->save();
        }
        if ($request->id_file) {
            $file = FileVanBanDen::where('id', $request->id_file)->first();
            if ($file) {
                $vbDenFile = new FileVanBanDen();
                $vbDenFile->ten_file = $file->ten_file;
                $vbDenFile->duong_dan = $file->duong_dan;
                $vbDenFile->duoi_file = $file->duoi_file;
                $vbDenFile->vb_den_id = $vanbandv->id;
                $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                $vbDenFile->save();
            }

        }


        return redirect()->route('van-ban-den.index')->with('success', 'Thêm văn bản thành công !!');
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
