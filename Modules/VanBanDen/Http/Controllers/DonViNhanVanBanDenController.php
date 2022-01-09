<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\NgayNghi;
use Modules\Admin\Entities\SoVanBan;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\TieuChuanVanBan;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDen\Entities\VanBanDenDonVi;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use auth, File, DB;

class DonViNhanVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $hienthi = $request->get('don_vi_van_ban');

        $donVi = auth::user()->donVi;
        $donViId = $donVi->id;
        $donViVT = DonVi::where('id',$donVi->id)->first();
        $donvinhan = null;
        $vanbanhuyenxuongdonvi = null;
        $vanBanHuyenChuyenDonViPhoiHop = null;

        if($donViVT->cap_chi_nhanh == 1)
        {
            $vanbanhuyenxuongdonvi = DonViChuTri::with('canBoChuyen')
                ->where(['don_vi_id' => $donViId])
                ->where('van_thu_nhan',1)
                ->whereNull('da_vao_so')
                ->where('can_bo_nhan_id',auth::user()->id)
                ->where(function ($query) use ($hienthi) {
                    if (!empty($hienthi)) {
                        if ($hienthi == 2)
                            return $query->whereNull('vao_so_van_ban');
                        elseif ($hienthi == 3) {
                            return $query->where('vao_so_van_ban', 1);
                        }
                    }
                })
                ->select('id', 'van_ban_den_id', 'can_bo_chuyen_id', 'updated_at')
                ->paginate(PER_PAGE);
            return view('vanbanden::don_vi_nhan_van_ban.chi-nhanh', compact('vanbanhuyenxuongdonvi'));
        }else{
            if (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $donViId = $donVi->parent_id;
            }
            $donvinhan = NoiNhanVanBanDi::where(['don_vi_id_nhan' => $donViId])->whereIn('trang_thai', [2])
                ->where(function ($query) use ($hienthi) {
                    if (!empty($hienthi)) {
                        return $query->where('trang_thai', "$hienthi");
                    }
                })
                ->paginate(PER_PAGE);


            $vanbanhuyenxuongdonvi = DonViChuTri::with('canBoChuyen')
                ->where(['don_vi_id' => $donViId])
                ->whereNull('vao_so_van_ban')
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
                ->whereNull('tra_lai')
                ->where('da_chuyen_xuong_don_vi', DonViChuTri::VB_DA_CHUYEN_XUONG_DON_VI)
                ->select('id', 'van_ban_den_id', 'can_bo_chuyen_id', 'updated_at')
                ->get();


            $donvinhancount = count($donvinhan);
            // don vi phoi hop
            $vanBanHuyenChuyenDonViPhoiHop = DonViPhoiHop::where('don_vi_id', $donViId)
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
                ->paginate(PER_PAGE);
        $countphoihop = count($donvinhan) + count($vanbanhuyenxuongdonvi);
        $tong = count($donvinhan) + count($vanbanhuyenxuongdonvi) + count($vanBanHuyenChuyenDonViPhoiHop);
        }

//        dd( count($donvinhan) , count($vanbanhuyenxuongdonvi) , count($vanBanHuyenChuyenDonViPhoiHop));
        return view('vanbanden::don_vi_nhan_van_ban.index', compact('donvinhan',
            'vanbanhuyenxuongdonvi', 'donvinhancount', 'tong', 'vanBanHuyenChuyenDonViPhoiHop', 'countphoihop'));
    }

    public function vanBanDonViGuiSo(Request $request)
    {

        $hienthi = $request->get('don_vi_van_ban');
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();

        $donvinhan = NoiNhanVanBanDi::where(['don_vi_id_nhan' => $lanhDaoSo->don_vi_id])->whereIn('trang_thai', [2])
            ->where(function ($query) use ($hienthi) {
                if (!empty($hienthi)) {
                    return $query->where('trang_thai', "$hienthi");
                }
            })
            ->paginate(PER_PAGE);
        return view('vanbanden::don_vi_nhan_van_ban.ds_van_ban_trong_don_vi_gui_den_so', compact('donvinhan'));
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
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $nam = date("Y");
        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 1
            ])->whereYear('ngay_nhan', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 2
            ])->whereYear('ngay_nhan', '=', $nam)->max('so_den');
        }
        $sodengiaymoi = $soDenvb + 1;
        $gio_hop_chinh_fomart = date('H:i', strtotime($request->gio_hop_chinh));
        $requestData = $request->all();
        $multiFiles = !empty($requestData['ten_file']) ? $requestData['ten_file'] : null;
        $giaymoicom = !empty($requestData['noi_dung_hop_con']) ? $requestData['noi_dung_hop_con'] : null;
        $uploadPath = UPLOAD_FILE_GIAY_MOI_DEN;
        $txtFiles = !empty($requestData['txt_file']) ? $requestData['txt_file'] : null;
        $idvanbanden = [];

        $type = $request->get('type') ?? null;
        $layvanbandi = DonViChuTri::where('id', $request->id_van_ban_di)->first();
        // don vi phoi hop
        if (!empty($type) && $type == 'phoi_hop') {
            $layvanbandi = DonViChuTri::where('id', $request->id_van_ban_di)->first();
        }

        $donVi = auth::user()->donVi;

        try {
            DB::beginTransaction();
            $sokyhieu = $request->vb_so_ky_hieu;
            $nguoiky = $request->nguoi_ky_id;
            $coquanbanhanh = $request->co_quan_ban_hanh_id;
            $loaivanban = $request->loai_van_ban_id;
            $trichyeu = $request->trich_yeu;
            //họp chính
            $giohopchinh = $gio_hop_chinh_fomart;
            $ngayhopchinh = $request->ngay_hop_chinh;
            $diadiemchinh = $request->dia_diem_chinh;
            //họp phụ
            $giohopcon = $request->gio_hop_con;
            $ngay_hop_con = $request->ngay_hop_con;
            $dia_diem_con = $request->dia_diem_con;
            $ngaybanhanh = $request->vb_ngay_ban_hanh;
            $chucvu = $request->chuc_vu;

            if (auth::user()->hasRole(VAN_THU_HUYEN)) {
                if ($giaymoicom && $giaymoicom[0] != null) {
                    foreach ($giaymoicom as $key => $data) {
                        $vanbandv = new VanBanDen();

                        $vanbandv->so_van_ban_id = $request->so_van_ban_id;
                        $vanbandv->so_den = $sodengiaymoi;
                        $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                        $vanbandv->nguoi_tao = auth::user()->id;
                        $vanbandv->so_ky_hieu = $sokyhieu;
                        $vanbandv->nguoi_ky = $nguoiky;
                        $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                        $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->vb_han_xu_ly;
                        $vanbandv->loai_van_ban_id = $loaivanban;
                        $vanbandv->type = 1;
                        $vanbandv->trich_yeu = $trichyeu;
                        //họp chính
                        $vanbandv->gio_hop = $giohopchinh;
                        $vanbandv->ngay_hop = $ngayhopchinh;
                        $vanbandv->dia_diem = $diadiemchinh;
                        //họp con
                        if ($request->gio_hop_con[$key] == null) {
                            $vanbandv->gio_hop_phu = $gio_hop_chinh_fomart;
                        } else {
                            $gio_hop_phu = date('H:i', strtotime($giohopcon[$key]));

                            $vanbandv->gio_hop_phu = $gio_hop_phu;
                        }
                        if ($request->dia_diem_con[$key] == null) {
                            $vanbandv->dia_diem_phu = $diadiemchinh;
                        } else {
                            $vanbandv->dia_diem_phu = $dia_diem_con[$key];
                        }
                        if ($request->ngay_hop_con[$key] == null) {
                            $vanbandv->ngay_hop_phu = $ngayhopchinh;
                        } else {
                            $vanbandv->ngay_hop_phu = $ngay_hop_con[$key];
                        }

                        $vanbandv->noi_dung = $data;
                        $vanbandv->ngay_ban_hanh = $ngaybanhanh;
                        $vanbandv->chuc_vu = $chucvu;
                        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                        $vanbandv->save();
                        array_push($idvanbanden, $vanbandv->id);
                    }
                } else {
                    $vanbandv = new VanBanDen();
                    $vanbandv->so_van_ban_id = $request->so_van_ban_id;
                    $vanbandv->so_den = $sodengiaymoi;
                    $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->so_ky_hieu = $sokyhieu;
                    $vanbandv->nguoi_ky = $nguoiky;
                    $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                    $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
                    $vanbandv->loai_van_ban_id = $loaivanban;
                    $vanbandv->trich_yeu = $trichyeu;
                    $vanbandv->chuc_vu = $chucvu;
                    $vanbandv->type = 1;
                    //họp chính
                    $vanbandv->gio_hop = $gio_hop_chinh_fomart;
                    $vanbandv->ngay_hop = $ngayhopchinh;
                    $vanbandv->dia_diem = $diadiemchinh;
                    //nếu không tách nhỏ thì họp con sẽ là họp chính
                    $vanbandv->gio_hop_phu = $gio_hop_chinh_fomart;
                    $vanbandv->ngay_hop_phu = $ngayhopchinh;
                    $vanbandv->dia_diem_phu = $diadiemchinh;
                    $vanbandv->ngay_ban_hanh = $ngaybanhanh;
                    $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                    $vanbandv->save();
                    UserLogs::saveUserLogs('vào sổ văn bản đến', $vanbandv);
                    array_push($idvanbanden, $vanbandv->id);
                }
            } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
                if ($giaymoicom && $giaymoicom[0] != null) {
                    foreach ($giaymoicom as $key => $data) {
                        $vanbandv = new VanBanDen();

                        $vanbandv->so_van_ban_id = $request->so_van_ban_id;
                        $vanbandv->so_den = $sodengiaymoi;
                        $vanbandv->don_vi_id = auth::user()->donVi->parent_id;
                        $vanbandv->nguoi_tao = auth::user()->id;
                        $vanbandv->so_ky_hieu = $sokyhieu;
                        $vanbandv->nguoi_ky = $nguoiky;
                        $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                        $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->vb_han_xu_ly;
                        $vanbandv->loai_van_ban_id = $loaivanban;
                        $vanbandv->trich_yeu = $trichyeu;
                        $vanbandv->type = 2;
                        //họp chính
                        $vanbandv->gio_hop = $giohopchinh;
                        $vanbandv->ngay_hop = $ngayhopchinh;
                        $vanbandv->dia_diem = $diadiemchinh;
                        //họp con
                        if ($request->gio_hop_con[$key] == null) {
                            $vanbandv->gio_hop_phu = $gio_hop_chinh_fomart;
                        } else {
                            $gio_hop_phu = date('H:i', strtotime($giohopcon[$key]));
                            $vanbandv->gio_hop_phu = $gio_hop_phu;
                        }
                        if ($request->dia_diem_con[$key] == null) {
                            $vanbandv->dia_diem_phu = $diadiemchinh;
                        } else {
                            $vanbandv->dia_diem_phu = $dia_diem_con[$key];
                        }
                        if ($request->ngay_hop_con[$key] == null) {
                            $vanbandv->ngay_hop_phu = $ngayhopchinh;
                        } else {
                            $vanbandv->ngay_hop_phu = $ngay_hop_con[$key];
                        }

                        $vanbandv->noi_dung = $data;
                        $vanbandv->ngay_ban_hanh = $ngaybanhanh;
                        $vanbandv->chuc_vu = $chucvu;
                        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                        $vanbandv->parent_id = $layvanbandi->van_ban_den_id ?? null;
                        $vanbandv->loai_van_ban_don_vi = !empty($type) ? VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP : null;

                        $vanbandv->save();
                        array_push($idvanbanden, $vanbandv->id);

                    }
                } else {
                    $vanbandv = new VanBanDen();
                    $vanbandv->so_van_ban_id = $request->so_van_ban_id;
                    $vanbandv->so_den = $sodengiaymoi;
                    $vanbandv->don_vi_id = auth::user()->donVi->parent_id;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->so_ky_hieu = $sokyhieu;
                    $vanbandv->nguoi_ky = $nguoiky;
                    $vanbandv->co_quan_ban_hanh = $coquanbanhanh;
                    $vanbandv->han_xu_ly = $request->vb_han_xu_ly;
                    $vanbandv->loai_van_ban_id = $loaivanban;
                    $vanbandv->trich_yeu = $trichyeu;
                    $vanbandv->chuc_vu = $chucvu;
                    $vanbandv->type = 2;
                    //họp chính
                    $vanbandv->gio_hop = $gio_hop_chinh_fomart;
                    $vanbandv->ngay_hop = $ngayhopchinh;
                    $vanbandv->dia_diem = $diadiemchinh;
                    //nếu không tách nhỏ thì họp con sẽ là họp chính
                    $vanbandv->gio_hop_phu = $gio_hop_chinh_fomart;
                    $vanbandv->ngay_hop_phu = $ngayhopchinh;
                    $vanbandv->dia_diem_phu = $diadiemchinh;
                    $vanbandv->ngay_ban_hanh = $ngaybanhanh;
//                    $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                    $vanbandv->parent_id = $layvanbandi->van_ban_den_id ?? null;
                    $vanbandv->loai_van_ban_don_vi = !empty($type) ? VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP : null;
                    $vanbandv->save();
                    array_push($idvanbanden, $vanbandv->id);

                }

                // gui chu tich xa nhan van ban
                if ($donVi->cap_xa == DonVi::CAP_XA) {
                    $this->updateTrinhTuNhanVanBan($layvanbandi->van_ban_den_id, $type);
                }

                if ($layvanbandi != null) {
                    //update
                    $layvanbandi->vao_so_van_ban = 1;
                    $layvanbandi->da_tham_muu = DonViChuTri::DA_THAM_MUU;
                    $layvanbandi->save();

                    /** check có tham mưu chi cục không? nếu có thì cập nhập cán bộ nhận id trong bảng đơn vị chủ trì **/
                    if (auth::user()->hasRole(VAN_THU_DON_VI)) {
                        $thamMuuChiCuc = User::permission(AllPermission::thamMuu())
                            ->whereHas('donVi', function ($query) {
                                return $query->where('parent_id', auth::user()->donVi->parent_id);
                            })->orderBy('id', 'DESC')->first();

                        if ($thamMuuChiCuc && $donVi->parent_id != 0) {
                            $layvanbandi->can_bo_nhan_id = $thamMuuChiCuc->id;
                            $layvanbandi->da_tham_muu = null;
                            $layvanbandi->save();
                        }
                    }
                }
                $filegiaymoi = FileVanBanDen::where('vb_den_id', $layvanbandi->van_ban_den_id)->whereNull('deleted_at')->get();
                if ($filegiaymoi && count($filegiaymoi) > 0) {
                    foreach ($filegiaymoi as $data) {
                        $vbDenFile = new FileVanBanDen();
                        $vbDenFile->ten_file = $data->ten_file;
                        $vbDenFile->duong_dan = $data->duong_dan;
                        $vbDenFile->duoi_file = $data->duoi_file;
                        $vbDenFile->vb_den_id = $vanbandv->id;
                        $vbDenFile->nguoi_dung_id = $data->nguoi_dung_id;
                        $vbDenFile->don_vi_id = $data->don_vi_id;
                        $vbDenFile->save();
                    }

                }

            }


            if ($multiFiles && count($multiFiles) > 0) {
                foreach ($multiFiles as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $ten = strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile;

                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                    $urlFile = UPLOAD_FILE_GIAY_MOI_DEN . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0777, true, true);
                    }
                    if (count($idvanbanden) > 1) {
                        $getFile->move($uploadPath, $fileName);
                        foreach ($idvanbanden as $data) {
                            $vbDenFile = new FileVanBanDen();
                            $vbDenFile->ten_file = $ten;
                            $vbDenFile->duong_dan = $urlFile;
                            $vbDenFile->duoi_file = $extFile;
                            $vbDenFile->vb_den_id = $data;
                            $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                            $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                            $vbDenFile->save();
                        }

                    } else {
                        $vbDenFile = new FileVanBanDen();
                        $getFile->move($uploadPath, $fileName);
                        $vbDenFile->ten_file = $ten;
                        $vbDenFile->duong_dan = $urlFile;
                        $vbDenFile->duoi_file = $extFile;
                        $vbDenFile->vb_den_id = $vanbandv->id;
                        $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                        $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                        $vbDenFile->save();
                    }
                    UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);

                }
            }
            $layvanbandi = NoiNhanVanBanDi::where('id', $request->id_van_ban_di)->first();
            if ($layvanbandi != null) {
                $updatenoinhan = NoiNhanVanBanDi::where('van_ban_di_id', $layvanbandi->van_ban_di_id)->get();
                if ($updatenoinhan) {
                    //update
                    foreach ($updatenoinhan as $data) {
                        $trangthai = NoiNhanVanBanDi::where('id', $data->id)->first();
                        $trangthai->trang_thai = 3;
                        $trangthai->save();
                    }
                }
            }


            DB::commit();

            return redirect()->route('don-vi-nhan-van-ban-den.index')
                ->with('success', 'Thêm văn bản thành công !');

        } catch (\Exception $e) {
            DB::rollBack();
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

    public function vaoSoVanBanDonViGuiSo($id)
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
        $tieuChuan = TieuChuanVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        return view('vanbanden::don_vi_nhan_van_ban.vao_so_van_ban_don_vi_gui_so', compact('dokhan', 'domat', 'tieuChuan', 'loaivanban', 'sovanban', 'users', 'id', 'hangiaiquyet', 'van_ban_den'));
    }

    public function thongtinvb($id)
    {
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $vanban = NoiNhanVanBanDi::where('id', $id)->first();
//        $soDen = VanBanDen::where([
//            'don_vi_id' => auth::user()->don_vi_id,
//            'so_van_ban_id' => 100,
//
//        ])->max('so_den');
        $phanbiet = null;
        $nam = date("Y");
        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 1
            ])->whereYear('ngay_nhan', '=', $nam)->max('so_den');
        }elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 2
            ])->whereYear('ngay_nhan', '=', $nam)->max('so_den');
        }

        $sodengiaymoi = $soDenvb + 1;
        $user = auth::user();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();

        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [1, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 1])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->orderBy('id', 'DESC')->get();

        return view('vanbanden::don_vi_nhan_van_ban.giaymoi',
            compact('vanban', 'sodengiaymoi', 'ds_loaiVanBan', 'id', 'ds_nguoiKy',
                'ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'nguoi_dung', 'phanbiet'));

    }

    public function thongtinvbsonhan($id)
    {
        $vanban = NoiNhanVanBanDi::where('id', $id)->first();
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $phanbiet = null;
        $nam = date("Y");
        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 1
            ])->whereYear('ngay_nhan', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 2
            ])->whereYear('ngay_nhan', '=', $nam)->max('so_den');
        }

        $sodengiaymoi = $soDenvb + 1;
        $user = auth::user();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();

        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [1, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 1])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->orderBy('id', 'DESC')->get();

        return view('vanbanden::don_vi_nhan_van_ban.giaymoiso',
            compact('vanban', 'sodengiaymoi', 'ds_loaiVanBan', 'id', 'ds_nguoiKy',
                'ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'nguoi_dung', 'phanbiet'));

    }

    public function thongtinvbhuyen($id)
    {

        $vanban = DonViChuTri::where('id', $id)->first();
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $nam = date("Y");
        if (auth::user()->hasRole(VAN_THU_HUYEN)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => $lanhDaoSo->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 1
            ])->whereYear('ngay_nhan', '=', $nam)->max('so_den');
        } elseif (auth::user()->hasRole(VAN_THU_DON_VI)) {
            $soDenvb = VanBanDen::where([
                'don_vi_id' => auth::user()->don_vi_id,
                'so_van_ban_id' => 100,
                'type' => 2
            ])->whereYear('ngay_nhan', '=', $nam)->max('so_den');
        }

        $sodengiaymoi = $soDenvb + 1;
        $user = auth::user();
        $ds_nguoiKy = User::where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->get();

        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [1, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 1])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai' => ACTIVE, 'don_vi_id' => $user->don_vi_id])->orderBy('id', 'DESC')->get();
        $phanbiet = 1;//giấy mời từ huyện xuống

        return view('vanbanden::don_vi_nhan_van_ban.giaymoi', compact('vanban', 'sodengiaymoi', 'ds_loaiVanBan', 'id', 'ds_nguoiKy', 'phanbiet', 'ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'nguoi_dung'));

    }


    public function vaosovanbandvnhan(Request $request)
    {
        $user = auth::user();
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();

        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($request['noi_dung']) ? $request['noi_dung'] : null;
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
                    $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
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
                    UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanbandv);
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
                            UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);

                        }

                    }
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
                $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                $vanbandv->nguoi_tao = auth::user()->id;
                $vanbandv->save();
                UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanbandv);

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
                        UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);

                    }

                }
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
                    $vanbandv->don_vi_id = auth::user()->donVi->parent_id;
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
                    $vanbandv->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
                    $vanbandv->save();
                    UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanbandv);
                    //save chuyen don vi chu tri
                    DonViChuTri::saveDonViChuTri($vanbandv->id);

                    if ($request->id_file) {
                        $file = FileVanBanDi::where('id', $request->id_file)->first();
                        if ($file) {
                            $vbDenFile = new FileVanBanDen();
                            $vbDenFile->ten_file = $file->ten_file;
                            $vbDenFile->duong_dan = $file->duong_dan;
                            $vbDenFile->duoi_file = $file->duoi_file;
                            $vbDenFile->vb_den_id = $vanbandv->id;
                            $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                            $vbDenFile->don_vi_id = auth::user()->donVi->parent_id;
                            $vbDenFile->save();
                            UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);

                        }

                    }

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
                $vanbandv->don_vi_id = auth::user()->donVi->parent_id;
                $vanbandv->type = 2;
                $vanbandv->nguoi_tao = auth::user()->id;
                $vanbandv->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_XA_NHAN_VB;
                $vanbandv->save();
                UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanbandv);
                //save chuyen don vi chu tri
                DonViChuTri::saveDonViChuTri($vanbandv->id);
                if ($request->id_file) {
                    $file = FileVanBanDi::where('id', $request->id_file)->first();
                    if ($file) {
                        $vbDenFile = new FileVanBanDen();
                        $vbDenFile->ten_file = $file->ten_file;
                        $vbDenFile->duong_dan = $file->duong_dan;
                        $vbDenFile->duoi_file = $file->duoi_file;
                        $vbDenFile->vb_den_id = $vanbandv->id;
                        $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                        $vbDenFile->don_vi_id = auth::user()->donVi->parent_id;
                        $vbDenFile->save();
                        UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);

                    }

                }

            }
        }
        $layvanbandi = NoiNhanVanBanDi::where('id', $request->id_van_ban_di)->first();
        if (!empty($layvanbandi)) {
            $layvanbandi->trang_thai = 3;
            $layvanbandi->save();

        }
        return redirect()->route('don-vi-nhan-van-ban-den.index')->with('success', 'Thêm văn bản thành công !');
    }


    public function vaosovanbanhuyen(Request $request)
    {
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->first();
        $donVi = auth::user()->donVi;
        $donViVanThu = DonVi::where('id',$donVi->id)->first();
        $type = $request->get('type') ?? null;
        $layvanbandi = DonViChuTri::where('id', $request->id_don_vi_chu_tri)->first();
        // don vi phoi hop
        if (!empty($type) && $type == 'phoi_hop') {
            $layvanbandi = DonViPhoiHop::where('id', $request->id_don_vi_chu_tri)->first();
        }
        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($request['noi_dung']) ? $request['noi_dung'] : null;
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
                    $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
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
                    UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanbandv);

                    if ($request->id_file) {
                        $file = FileVanBanDen::where('id', $request->id_file)->whereNull('deleted_at')->get();
                        if ($file && count($file) > 0) {
                            foreach ($file as $data1) {
                                $vbDenFile = new FileVanBanDen();
                                $vbDenFile->ten_file = $data1->ten_file;
                                $vbDenFile->duong_dan = $data1->duong_dan;
                                $vbDenFile->duoi_file = $data1->duoi_file;
                                $vbDenFile->vb_den_id = $vanbandv->id;
                                $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                                $vbDenFile->save();
//                                UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);
                            }

                        }

                    }
                }
            }else {
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
                $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
                $vanbandv->type = 1;
                $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
                $vanbandv->don_vi_id = $lanhDaoSo->don_vi_id;
                $vanbandv->nguoi_tao = auth::user()->id;

                $vanbandv->save();
                UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanbandv);
                if ($request->id_file) {
                    $file = FileVanBanDen::where('id', $request->id_file)->get();
                    if ($file && count($file) > 0) {
                        foreach ($file as $data1) {
                            $vbDenFile = new FileVanBanDen();
                            $vbDenFile->ten_file = $data1->ten_file;
                            $vbDenFile->duong_dan = $data1->duong_dan;
                            $vbDenFile->duoi_file = $data1->duoi_file;
                            $vbDenFile->vb_den_id = $vanbandv->id;
                            $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                            $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                            $vbDenFile->save();
                            UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);
                        }
                    }

                }
            }
        } elseif (auth::user()->role_id == QUYEN_VAN_THU_DON_VI) {
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
                    $vanbandv->don_vi_id = auth::user()->donVi->parent_id ?? null;
                    $vanbandv->nguoi_tao = auth::user()->id;
                    $vanbandv->noi_dung = $data;
                    $vanbandv->type = 2;
                    $vanbandv->loai_van_ban_don_vi = !empty($type) ? VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP : null;
                    $vanbandv->chu_tri_phoi_hop = !empty($type) ? VanBanDen::LA_PHOI_HOP : 1;
                    if ($request->han_giai_quyet[$key] == null) {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $request->han_xu_ly;
                    } else {
                        $vanbandv->han_xu_ly = $request->han_xu_ly;
                        $vanbandv->han_giai_quyet = $han_gq[$key];
                    }

                    $vanbandv->save();
                    UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanbandv);

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
                            UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);
                        }

                    }

                }
            } else {


                $vanbandv = new VanBanDen();
                $vanbandv->parent_id = $layvanbandi->van_ban_den_id ?? null;
                $vanbandv->ngay_nhan = !empty($request->ngay_nhan) ? formatYMD($request->ngay_nhan) : null;
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
                $vanbandv->don_vi_id = auth::user()->donVi->parent_id ?? null;
                $vanbandv->type = 2;
                $vanbandv->loai_van_ban_don_vi = !empty($type) ? VanBanDen::LOAI_VAN_BAN_DON_VI_PHOI_HOP : null;
                $vanbandv->chu_tri_phoi_hop = !empty($type) ? VanBanDen::LA_PHOI_HOP : 1;
                $vanbandv->nguoi_tao = auth::user()->id;
                if($donViVanThu->cap_chi_nhanh == 1){
                    $vanbandv->van_ban_chi_nhanh = $donViVanThu->id;
                }
                $vanbandv->save();
                UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanbandv);

                if ($request->id_file) {
                    $file = FileVanBanDen::where('vb_den_id', $request->id_file)->whereNull('deleted_at')->get();
                    if ($file && count($file) > 0) {
                        foreach ($file as $data1) {
                            $vbDenFile = new FileVanBanDen();
                            $vbDenFile->ten_file = $data1->ten_file;
                            $vbDenFile->duong_dan = $data1->duong_dan;
                            $vbDenFile->duoi_file = $data1->duoi_file;
                            $vbDenFile->vb_den_id = $vanbandv->id;
                            $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                            $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                            $vbDenFile->save();
                            UserLogs::saveUserLogs('Upload file văn bản đến', $vbDenFile);
                        }
                    }

                }
            }
        }

        if ($layvanbandi) {
            //update
            $layvanbandi->vao_so_van_ban = 1;
            if($donViVanThu->cap_chi_nhanh == 1){
                $layvanbandi2 = DonViChuTri::where('id', $request->id_don_vi_chu_tri)->where('van_thu_nhan',1)->first();
                $layvanbandi2->da_vao_so = 1;
                $layvanbandi2->save();

                $this->luuDonViChiNhanh($donViVanThu->id, $layvanbandi2->van_ban_den_id, $layvanbandi2->parent_id, $layvanbandi2->han_xu_ly_cu, $request->han_xu_ly);
                $this->updateTrinhTuNhanVanBanDen($layvanbandi->van_ban_den_id);
            }
            $layvanbandi->da_tham_muu = DonViChuTri::DA_THAM_MUU;
            $layvanbandi->save();

            /** check có tham mưu chi cục không? nếu có thì cập nhập cán bộ nhận id trong bảng đơn vị chủ trì **/
            if (auth::user()->hasRole(VAN_THU_DON_VI)) {
                $thamMuuChiCuc = User::permission(AllPermission::thamMuu())
                    ->whereHas('donVi', function ($query) {
                        return $query->where('parent_id', auth::user()->donVi->parent_id);
                    })->orderBy('id', 'DESC')->first();

                if ($thamMuuChiCuc && auth::user()->donVi->parent_id != 0) {
                    $layvanbandi->can_bo_nhan_id = $thamMuuChiCuc->id;
                    $layvanbandi->da_tham_muu = null;
                    $layvanbandi->save();
                }
            }
        }

        // gui chu tich xa nhan van ban
        if (auth::user()->donVi->parent_id != 0) {
            if($donViVanThu->cap_chi_nhanh != 1){
                $this->updateTrinhTuNhanVanBan($layvanbandi->van_ban_den_id, $type);
            }
        }


        return redirect()->route('don-vi-nhan-van-ban-den.index')->with('success', 'Thêm văn bản thành công !');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public static function luuDonViChiNhanh($donViId,  $vanBanDenId, $donViChuTri, $hanXuLyCu, $hanXuLyMoi)
    {
        // luu don vi chu tri
        $donVi = DonVi::where('id', $donViId)->select('id', 'ten_don_vi')->first();
//        $role = [TRUONG_BAN, TRUONG_PHONG];

            $role = [TRUONG_BAN, TRUONG_PHONG];

        $nguoiDung = User::where('don_vi_id', $donViId)
            ->whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->first();
        $txtDonViChuTri = 'Kính chuyển đồng chí '. $nguoiDung->username . ' chỉ đạo';


        $dataLuuDonViChuTri = [
            'van_ban_den_id' => $vanBanDenId,
            'can_bo_chuyen_id' => auth::user()->id,
            'can_bo_nhan_id' => $nguoiDung->id ?? null,
            'don_vi_id' => $donViId,
            'parent_id' => $donViChuTri ? $donViChuTri : null,
            'noi_dung' => $txtDonViChuTri,
            'don_vi_co_dieu_hanh' => 1,
            'vao_so_van_ban' => 1 ,
            'han_xu_ly_cu' => $hanXuLyCu ?? null,
            'han_xu_ly_moi' => isset($hanXuLyMoi) ? $hanXuLyMoi : $donViChuTri->han_xu_ly_moi ?? null,
            'da_chuyen_xuong_don_vi' => 1,
            'user_id' => auth::user()->id,
            'parent_don_vi_id' =>  auth::user()->don_vi_id,
        ];


        $donViChuTri = new DonViChuTri();
        $donViChuTri->fill($dataLuuDonViChuTri);
        $donViChuTri->save();

        LogXuLyVanBanDen::luuLogXuLyVanBanDen($dataLuuDonViChuTri);
    }

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

    public function updateTrinhTuNhanVanBan($vanBanDenId, $type)
    {
        $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
        if ($vanBanDen) {

            $thamMuuChiCuc = User::permission(AllPermission::thamMuu())
                ->whereHas('donVi', function ($query) {
                    return $query->where('parent_id', auth::user()->donVi->parent_id);
                })->orderBy('id', 'DESC')->first();

            $trinhTuNhanVanBan = VanBanDen::CHU_TICH_XA_NHAN_VB;

            if ($thamMuuChiCuc) {
                $trinhTuNhanVanBan = VanBanDen::THAM_MUU_CHI_CUC_NHAN_VB;
            }

            $vanBanDen->trinh_tu_nhan_van_ban = $trinhTuNhanVanBan;
            $vanBanDen->save();
        }
    }
    public function updateTrinhTuNhanVanBanDen($vanBanDenId )
    {
        $vanBanDen = VanBanDen::where('id', $vanBanDenId)->first();
        if ($vanBanDen) {
            $vanBanDen->trinh_tu_nhan_van_ban = 6;
            $vanBanDen->save();
        }
    }
}
