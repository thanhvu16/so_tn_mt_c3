<?php

namespace Modules\LichCongTac\Http\Controllers;

use App\Models\LichCongTac;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\NhomDonVi;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\LichCongTac\Entities\CuocHopChiTiet;
use Modules\LichCongTac\Entities\CuocHopLienQuan;
use Modules\LichCongTac\Entities\DanhGiaGopY;
use Modules\LichCongTac\Entities\DanhGiaTaiLieu;
use Modules\LichCongTac\Entities\FileCuocHop;
use Modules\LichCongTac\Entities\NguoiThamDu;
use File, auth, DB;
use Modules\LichCongTac\Entities\ThanhPhanDuHop;

class QuanLyCuocHopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('lichcongtac::index');
    }

    public function chiTietCuocHop($id)
    {
        $nguoi_tham_du = null;
        $lich_cong_tac = LichCongTac::where('id', $id)->first();
        $cuochop = CuocHopChiTiet::where('lich_hop_id', $id)->first();
        $roles = [CHU_TICH, PHO_CHUC_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_PHONG, PHO_PHONG];
        $danhSachLanhDao = User::whereHas('roles', function ($query) use ($roles) {
            return $query->whereIn('name', $roles);
        })
            ->orderBy('id', 'ASC')
            ->get();
        if ($lich_cong_tac->type == null) {
            $nguoi_tham_du = ThanhPhanDuHop::where(['lich_cong_tac_id' => $lich_cong_tac->id])->get();
//        dd($nguoi_tham_du);
        }
        $nhom_don_vi = NhomDonVi::orderBy('ten_nhom_don_vi', 'asc')->get();
        $chucVu = ChucVu::orderBy('ten_chuc_vu', 'asc')->whereNull('deleted_at')->get();
        $donvi = DonVi::orderBy('ten_don_vi', 'asc')->whereNull('deleted_at')->get();
        $cuocHopLienQuan = CuocHopLienQuan::where('id_lich_hop', $id)->whereNull('deleted_at')->get();
        $nguoi_chu_tri = User::role([CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG, TRUONG_BAN, PHO_TRUONG_BAN, TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHUC_TICH])->get();
        $nguoi_upTaiLieu = ThanhPhanDuHop::where(['lich_cong_tac_id' => $id, 'trang_thai' => 1,'thanh_phan_moi'=>1])->get();
        $phong_up_tai_lieu = ThanhPhanDuHop::where(['lich_cong_tac_id' => $id, 'trang_thai' => 1,'thanh_phan_moi'=>1])->distinct()->pluck('don_vi_id');

        $canBoGopY = ThanhPhanDuHop::where(['lich_cong_tac_id' => $id, 'trang_thai' => 1])->get();
        $GopY = DanhGiaGopY::where(['user_id' => auth::user()->id, 'id_lich_hop' => $id])->first();
//        dd($cuocHopLienQuan);
        $donViKetLuan = DonVi::where('ten_don_vi','LIKE','Phòng Tổng Hợp')->get();


        return view('lichcongtac::chi-tiet.index', compact('lich_cong_tac', 'danhSachLanhDao', 'nguoi_upTaiLieu', 'nguoi_tham_du', 'id', 'cuochop', 'nhom_don_vi',
            'cuocHopLienQuan', 'chucVu', 'donvi', 'nguoi_chu_tri', 'phong_up_tai_lieu', 'canBoGopY', 'GopY','donViKetLuan'));
    }


    public function deleteNguoiDuHop($id)
    {
        $xoa = ThanhPhanDuHop::where(['id' => $id])->where('thanh_phan_moi', 2)->delete();
        return response()->json(
            [
                'id' => $id,
                'is_relate' => true,
                'message' => 'Xóa người tham dự thành công!'
            ]
        );
    }
    public function thamDuNgoai(Request $request)
    {
        $thamDuNgoai = CuocHopChiTiet::where(['lich_hop_id'=>$request->lich_ct])->first();
        if ($thamDuNgoai == null) {
            $taomoi = new CuocHopChiTiet();
            $taomoi->lich_hop_id = $request->id;
            $taomoi->thanh_phan_ben_ngoai = $request->noidung;
            $taomoi->save();

        } else {
            $thamDuNgoai->thanh_phan_ben_ngoai = $request->noidung;
            $thamDuNgoai->save();
        }
        return response()->json(
            [
                'is_relate' => true,
                'message' => 'Thêm thành phần tham dự thành công!'
            ]
        );
    }

    public function themDuLieuCuocHop($id, Request $request)
    {
        $cuochop = CuocHopChiTiet::where('lich_hop_id', $id)->first();
        if ($cuochop == null) {
            $taomoi = new CuocHopChiTiet();
            $taomoi->lich_hop_id = $request->id;
            $taomoi->y_kien_chinh_thuc = $request->ykienchinhthuc;
            $taomoi->save();

        } else {
            $cuochop->lich_hop_id = $request->id;
            $cuochop->y_kien_chinh_thuc = $request->ykienchinhthuc;
            $cuochop->save();
        }
        return response()->json(
            [
                'id' => $id,
                'is_relate' => true,
                'message' => 'Đánh giá thành công!'
            ]
        );
    }

    public function luu_ghichepcuochop_qu($id, Request $request)
    {
        $cuochop = CuocHopChiTiet::where('lich_hop_id', $id)->first();
        if ($cuochop == null) {
            $taomoi = new CuocHopChiTiet();
            $taomoi->lich_hop_id = $request->id;
            $taomoi->ghi_chep_quan_uy = $request->noidung_ghichepcuochop_qu;
            $taomoi->save();

        } else {
            $cuochop->ghi_chep_quan_uy = $request->noidung_ghichepcuochop_qu;
            $cuochop->lich_hop_id = $request->id;
            $cuochop->save();
        }

        return response()->json(
            [
                'id' => $id,
                'is_relate' => true,
                'message' => 'Đánh giá thành công!'
            ]
        );
    }

    public function luu_ghichepcuochop($id, Request $request)
    {
        $cuochop = CuocHopChiTiet::where('lich_hop_id', $id)->first();
        if ($cuochop == null) {
            $taomoi = new CuocHopChiTiet();
            $taomoi->lich_hop_id = $request->id;
            $taomoi->ghi_chep_HDND = $request->noidung_ghichepcuochop;
            $taomoi->save();

        } else {
            $cuochop->ghi_chep_HDND = $request->noidung_ghichepcuochop;
            $cuochop->lich_hop_id = $request->id;
            $cuochop->save();
        }
        return response()->json(
            [
                'id' => $id,
                'is_relate' => true,
                'message' => 'Đánh giá thành công!'
            ]
        );
    }

    public function luu_ketluan($id, Request $request)
    {
        $file_ketluan = !empty($request['file_ketluan']) ? $request['file_ketluan'] : null;
        $uploadPath = UPLOAD_FILE_CUOC_HOP;
        $cuochop = CuocHopChiTiet::where('lich_hop_id', $id)->first();
        if ($cuochop == null) {
            $taomoi = new CuocHopChiTiet();
            $taomoi->lich_hop_id = $id;
            $taomoi->ket_luan_cuoc_hop = $request->noidung_ketluan;
            $taomoi->save();

        } else {
            $cuochop->ket_luan_cuoc_hop = $request->noidung_ketluan;
            $cuochop->lich_hop_id = $request->id;
            $cuochop->save();
        }
        if ($file_ketluan && count($file_ketluan) > 0) {
            foreach ($file_ketluan as $key => $getFile) {
                $extFile = $getFile->extension();
                $filecuochop = new FileCuocHop();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_CUOC_HOP . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $filecuochop->ten_file = $fileName;
                $filecuochop->duong_dan = $urlFile;
                $filecuochop->duoi_file = $extFile;
                $filecuochop->lich_hop_id = $id;
                $filecuochop->nguoi_tao = auth::user()->id;
                $filecuochop->trang_thai = 3;
                $filecuochop->save();
            }

        }
        return redirect()->back();
    }

    public function upload_tai_lieu(Request $request, $id)
    {
        $file_tai_lieu = !empty($request['tailieucuochop']) ? $request['tailieucuochop'] : null;
        $file_tham_khao = !empty($request['tailieuthamkhao']) ? $request['tailieuthamkhao'] : null;
        $uploadPath = UPLOAD_FILE_CUOC_HOP;
        if ($file_tai_lieu && count($file_tai_lieu) > 0) {
            foreach ($file_tai_lieu as $key => $getFile) {
                $extFile = $getFile->extension();
                $filecuochop = new FileCuocHop();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_CUOC_HOP . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $filecuochop->ten_file = $fileName;
                $filecuochop->duong_dan = $urlFile;
                $filecuochop->duoi_file = $extFile;
                $filecuochop->lich_hop_id = $id;
                $filecuochop->nguoi_tao = auth::user()->id;
                $filecuochop->trang_thai = 1;
                $filecuochop->save();
            }

        }
        if ($file_tham_khao && count($file_tham_khao) > 0) {
            foreach ($file_tham_khao as $key => $getFile) {
                $extFile = $getFile->extension();
                $filecuochop = new FileCuocHop();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_CUOC_HOP . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $filecuochop->ten_file = $fileName;
                $filecuochop->duong_dan = $urlFile;
                $filecuochop->duoi_file = $extFile;
                $filecuochop->lich_hop_id = $id;
                $filecuochop->nguoi_tao = auth::user()->id;
                $filecuochop->trang_thai = 2;
                $filecuochop->save();
            }

        }
        return redirect()->back();


    }

    public function cuocHopLienQuan($id, Request $request)
    {
        $nam = $request->nam;
        $ngaybatdau = $request->ngay_bat_dau;
        $ngayketthuc = $request->ngay_ket_thuc;
        $nguoi_chu_tri = $request->nguoi_chu_tri;
        $ten_lich_hop = $request->ten_lich_hop;
        $lichCongTac = LichCongTac::orderBy('id', 'asc')
            ->where(function ($query) use ($nguoi_chu_tri) {
                if (!empty($nguoi_chu_tri)) {
                    return $query->where('lanh_dao_id', "$nguoi_chu_tri");
                }
            })
            ->where(function ($query) use ($ten_lich_hop) {
                if (!empty($ten_lich_hop)) {
                    return $query->where('noi_dung', 'LIKE', "%$ten_lich_hop%");
                }
            })
            ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                    return $query->where('ngay', '>=', $ngaybatdau)
                        ->where('ngay', '<=', $ngayketthuc);
                }
                if ($ngayketthuc == '' && $ngaybatdau != '') {
                    return $query->where('ngay', $ngaybatdau);

                }
                if ($ngaybatdau == '' && $ngayketthuc != '') {
                    return $query->where('ngay', $ngayketthuc);

                }
            })
            ->whereYear('ngay', '=', $nam)
            ->get();

        return response()->json(
            [
                'lichCongTac' => $lichCongTac,
                'id_lich_hop' => $id,
                'is_relate' => true
            ]
        );
    }

    public function themCuocHop(Request $request)
    {
        $cuocHopTrung = CuocHopLienQuan::where(['id_cuoc_hop_lien_quan' => $request->id, 'id_lich_hop' => $request->lich_hop_id])->first();


        if ($cuocHopTrung == null) {
            $cuocHopLienQUan = new CuocHopLienQuan();
            $cuocHopLienQUan->id_lich_hop = $request->lich_hop_id;
            $cuocHopLienQUan->id_cuoc_hop_lien_quan = $request->id;
            $cuocHopLienQUan->save();
            return response()->json(
                [
                    'is_relate' => true,
                    'message' => 'Thêm cuộc họp thành công!'
                ]
            );


        } else {
            return response()->json(
                [
                    'is_relate' => false,
                    'message' => 'Cuộc họp đã tồn tại!'
                ]
            );
        }


    }

    public function XoaCuocHop($id)
    {
        $xoaCuocHopLienQuan = CuocHopLienQuan::where('id', $id)->delete();
        return response()->json(
            [
                'is_relate' => true,
                'message' => 'Xóa thành công!'
            ]
        );
    }

    public function xoaTaiLieu($id)
    {
        $xoaCuocHopLienQuan = FileCuocHop::where('id', $id)->delete();
        return response()->json(
            [
                'is_relate' => true,
                'message' => 'Xóa thành công!'
            ]
        );
    }

    public function luu_danhgiatonghop(Request $request, $id)
    {
        $lich_cong_tac = LichCongTac::where('id', $id)->first();
        if ($request->danh_gia == 1) {
            $lich_cong_tac->danh_gia = 1;
            $lich_cong_tac->save();
        } else {
            $lich_cong_tac->danh_gia = 0;
            $lich_cong_tac->save();
        }
        return response()->json(
            [
                'is_relate' => true,
                'message' => 'Đánh giá thành công!'
            ]
        );
    }

    public function luu_noidungchat(Request $request, $id)
    {
        $GopY = DanhGiaGopY::where(['user_id' => auth::user()->id, 'id_lich_hop' => $id])->first();
        if ($GopY == null) {
            $gopY = new DanhGiaGopY();
            $gopY->user_id = auth::user()->id;
            $gopY->id_lich_hop = $id;
            $gopY->trao_doi_thao_luan = $request->noidungchat;
            $gopY->save();
        } else {
            $GopY->trao_doi_thao_luan = $request->noidungchat;
            $GopY->save();
        }
        return response()->json(
            [
                'is_relate' => false,
                'message' => 'Đánh giá thành công!'
            ]
        );
    }

    public function nhanxetTaiLieu(Request $request, $id)
    {

        $taiLieu = new DanhGiaTaiLieu();
        $taiLieu->id_phong = $request->id_donVi;
        $taiLieu->id_lich_ct = $request->lich_cong_tac;
        $taiLieu->danh_gia_chat_luong_chuan_bi_tai_lieu = $request->danh_gia;
        $taiLieu->nhan_xet = $request->nhan_xet;
        $taiLieu->trang_thai = 1;
        $taiLieu->save();
        return response()->json(
            [
                'is_relate' => true,
                'message' => 'Đánh giá thành công!'
            ]
        );
    }

    public function hoten_capnhatthamdu(Request $request)
    {
        $donViId= $request->don_vi;
        $nhom_don_vi= $request->nhom_don_vi;
        $chuc_vu= $request->chuc_vu;
        $hoTen= $request->ho_ten;
        $nhom = NhomDonVi::where('id',$nhom_don_vi)->first();
//        $donvi = DonVi::where('')
        $users = User::with('chucVu', 'donVi')
            ->where('trang_thai', ACTIVE)
            ->where(function ($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('don_vi_id', $donViId);
                }
            })
            ->where(function ($query) use ($chuc_vu) {
                if (!empty($chuc_vu)) {
                    return $query->where('chuc_vu_id', $chuc_vu);
                }
            })
            ->where(function ($query) use ($hoTen) {
                if (!empty($hoTen)) {
                    return $query->where('ho_ten', $hoTen);
                }
            })

            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json(
            [
                'is_relate' => true,
                'users' => $users
            ]
        );

    }

    public function LuuCanBoDuHop(Request $request)
    {
        $user = User::where('id',$request->id)->first();
        $nguoiThamDu = new ThanhPhanDuHop();
        $nguoiThamDu->lich_cong_tac_id = $request->lich_hop_id;
        $nguoiThamDu->user_id = $request->id;
        $nguoiThamDu->don_vi_id = $user->don_vi_id;
        $nguoiThamDu->thanh_phan_moi = 2;
        $nguoiThamDu->nguoi_tao_id = auth::user()->id;
        $nguoiThamDu->save();
        return response()->json(
            [
                'is_relate' => true,
                'message' => 'Thêm cán bộ thành công!'
            ]
        );
    }

    public function danhgiaykien(Request $request)
    {
        $thanhPhanThamDu =ThanhPhanDuHop::where(['lich_cong_tac_id'=>$request->lich_ct,'user_id'=>$request->ca_nhan])->first();
        $thanhPhanThamDu->nhan_xet=$request->nhan_xet;
        $thanhPhanThamDu->chat_luong=$request->danh_gia;
        $thanhPhanThamDu->save();

        return response()->json(
            [
                'is_relate' => true,
                'message' => 'Đánh giá thành công!'
            ]
        );


    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('lichcongtac::create');
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
        return view('lichcongtac::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('lichcongtac::edit');
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
