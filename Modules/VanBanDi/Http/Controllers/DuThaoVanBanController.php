<?php

namespace Modules\VanBanDi\Http\Controllers;


use App\Common\AllPermission;
use App\Models\UserLogs;
use App\Models\VanBanDiVanBanDen;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use auth, File, DB;
use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\Admin\Entities\MailTrongThanhPho;
use Modules\Admin\Entities\NhomDonVi;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\Fileduthao;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanMail;
use Modules\VanBanDi\Entities\NoiNhanMailNgoai;
use Modules\VanBanDi\Entities\NoiNhanVanBanDi;
use Modules\VanBanDi\Entities\VanBanDi;
use Modules\VanBanDi\Entities\VanBanDiChoDuyet;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Spatie\Permission\Models\Role;
use function GuzzleHttp\Promise\all;

class DuThaoVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $vanThuVanBanDiPiceCharts = [];
        $date = Carbon::now()->format('Y-m-d');
        $donvikhongdieuhanh = DonVi::where('dieu_hanh', '!=', 1)->whereNull('deleted_at')->get();
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $lanhdaotrongphong = User::role([TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN, TRUONG_BAN, PHO_TRUONG_BAN, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->where(['don_vi_id' => auth::user()->don_vi_id])->where('id', '!=', auth::user()->id)->whereNull('deleted_at')->get();
        $lanhdaokhac = User::where('don_vi_id', '!=', auth::user()->don_vi_id)->whereNull('deleted_at')->get();
        $ds_nguoiKy = null;
        //$ds_nguoiKy = User::role([ TRUONG_PHONG,PHO_PHONG,CHU_TICH,PHO_CHU_TICH,TRUONG_PHONG,PHO_PHONG])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
        $ds_DonVi_phatHanh = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->where('dieu_hanh', 1)->get();
        $user = auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi', 'LIKE', LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi', $nhomDonVi->id)->first();


        $dataNguoiKy = [];
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    if ($donVi->dieu_hanh == 1) {
                        $truongpho = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                            ->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $truongpho = null;
                    }

                    if ($truongpho != null) {
                        foreach ($truongpho as $data2) {
                            array_push($dataNguoiKy, $data2);
                        }

                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                }
                break;
            case PHO_PHONG:
                if ($donVi->dieu_hanh == 1) {
                    $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $truongpho = null;
                }

                if ($truongpho != null) {
                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = $lanhDaoSo;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                }
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = null;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                $ds_nguoiKy = $lanhDaoSo;
                break;
            case PHO_CHANH_VAN_PHONG:
                $chanhVanPhong = User::role([CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->first();
                foreach ($chanhVanPhong as $data) {
                    array_push($dataNguoiKy, $data);
                }
                foreach ($lanhDaoSo as $item) {
                    array_push($dataNguoiKy, $item);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_DON_VI:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

            case VAN_THU_HUYEN:
                $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;

            case TRUONG_BAN:
                $ds_nguoiKy = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                break;

            case PHO_TRUONG_BAN:
                $truongBan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->first();
                array_push($dataNguoiKy, $truongBan);

                $danhSachLanhDaoPhongBan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                if ($danhSachLanhDaoPhongBan) {
                    foreach ($danhSachLanhDaoPhongBan as $lanhDaoPhongBan) {
                        array_push($dataNguoiKy, $lanhDaoPhongBan);
                    }
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

        }


        return view('vanbandi::Du_thao_van_ban_di.index', compact('ds_loaiVanBan', 'ds_nguoiKy', 'lanhdaotrongphong', 'ds_DonVi_phatHanh', 'lanhdaokhac', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('vanbandi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        canPermission(AllPermission::themDuThao());
        //file
        $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
        $tenfilehoso = !empty($request['txt_file']) ? $request['txt_file'] : null;
        $filehoso = !empty($request['file_name']) ? $request['file_name'] : null;
        $filephieutrinh = !empty($request['file_phieu_trinh']) ? $request['file_phieu_trinh'] : null;
        $filetrinhky = !empty($request['file_trinh_ky']) ? $request['file_trinh_ky'] : null;


        $lanhdaophong = !empty($request['lanh_dao_phong_phoi_hop']) ? $request['lanh_dao_phong_phoi_hop'] : null;
        $lanhdaophongkhac = !empty($request['lanh_dao_phong_khac']) ? $request['lanh_dao_phong_khac'] : null;
        $duthao = new Duthaovanbandi();
        $duthao->loai_van_ban_id = $request->loai_van_ban_id;
        $duthao->so_ky_hieu = $request->so_ky_hieu;
        $duthao->vb_trich_yeu = $request->vb_trich_yeu;
        $duthao->nguoi_ky = $request->nguoi_ky;
        $duthao->chuc_vu = $request->chuc_vu;
        $duthao->phong_phat_hanh = $request->phong_phat_hanh;
        $duthao->so_trang = $request->so_trang;
        $duthao->han_xu_ly = $request->han_xu_ly;
        $duthao->ngay_thang = $request->ngay_thang;
        $duthao->nguoi_tao = auth::user()->id;
        $duthao->y_kien = $request->y_kien;
        $duthao->lan_du_thao = 1;
        $duthao->van_ban_den_don_vi_id = $request->get('van_ban_den_don_vi_id') ?? null;
        $duthao->save();
        UserLogs::saveUserLogs('Tạo dự thảo văn bản đi', $duthao);
//        update id văn bản
        $duthao_id = Duthaovanbandi::where('id', $duthao->id)->first();
        $duthao_id->du_thao_id = $duthao->id;
        $duthao_id->save();

        //check van ban tra loi
        if (!empty($request->get('van_ban_den_don_vi_id'))) {
            $vanBanDen = VanBanDen::where('id', (int)$request->get('van_ban_den_don_vi_id'))->first();
            if ($vanBanDen) {
                $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::HOAN_THANH_CHO_DUYET;
                $vanBanDen->van_ban_can_tra_loi = 1;
                $vanBanDen->save();

                //xoa chuyen nhan vb
                $chuyenNhanVanBanDonVi = DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
                    ->where('can_bo_nhan_id', auth::user()->id)
                    ->whereNull('hoan_thanh')->first();

                if ($chuyenNhanVanBanDonVi) {
                    DonViChuTri::where('van_ban_den_id', $vanBanDen->id)
                        ->where('don_vi_id', auth::user()->don_vi_id)
                        ->where('id', '>', $chuyenNhanVanBanDonVi->id)
                        ->whereNull('hoan_thanh')->delete();
                }
            }
        }

        if ($filehoso && count($filehoso) > 0) {
            foreach ($filehoso as $key => $getFile) {

                $typeArray = explode('.', $getFile->getClientOriginalName());
                $extFile = $getFile->extension();
                $ten = strtolower($tenfilehoso[$key]) . '_' . '.' . $extFile;
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $ten;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 3;
                $fileduthao->save();
            }

        }
        if ($filephieutrinh && count($filephieutrinh) > 0) {
            foreach ($filephieutrinh as $key => $getFile) {
                $extFile = $getFile->extension();
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $fileName;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 1;
                $fileduthao->save();
            }

        }

        if ($filetrinhky && count($filetrinhky) > 0) {
            foreach ($filetrinhky as $key => $getFile) {
//                $typeArray = explode('.', $getFile->getClientOriginalName());
                $extFile = $getFile->extension();
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $fileName;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 2;
                $fileduthao->save();
            }

        }
        if ($lanhdaophong && count($lanhdaophong) > 0) {
            foreach ($lanhdaophong as $key => $data) {
                $canbophong = new CanBoPhongDuThao();
                $canbophong->can_bo_id = $data;
                $canbophong->du_thao_vb_id = $duthao->id;
                $canbophong->save();
            }
        }
        if ($lanhdaophongkhac && count($lanhdaophongkhac) > 0) {
            foreach ($lanhdaophongkhac as $key => $data) {
                $canbophongkhac = new CanBoPhongDuThaoKhac();
                $canbophongkhac->can_bo_id = $data;
                $canbophongkhac->du_thao_vb_id = $duthao->id;
                $canbophongkhac->save();
            }
        }
        return redirect()->route('Danhsachduthao')->with('success', 'Thêm dự thảo thành công !');
    }

    public function Danhsachduthao()
    {
        $ds_duthao = Duthaovanbandi::where(['nguoi_tao' => auth::user()->id, 'stt' => 1])->orderBy('created_at', 'desc')->get();
        return view('vanbandi::Du_thao_van_ban_di.Danh_sach_du_thao', compact('ds_duthao'));
    }

    public function laythongtinduthaocu($id)
    {
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $lanhdaotrongphong = User::role([TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN])->where(['don_vi_id' => auth::user()->don_vi_id])->where('id', '!=', auth::user()->id)->whereNull('deleted_at')->get();
        $lanhdaokhac = User::role([TRUONG_PHONG])->where('don_vi_id', '!=', auth::user()->don_vi_id)->whereNull('deleted_at')->get();
        $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG, CHU_TICH, PHO_CHU_TICH, TRUONG_PHONG, PHO_PHONG])->orderBy('username', 'desc')->whereNull('deleted_at')->get();
        $lay_can_bo_phong = CanBoPhongDuThao::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();

        $lay_can_bo_khac = CanBoPhongDuThaoKhac::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();

        $duthao = Duthaovanbandi::where('id', $id)->first();
        return view('vanbandi::Du_thao_van_ban_di.duthaocu', compact('duthao', 'ds_nguoiKy', 'lanhdaokhac', 'lanhdaotrongphong', 'ds_loaiVanBan', 'lay_can_bo_khac', 'lay_can_bo_phong'));
    }

    public function thongtinvanban($id)
    {
        $user = auth::user();
        $donVi = $user->donVi;
        $file = Fileduthao::where(['vb_du_thao_id' => $id])->where('stt', '!=', 0)->get();
        $vanThuVanBanDiPiceCharts = [];

        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi', 'LIKE', LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi', $nhomDonVi->id ?? null)->first();
        $dataNguoiKy = [];
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();

        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    if ($donVi->dieu_hanh == 1) {
                        $truongpho = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                            ->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $truongpho = null;
                    }

                    if ($truongpho != null) {
                        foreach ($truongpho as $data2) {
                            array_push($dataNguoiKy, $data2);
                        }

                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                }
                break;
            case PHO_PHONG:
                if ($donVi->dieu_hanh == 1) {
                    $truongpho = User::role([TRUONG_PHONG, CHANH_VAN_PHONG])
                        ->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $truongpho = null;
                }

                if ($truongpho != null) {
                    foreach ($truongpho as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }

                }

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = $lanhDaoSo;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case PHO_CHU_TICH:
                if (empty($donVi->cap_xa)) {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH])->where('don_vi_id', $donVi->id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $ds_nguoiKy = null;
                } else {
                    $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                $ds_nguoiKy = $lanhDaoSo;
                break;
            case PHO_CHANH_VAN_PHONG:
                $chanhVanPhong = User::role([CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->first();
                foreach ($chanhVanPhong as $data) {
                    array_push($dataNguoiKy, $data);
                }
                foreach ($lanhDaoSo as $item) {
                    array_push($dataNguoiKy, $item);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_DON_VI:
                $ds_nguoiKy = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

            case VAN_THU_HUYEN:
                $ds_nguoiKy = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->get();
                break;

            case TRUONG_BAN:
                $ds_nguoiKy = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                break;

            case PHO_TRUONG_BAN:
                $truongBan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->first();
                array_push($dataNguoiKy, $truongBan);

                $danhSachLanhDaoPhongBan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                if ($danhSachLanhDaoPhongBan) {
                    foreach ($danhSachLanhDaoPhongBan as $lanhDaoPhongBan) {
                        array_push($dataNguoiKy, $lanhDaoPhongBan);
                    }
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

        }

        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $ds_DonVi = Donvi::whereNull('deleted_at')
            ->orderBy('ten_don_vi', 'asc')->get();
        $ds_DonVi_nhan = Donvi::whereNull('deleted_at')->where('parent_id', 0)
            ->orderBy('ten_don_vi', 'asc')->get();
        $ds_DonVi_phatHanh = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->where('dieu_hanh', 1)->get();
        $user = auth::user();

        $laysovanban = [];
        $sovanbanchung = SoVanBan::whereIn('loai_so', [2, 3])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sovanbanchung as $data2) {
            array_push($laysovanban, $data2);
        }
        $sorieng = SoVanBan::where(['loai_so' => 4, 'so_don_vi' => $user->don_vi_id, 'type' => 2])->wherenull('deleted_at')->orderBy('id', 'asc')->get();
        foreach ($sorieng as $data2) {
            array_push($laysovanban, $data2);
        }
        $ds_soVanBan = $laysovanban;
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $date = Carbon::now()->format('Y-m-d');
        $id_duthao = $id;
        $vanbanduthao = Duthaovanbandi::where('id', $id)->first();
        $donvicap2 = Donvi::whereNull('deleted_at')->first();
        $nguoinhan = null;
//        $nguoinhan = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
        $user = auth::user();
        $donVi = $user->donVi;


        switch (auth::user()->roles->pluck('name')[0]) {
            case CHUYEN_VIEN:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();

                } else {
                    $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                }
                break;
            case PHO_PHONG:
                $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case TRUONG_PHONG:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = $lanhDaoSo;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case PHO_CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                } else {
                    $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                }
                break;
            case CHU_TICH:
                if ($donVi->parent_id == 0) {
                    $nguoinhan = null;
                } else {
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                }
                break;
            case CHANH_VAN_PHONG:
                if (empty($donVi->cap_xa)) {
                    $nguoinhan = $lanhDaoSo;
                }
                break;
            case PHO_CHANH_VAN_PHONG:
                $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                break;
            case VAN_THU_DON_VI:
                $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:
                $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                    ->whereHas('donVi', function ($query) {
                        return $query->whereNull('cap_xa');
                    })->get()->get();
                break;
            case TRUONG_BAN:
                $nguoinhan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();;
                break;
            case PHO_TRUONG_BAN:
                $nguoinhan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

        }

        return view('vanbandi::Du_thao_van_ban_di.tao_van_ban_di', compact('ds_nguoiKy', 'ds_DonVi_nhan', 'ds_loaiVanBan', 'ds_DonVi', 'ds_soVanBan', 'ds_DonVi_phatHanh',
            'ds_doKhanCap', 'ds_mucBaoMat', 'vanbanduthao', 'date', 'id_duthao', 'nguoinhan', 'file', 'emailngoaithanhpho', 'emailtrongthanhpho'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('vanbandi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        canPermission(AllPermission::suaDuThao());
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $lanhdaotrongphong = User::role([TRUONG_PHONG, PHO_PHONG, CHUYEN_VIEN])->where(['don_vi_id' => auth::user()->don_vi_id])->where('id', '!=', auth::user()->id)->whereNull('deleted_at')->get();
        $lanhdaokhac = User::role([TRUONG_PHONG])->where('don_vi_id', '!=', auth::user()->don_vi_id)->whereNull('deleted_at')->get();
        $ds_nguoiKy = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
        $lay_can_bo_phong = CanBoPhongDuThao::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $lay_can_bo_khac = CanBoPhongDuThaoKhac::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $duthao = Duthaovanbandi::where('id', $id)->first();
        $file = Fileduthao::where(['vb_du_thao_id' => $id])->where('stt', '!=', 0)->get();
        return view('vanbandi::Du_thao_van_ban_di.edit', compact('duthao', 'ds_nguoiKy', 'lanhdaokhac', 'lanhdaotrongphong', 'ds_loaiVanBan', 'lay_can_bo_khac', 'lay_can_bo_phong', 'file'));
    }

    public function tao_du_thao_lan_tiep($id, Request $request)
    {
        $duthaocu = Duthaovanbandi::where('id', $id)->first();
        $duthaocu->stt = 2;
        $duthaocu->save();
        $canbothuocduthaocu = CanBoPhongDuThao::where('du_thao_vb_id', $id)->get();
        foreach ($canbothuocduthaocu as $canbo) {
            $canbothuocduthaophongcu = CanBoPhongDuThao::where('id', $canbo->id)->first();
            $canbothuocduthaophongcu->trang_thai = 12;
            $canbothuocduthaophongcu->save();
        }
        $canbothuocduthaophongkhaccu = CanBoPhongDuThaoKhac::where('du_thao_vb_id', $id)->get();
        foreach ($canbothuocduthaophongkhaccu as $canbokhac) {
            $canbothuocduthaocukhac = CanBoPhongDuThaoKhac::where('id', $canbokhac->id)->first();
            $canbothuocduthaocukhac->trang_thai = 12;
            $canbothuocduthaocukhac->save();
        }
        $lanhdaophong = !empty($request['lanh_dao_phong_phoi_hop']) ? $request['lanh_dao_phong_phoi_hop'] : null;
        $lanhdaophongkhac = !empty($request['lanh_dao_phong_khac']) ? $request['lanh_dao_phong_khac'] : null;
        $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
        $tenfilehoso = !empty($request['txt_file']) ? $request['txt_file'] : null;
        $filehoso = !empty($request['file_name']) ? $request['file_name'] : null;
        $filephieutrinh = !empty($request['file_phieu_trinh']) ? $request['file_phieu_trinh'] : null;
        $filetrinhky = !empty($request['file_trinh_ky']) ? $request['file_trinh_ky'] : null;


        $duthao = new Duthaovanbandi();
        $duthao->loai_van_ban_id = $request->loai_van_ban_id;
        $duthao->so_ky_hieu = $request->so_ky_hieu;
        $duthao->vb_trich_yeu = $request->vb_trich_yeu;
        $duthao->nguoi_ky = $request->nguoi_ky;
        $duthao->chuc_vu = $request->chuc_vu;
        $duthao->so_trang = $request->so_trang;
        $duthao->ngay_thang = $request->ngay_thang;
        $duthao->nguoi_tao = auth::user()->id;
        $duthao->y_kien = $request->y_kien;
        $duthao->du_thao_id = $duthaocu->du_thao_id;
        $duthao->van_ban_den_don_vi_id = $duthaocu->van_ban_den_don_vi_id ?? null;
        $duthao->lan_du_thao = $duthaocu->lan_du_thao + 1;
        if ($duthaocu->du_thao_cha == null) {
            $duthao->du_thao_cha = $id;
        } else {
            $duthao->du_thao_cha = $duthaocu->id;
        }
        $duthao->save();
        UserLogs::saveUserLogs('Tạo dự thảo văn bản đi lần tiếp', $duthao);

        if ($lanhdaophong && count($lanhdaophong) > 0) {
            foreach ($lanhdaophong as $key => $data) {
                $canbophong = new CanBoPhongDuThao();
                $canbophong->can_bo_id = $data;
                $canbophong->du_thao_vb_id = $duthao->id;
                $canbophong->save();
            }
        }
        if ($lanhdaophongkhac && count($lanhdaophongkhac) > 0) {
            foreach ($lanhdaophongkhac as $key => $data) {
                $canbophongkhac = new CanBoPhongDuThaoKhac();
                $canbophongkhac->can_bo_id = $data;
                $canbophongkhac->du_thao_vb_id = $duthao->id;
                $canbophongkhac->save();
            }
        }
        if ($filehoso && count($filehoso) > 0) {
            foreach ($filehoso as $key => $getFile) {
                $typeArray = explode('.', $getFile->getClientOriginalName());
                $extFile = strtolower($typeArray[1]);
                $ten = strSlugFileName(strtolower($tenfilehoso[$key]), '_') . '.' . $extFile;
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $ten;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 3;
                $fileduthao->save();
            }

        }
        if ($filephieutrinh && count($filephieutrinh) > 0) {
            foreach ($filephieutrinh as $key => $getFile) {
                $typeArray = explode('.', $getFile->getClientOriginalName());
                $extFile = strtolower($typeArray[1]);
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $fileName;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 1;
                $fileduthao->save();
            }

        }
        if ($filetrinhky && count($filetrinhky) > 0) {
            foreach ($filetrinhky as $key => $getFile) {
                $typeArray = explode('.', $getFile->getClientOriginalName());
                $extFile = strtolower($typeArray[1]);
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $fileName;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 2;
                $fileduthao->save();
            }

        }


        return redirect()->route('Danhsachduthao')->with('success', 'Tạo dự thảo thành công');
    }

    public function delete_duthao($id)
    {
        $delete = Fileduthao::where('id', $id)->first();
        $delete->stt = 0;
        $delete->save();
        return redirect()->back()->with('success', 'Xóa file thành công !');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
        $tenfilehoso = !empty($request['txt_file']) ? $request['txt_file'] : null;
        $filehoso = !empty($request['file_name']) ? $request['file_name'] : null;
        $filephieutrinh = !empty($request['file_phieu_trinh']) ? $request['file_phieu_trinh'] : null;
        $filetrinhky = !empty($request['file_trinh_ky']) ? $request['file_trinh_ky'] : null;
        $lanhdaophong = !empty($request['lanh_dao_phong_phoi_hop']) ? $request['lanh_dao_phong_phoi_hop'] : null;
        $lanhdaophongkhac = !empty($request['lanh_dao_phong_khac']) ? $request['lanh_dao_phong_khac'] : null;
        $canbophong = CanBoPhongDuThao::where(['du_thao_vb_id' => $id, 'trang_thai' => 1])->get();
        $idcanbophong = $canbophong->pluck('can_bo_id')->toArray();
        $canbophongkhac = CanBoPhongDuThaoKhac::where(['du_thao_vb_id' => $id, 'trang_thai' => 1])->get();
        $idcanbophongkhac = $canbophongkhac->pluck('can_bo_id')->toArray();

        $duthao = Duthaovanbandi::where('id', $id)->first();
        $duthao->loai_van_ban_id = $request->loai_van_ban_id;
        $duthao->so_ky_hieu = $request->so_ky_hieu;
        $duthao->y_kien = $request->y_kien;
        $duthao->vb_trich_yeu = $request->vb_trich_yeu;
        $duthao->nguoi_ky = $request->nguoi_ky;
        $duthao->chuc_vu = $request->chuc_vu;
        $duthao->ngay_thang = $request->ngay_thang;
        $duthao->so_trang = $request->so_trang;
        $duthao->han_xu_ly = $request->han_xu_ly;
        $duthao->save();
        UserLogs::saveUserLogs('Cập nhật dự thảo văn bản đi', $duthao);


        if ($filehoso && count($filehoso) > 0) {
            foreach ($filehoso as $key => $getFile) {

                $typeArray = explode('.', $getFile->getClientOriginalName());
                $extFile = $getFile->extension();
                $ten = strtolower($tenfilehoso[$key]) . '_' . '.' . $extFile;
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $ten;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 3;
                $fileduthao->save();
            }

        }
        if ($filephieutrinh && count($filephieutrinh) > 0) {
            foreach ($filephieutrinh as $key => $getFile) {
                $extFile = $getFile->extension();
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $fileName;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 1;
                $fileduthao->save();
            }

        }

        if ($filetrinhky && count($filetrinhky) > 0) {
            foreach ($filetrinhky as $key => $getFile) {
//                $typeArray = explode('.', $getFile->getClientOriginalName());
                $extFile = $getFile->extension();
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->ten_file = $fileName;
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->vb_du_thao_id = $duthao->id;
                $fileduthao->nguoi_tao = auth::user()->id;
                $fileduthao->don_vi = auth::user()->donvi_id;
                $fileduthao->stt = 2;
                $fileduthao->save();
            }

        }


        if ($lanhdaophong && count($lanhdaophong) > 0) {

            if (array_diff($lanhdaophong, $idcanbophong) == null && count($idcanbophong) == count($lanhdaophong)) {
                //đây là trường hợp không thay đổi
            }
            if (array_diff($lanhdaophong, $idcanbophong) != null && count($idcanbophong) < count($lanhdaophong)) {
                //đây là trường hợp thêm n phần từ
                $hihi = array_diff($lanhdaophong, $idcanbophong);
                foreach ($hihi as $data) {
                    $canbotontai = CanBoPhongDuThao:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    if ($canbotontai == null) {
                        $canbophong = new CanBoPhongDuThao();
                        $canbophong->can_bo_id = $data;
                        $canbophong->du_thao_vb_id = $id;
                        $canbophong->save();
                    } else {
                        $canbotontai->trang_thai = 1;
                        $canbotontai->save();
                    }
                }
                $xoaphantubochon = array_diff($idcanbophong, $lanhdaophong);
                foreach ($xoaphantubochon as $data) {
                    $canbophong = CanBoPhongDuThao:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    $canbophong->trang_thai = 0;
                    $canbophong->save();
                }
            }
            if (array_diff($lanhdaophong, $idcanbophong) == null && count($idcanbophong) > count($lanhdaophong)) {
                //đây là trường hợp xóa đi 1 phàn tử
                $xoa = array_diff($idcanbophong, $lanhdaophong);//xóa phần tử dc chọn

                foreach ($xoa as $data) {
                    $canbophong = CanBoPhongDuThao:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    $canbophong->trang_thai = 0;
                    $canbophong->save();
                }
            }
            if (array_diff($lanhdaophong, $idcanbophong) != null && count($idcanbophong) == count($lanhdaophong)) {
                //đây là trường hợp thay đổi người
                $thaydoicanbo = array_diff($lanhdaophong, $idcanbophong);
                foreach ($thaydoicanbo as $data) {
                    $canbotontai = CanBoPhongDuThao:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    if ($canbotontai == null) {
                        $canbophong = new CanBoPhongDuThao();
                        $canbophong->can_bo_id = $data;
                        $canbophong->du_thao_vb_id = $id;
                        $canbophong->save();
                    } else {
                        $canbotontai->trang_thai = 1;
                        $canbotontai->save();
                    }
                }
                $Xoabochon = array_diff($idcanbophong, $lanhdaophong);
                foreach ($Xoabochon as $data) {
                    $canbophong = CanBoPhongDuThao:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    $canbophong->trang_thai = 0;
                    $canbophong->save();
                }
            }


        }
        if ($lanhdaophongkhac && count($lanhdaophongkhac) > 0) {

            if (array_diff($lanhdaophongkhac, $idcanbophongkhac) == null && count($idcanbophongkhac) == count($lanhdaophongkhac)) {
                //đây là trường hợp không thay đổi
            }
            if (array_diff($lanhdaophongkhac, $idcanbophongkhac) != null && count($idcanbophongkhac) < count($lanhdaophongkhac)) {
                //đây là trường hợp thêm n phần từ
                $hihikhac = array_diff($lanhdaophongkhac, $idcanbophongkhac);
                foreach ($hihikhac as $data) {
                    $canbotontai = CanBoPhongDuThaoKhac:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    if ($canbotontai == null) {
                        $canbophong = new CanBoPhongDuThaoKhac();
                        $canbophong->can_bo_id = $data;
                        $canbophong->du_thao_vb_id = $id;
                        $canbophong->save();
                    } else {
                        $canbotontai->trang_thai = 1;
                        $canbotontai->save();
                    }
                }
                $xoaphantubochonkhac = array_diff($idcanbophongkhac, $lanhdaophongkhac);
                foreach ($xoaphantubochonkhac as $data) {
                    $canbophong = CanBoPhongDuThaoKhac:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    $canbophong->trang_thai = 0;
                    $canbophong->save();
                }
            }
            if (array_diff($lanhdaophongkhac, $idcanbophongkhac) == null && count($idcanbophongkhac) > count($lanhdaophongkhac)) {
                //đây là trường hợp xóa đi 1 phàn tử
                $xoakhac = array_diff($idcanbophongkhac, $lanhdaophongkhac);//xóa phần tử dc chọn

                foreach ($xoakhac as $data) {
                    $canbophong = CanBoPhongDuThaoKhac:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    $canbophong->trang_thai = 0;
                    $canbophong->save();
                }
            }
            if (array_diff($lanhdaophongkhac, $idcanbophongkhac) != null && count($idcanbophongkhac) == count($lanhdaophongkhac)) {
                //đây là trường hợp thay đổi người
                $thaydoicanbokhac = array_diff($lanhdaophongkhac, $idcanbophongkhac);
                foreach ($thaydoicanbokhac as $data) {
                    $canbotontai = CanBoPhongDuThaoKhac:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    if ($canbotontai == null) {
                        $canbophong = new CanBoPhongDuThao();
                        $canbophong->can_bo_id = $data;
                        $canbophong->du_thao_vb_id = $id;
                        $canbophong->save();
                    } else {
                        $canbotontai->trang_thai = 1;
                        $canbotontai->save();
                    }
                }
                $Xoabochonkhac = array_diff($idcanbophongkhac, $lanhdaophongkhac);
                foreach ($Xoabochonkhac as $data) {
                    $canbophong = CanBoPhongDuThaoKhac:: where(['can_bo_id' => $data, 'du_thao_vb_id' => $id])->first();
                    $canbophong->trang_thai = 0;
                    $canbophong->save();
                }
            }


        }
        return redirect()->back()->with('success', 'cập nhật thành công !');


    }

    public function tao_van_ban_di(Request $request)
    {
        $user = auth::user();
        $donvinhanvanbandi = !empty($request['don_vi_nhan_van_ban_di']) ? $request['don_vi_nhan_van_ban_di'] : null;
        $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
        $canbothuocduthaocu = CanBoPhongDuThao::where('du_thao_vb_id', $request->id_duthao)->get();
        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id', 'ten_loai_van_ban')->first();
        try {
            DB::beginTransaction();
            foreach ($canbothuocduthaocu as $canbo) {
                $canbothuocduthaophongcu = CanBoPhongDuThao::where('id', $canbo->id)->first();
                $canbothuocduthaophongcu->trang_thai = 12;
                $canbothuocduthaophongcu->save();
            }
            $canbothuocduthaophongkhaccu = CanBoPhongDuThaoKhac::where('du_thao_vb_id', $request->id_duthao)->get();
            foreach ($canbothuocduthaophongkhaccu as $canbokhac) {
                $canbothuocduthaocukhac = CanBoPhongDuThaoKhac::where('id', $canbokhac->id)->first();
                $canbothuocduthaocukhac->trang_thai = 12;
                $canbothuocduthaocukhac->save();
            }
            $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
            $tenfilehoso = !empty($request['txt_file']) ? $request['txt_file'] : null;
            $filehoso = !empty($request['file_name']) ? $request['file_name'] : null;
            $filephieutrinh = !empty($request['file_phieu_trinh']) ? $request['file_phieu_trinh'] : null;
            $filetrinhky = !empty($request['file_trinh_ky']) ? $request['file_trinh_ky'] : null;
            $tenMailThem = !empty($request['ten_don_vi_them']) ? $request['ten_don_vi_them'] : null;
            $EmailThem = $request->email_them;
            $duthaochot = Duthaovanbandi::where('id', $request->id_duthao)->first();
            $nguoiky = User::where('id', $request->nguoiky_id)->first();
            $duthaochot->stt = 3;
            $duthaochot->save();
            $vanbandi = new VanBanDi();
            $vanbandi->trich_yeu = $request->vb_trichyeu;
            $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
            $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
            $vanbandi->phong_phat_hanh = $request->phong_phat_hanh;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->do_khan_cap_id = $request->dokhan_id;
            $vanbandi->chuc_vu = $request->chuc_vu;
            $vanbandi->do_bao_mat_id = $request->dobaomat_id;
            if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHU_TICH ||
                $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //đây là huyện ký
            {
                if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH) ||
                    $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                    //đây là huyện soạn thảo và huyện ký
//                $vanbandi->don_vi_soan_thao = ;
                } else {//đây là đơn vị soạn thảo do huyện ký
//                $vanbandi->don_vi_soan_thao = '';
                    $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                }
                $vanbandi->type = 1;
            } elseif ($nguoiky->role_id == QUYEN_CHUYEN_VIEN || $nguoiky->role_id == QUYEN_PHO_PHONG || $nguoiky->role_id == QUYEN_TRUONG_PHONG) {
                //đây là đơn vị ký
                $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                $vanbandi->don_vi_soan_thao = $request->donvisoanthao_id;
                $vanbandi->type = 2;
            }
            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->nguoi_tao = auth::user()->id;

            $vanbandi->van_ban_den_id = !empty($duthaochot->van_ban_den_don_vi_id) ? [(string)$duthaochot->van_ban_den_don_vi_id] : null;
            if ($duthaochot->loai_van_ban_id == $giayMoi->id) {
                $vanbandi->loai_van_ban_giay_moi = 2;
            } else {
                $vanbandi->loai_van_ban_giay_moi = 1;
            }
            $vanbandi->du_thao_van_ban_di_id = $duthaochot->id ?? null;
            $vanbandi->save();

            // luu vao van ban di van ban den
            if ($duthaochot->van_ban_den_don_vi_id != null) {
                VanBanDiVanBanDen::saveVanBanDiVanBanDen($vanbandi->id, $duthaochot->van_ban_den_don_vi_id);

            }

            UserLogs::saveUserLogs('Tạo văn bản đi', $vanbandi);

            if ($tenMailThem && count($tenMailThem) > 0) {
                foreach ($tenMailThem as $key => $data) {
                    $themDonVi = new MailNgoaiThanhPho();
                    $themDonVi->ten_don_vi = $data;
                    $themDonVi->email = $EmailThem[$key];
                    $themDonVi->save();
                }
            }

            if ($filetrinhky && count($filetrinhky) > 0) {
                if ($filehoso && count($filehoso) > 0) {
                    foreach ($filehoso as $key => $getFile) {
                        $extFile = $getFile->extension();
                        $ten = !empty($txtFiles[$key]) ? strSlugFileName(strtolower($tenfilehoso[$key]), '_') . '.' . $extFile : null;
                        $vbDiFile = new FileVanBanDi();
                        $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                        $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                        if (!File::exists($uploadPath)) {
                            File::makeDirectory($uploadPath, 0775, true, true);
                        }
                        $getFile->move($uploadPath, $fileName);

                        $vbDiFile->ten_file = isset($ten) ? $ten : $fileName;
                        $vbDiFile->duong_dan = $urlFile;
                        $vbDiFile->van_ban_di_id = $vanbandi->id;
                        $vbDiFile->nguoi_dung_id = auth::user()->id;
                        $vbDiFile->don_vi_id = auth::user()->donvi_id;
                        $vbDiFile->trang_thai = 3;
                        $vbDiFile->save();

                    }
                }
                if ($filephieutrinh && count($filephieutrinh) > 0) {
                    foreach ($filephieutrinh as $key => $getFile) {
                        $extFile = $getFile->extension();
                        $vbDiFile = new FileVanBanDi();
                        $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                        $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                        if (!File::exists($uploadPath)) {
                            File::makeDirectory($uploadPath, 0775, true, true);
                        }
                        $getFile->move($uploadPath, $fileName);

                        $vbDiFile->ten_file = $fileName;
                        $vbDiFile->duong_dan = $urlFile;
                        $vbDiFile->van_ban_di_id = $vanbandi->id;
                        $vbDiFile->nguoi_dung_id = auth::user()->id;
                        $vbDiFile->don_vi_id = auth::user()->donvi_id;
                        $vbDiFile->trang_thai = 1;

                        if ($extFile == 'pdf') {
                            $vbDiFile->trang_thai = 1;
                        } else {
                            $vbDiFile->trang_thai = 3;
                        }
                        $vbDiFile->save();

                    }
//                if ($extFile == 'doc' || $extFile == 'docx') {
//                    if (config('system.convert_doc_to_pdf') == true) {
//                        $explodeFileDoc = explode('.', $vbDiFile->ten_file);
//                        $filePdf = $explodeFileDoc[0] . '.' . 'pdf';
//                        $urlFile2 = '/vanBanDiFile_' . date('Y') . '/' . $filePdf;
//                        $convert = new OfficeConverter(public_path($vbDiFile->duong_dan));
//                        $convert->convertTo($filePdf);
//                        $vbDiFile2 = new FileVanBanDi();
//                        $vbDiFile2->ten_file = $filePdf;
//                        $vbDiFile2->duong_dan = $urlFile2;
//
//                        $vbDiFile2->vanbandi_id = $vbDiFile->vanbandi_id;
//                        $vbDiFile2->nguoidung_id = $vbDiFile->nguoidung_id;
//                        $vbDiFile2->donvi_id = $vbDiFile->donvi_id;
//                        $vbDiFile2->trangthai = 1;
//
//                        $vbDiFile2->save();
//                    }
//                }
                }
                if ($filetrinhky && count($filetrinhky) > 0) {
                    foreach ($filetrinhky as $key => $getFile) {
                        $extFile = $getFile->extension();
                        $vbDiFile = new FileVanBanDi();
                        $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                        $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                        if (!File::exists($uploadPath)) {
                            File::makeDirectory($uploadPath, 0775, true, true);
                        }
                        $getFile->move($uploadPath, $fileName);

                        $vbDiFile->ten_file = $fileName;
                        $vbDiFile->duong_dan = $urlFile;
                        $vbDiFile->van_ban_di_id = $vanbandi->id;
                        $vbDiFile->nguoi_dung_id = auth::user()->id;
                        $vbDiFile->don_vi_id = auth::user()->donvi_id;
                        $vbDiFile->trang_thai = 2;

                        if ($extFile == 'pdf') {
                            $vbDiFile->trang_thai = 2;
                        } else {
                            $vbDiFile->trang_thai = 3;
                        }
                        $vbDiFile->save();
//                    if ($extFile == 'doc' || $extFile == 'docx') {
//                        if (config('system.convert_doc_to_pdf') == true) {
//                            $explodeFileDoc = explode('.', $vbDiFile->ten_file);
//                            $filePdf = $explodeFileDoc[0] . '.' . 'pdf';
//                            $urlFile2 = '/vanBanDiFile_' . date('Y') . '/' . $filePdf;
//                            $convert = new OfficeConverter(public_path($vbDiFile->duong_dan));
//                            $convert->convertTo($filePdf);
//                            $vbDiFile2 = new FileVanBanDi();
//                            $vbDiFile2->ten_file = $filePdf;
//                            $vbDiFile2->duong_dan = $urlFile2;
//
//                            $vbDiFile2->vanbandi_id = $vbDiFile->vanbandi_id;
//                            $vbDiFile2->nguoidung_id = $vbDiFile->nguoidung_id;
//                            $vbDiFile2->donvi_id = $vbDiFile->donvi_id;
//                            $vbDiFile2->trangthai = 2;
//
//                            $vbDiFile2->save();
//                        }
//                    }
                    }

                }
            } else {
                //lấy file cũ vào để làm file vb đi
                $fileduthao = Fileduthao::where(['vb_du_thao_id' => $request->id_duthao])->where('stt', '!=', 0)->OrderBy('created_at', 'desc')->get();

                foreach ($fileduthao as $file) {
                    $explodeFileDoc = explode('.', $file->ten_file);
                    $extFile = strtolower($explodeFileDoc[1]);

                    if ($file->stt == 1 || $file->stt == 2) {

//                    if($extFile == 'doc' || $extFile == 'docx'){
//
//                        if (config('system.convert_doc_to_pdf') == true) {
//
//                            $explodeFileDoc = explode('.', $file->ten_file);
//                            $filePdf = $explodeFileDoc[0] . '.' . 'pdf';
//                            $urlFile2 = '/vanBanDiFile_' . date('Y') . '/' . $filePdf;
//                            $convert = new OfficeConverter(public_path($file->duong_dan));
//                            $convert->convertTo($filePdf);
//                            $vbDiFile2 = new FileVanBanDi();
//                            $vbDiFile2->tenfile = $filePdf;
//                            $vbDiFile2->duongdan = $urlFile2;
//                            $vbDiFile2->vanbandi_id = $vanbandi->id;
//                            $vbDiFile2->nguoidung_id = $file->nguoi_tao;
//                            $vbDiFile2->donvi_id = $file->don_vi;
//                            $vbDiFile2->trangthai = $file->stt;
//                            $vbDiFile2->save();
//                        }
//                    }else{
                        $filevanbandi = new FileVanBanDi();
                        $filevanbandi->ten_file = $file->ten_file ?? null;
                        $filevanbandi->duong_dan = $file->duong_dan ?? null;
                        $filevanbandi->van_ban_di_id = $vanbandi->id;
                        $filevanbandi->nguoi_dung_id = $file->nguoi_tao;
                        $filevanbandi->don_vi_id = $file->don_vi;
                        $filevanbandi->trang_thai = $file->stt;
                        $filevanbandi->save();
//                    }

                    } else {
                        ;
                        $filevanbandi = new FileVanBanDi();
                        $filevanbandi->ten_file = $file->ten_file ?? null;
                        $filevanbandi->duong_dan = $file->duong_dan ?? null;
                        $filevanbandi->van_ban_di_id = $vanbandi->id;
                        $filevanbandi->nguoi_dung_id = $file->nguoi_tao;
                        $filevanbandi->don_vi_id = $file->don_vi;
                        $filevanbandi->trang_thai = $file->stt;
                        $filevanbandi->save();
                    }


                }
            }

            $canbonhan = new VanBanDiChoDuyet();
            $canbonhan->van_ban_di_id = $vanbandi->id;
            $canbonhan->can_bo_chuyen_id = $vanbandi->nguoi_tao;
            $canbonhan->can_bo_nhan_id = $request->nguoi_nhan;
            $canbonhan->id_du_thao = $duthaochot->id;
            $canbonhan->save();

            if ($donvinhanvanbandi && count($donvinhanvanbandi) > 0) {
                foreach ($donvinhanvanbandi as $key => $donvi) {
                    $donvinhan = new NoiNhanVanBanDi();
                    $donvinhan->van_ban_di_id = $vanbandi->id;
                    $donvinhan->don_vi_id_nhan = $donvi;
                    $donvinhan->save();
                }
            }
            if ($donvinhanmailngoaitp && count($donvinhanmailngoaitp) > 0) {
                foreach ($donvinhanmailngoaitp as $key => $ngoai) {
                    $mailngoai = new NoiNhanMailNgoai();
                    $mailngoai->van_ban_di_id = $vanbandi->id;
                    $mailngoai->email = $ngoai;
                    $mailngoai->save();
                }
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            dd($e);
        }

        return redirect()->route('Danhsachduthao')->with('success', 'Thêm văn bản đi thành công !');
    }

    public function themDonViNhanVanBanDi(Request $request)
    {
        $vanBanDiId = $request->get('van_ban_di_id');
        $donViTrongThanhPhoIds = $request->get('don_vi_nhan_trong_thanh_pho');
        $donViNgoaiThanhPhoIds = $request->get('don_vi_nhan_ngoai_thanh_pho');

        NoiNhanMail::luuVanBanDiDonVi($vanBanDiId, $donViTrongThanhPhoIds);
        NoiNhanMailNgoai::luuVanBanDiDonViNgoai($vanBanDiId, $donViNgoaiThanhPhoIds);

        return redirect()->back()->with('success', 'Thêm nơi nhận văn bản thành công.');
    }

    public function vb_di_da_duyet()
    {


        $nguoinhan = null;
        $vanbandidautien = null;
        $vanbandicuoicung = null;
        $vanbandichoduyet = null;
        $idnguoiky = null;
        $vanbandichoduyet = Vanbandichoduyet::where(['can_bo_nhan_id' => auth::user()->id])->where('trang_thai', '!=', 1)->orderBy('created_at', 'desc')->get();
        if (count($vanbandichoduyet) > 0) {
            $idvb = $vanbandichoduyet->pluck('van_ban_di_id');
            $vanbandidautien = Vanbandichoduyet::where(['van_ban_di_id' => $idvb])->OrderBy('created_at', 'asc')->first();
            $vanbandicuoicung = Vanbandichoduyet::where(['van_ban_di_id' => $idvb])->OrderBy('created_at', 'desc')->first();
            $nguoiky = VanBanDi::where('id', $vanbandidautien->van_ban_di_id)->first();
            $idnguoiky = $nguoiky->nguoiky_id;
        }
        return view('vanbandi::Duyet_van_ban_di.vanbandaduyet', compact('vanbandichoduyet', 'nguoinhan', 'vanbandidautien', 'idnguoiky', 'vanbandicuoicung'));

    }

    public function vb_di_tra_lai()
    {
        $nguoinhan = null;
        $vanbandidautien = null;
        $vanbandicuoicung = null;
        $vanbandichoduyet = null;
        $idnguoiky = null;

//        $donViXa = DonVi::where(['id'=>auth::user()->don_vi_id , 'cap_xa'=>1])->first();
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi', 'LIKE', LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi', $nhomDonVi->id ?? null)->first();


        $van_ban_di_tra_lai = Vanbandichoduyet::where(['can_bo_nhan_id' => auth::user()->id, 'trang_thai' => 0, 'tra_lai' => 1])->get();
        if (count($van_ban_di_tra_lai) > 0) {
            $idvb = $van_ban_di_tra_lai->pluck('van_ban_di_id');
            $vanbandidautien = Vanbandichoduyet::where(['van_ban_di_id' => $idvb])->OrderBy('created_at', 'asc')->first();
            $vanbandicuoicung = Vanbandichoduyet::where(['van_ban_di_id' => $idvb])->OrderBy('created_at', 'desc')->first();
            $nguoiky = VanBanDi::where('id', $vanbandidautien->van_ban_di_id)->first();
            $idnguoiky = $nguoiky->nguoiky_id;
        }
        if (auth::user()->id == $idnguoiky) {
            $nguoinhan = User::where('id', $vanbandidautien->can_bo_chuyen_id)->get();
        } else {
            $user = auth::user();
            $donVi = $user->donVi;
            $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->get();
            switch (auth::user()->roles->pluck('name')[0]) {
                case CHUYEN_VIEN:
                    if ($donVi->parent_id == 0) {
                        $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();

                    } else {
                        $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    }
                    break;
                case PHO_PHONG:
                    $nguoinhan = User::role([TRUONG_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    break;
                case TRUONG_PHONG:
                    if ($donVi->parent_id == 0) {
                        $nguoinhan = $lanhDaoSo;
                    } else {
                        $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                    }
                    break;
                case PHO_CHU_TICH:
                    if ($donVi->parent_id == 0) {
                        $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    } else {
                        $nguoinhan = User::role([CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();
                    }
                    break;
                case CHU_TICH:
                    if ($donVi->parent_id == 0) {
                        $nguoinhan = null;
                    } else {
                        $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH])->get();
                    }
                    break;
                case CHANH_VAN_PHONG:
                    if ($donVi->parent_id == 0) {
                        $nguoinhan = $lanhDaoSo;
                    }
                    break;
                case PHO_CHANH_VAN_PHONG:
                    $nguoinhan = User::role([CHANH_VAN_PHONG])->get();
                    break;
                case VAN_THU_DON_VI:
                    $nguoinhan = User::role([TRUONG_PHONG, PHO_PHONG])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    break;
                case VAN_THU_HUYEN:
                    $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                        ->whereHas('donVi', function ($query) {
                            return $query->whereNull('cap_xa');
                        })->get()->get();
                    break;
                case TRUONG_BAN:
                    $nguoinhan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();;
                    break;
                case PHO_TRUONG_BAN:
                    $nguoinhan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                    break;

            }
        }
        return view('vanbandi::Duyet_van_ban_di.van_ban_tra_lai', compact('van_ban_di_tra_lai', 'nguoinhan', 'vanbandidautien', 'idnguoiky', 'vanbandicuoicung'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaDuThao());
        $duthao = Duthaovanbandi::where('id', $id)->first();
        $duthao->delete();
        UserLogs::saveUserLogs('Xóa dự thảo văn bản đi', $duthao);
        return redirect()->route('Danhsachduthao')->with('success', 'Xóa thành công !');
    }
}
