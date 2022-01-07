<?php

namespace Modules\VanBanDi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\LichCongTac;
use App\Models\UserLogs;
use App\Models\VanBanDiVanBanDen;
use App\User;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\Admin\Entities\MailTrongThanhPho;
use Modules\Admin\Entities\NhomDonVi;
use Modules\Admin\Entities\SoVanBan;
use auth,DB,File;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\VanBanDi;

class KetThucVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $user = auth::user();
        $donVi = $user->donVi;
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $ds_DonVi_phatHanh = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->where('dieu_hanh', 1)->get();
        $emailSoBanNganh = MailTrongThanhPho::where('mail_group', 1)->orderBy('ten_don_vi', 'asc')->get();
        $emailQuanHuyen = MailTrongThanhPho::where('mail_group', 2)->orderBy('ten_don_vi', 'asc')->get();
        $emailTrucThuoc = MailTrongThanhPho::where('mail_group', 3)->orderBy('ten_don_vi', 'asc')->get();
//        dd($emailTrucThuoc);
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('mac_dinh', 'desc')->get();
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
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->where('loai_van_ban',2)->orderBy('id', 'asc')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('thu_tu', 'asc')->get();
        $ds_DonVi_nhan = DonVi::wherenull('deleted_at')->where('parent_id', 0)->orderBy('id', 'desc')->get();

        $nguoinhan = null;
        $vanThuVanBanDiPiceCharts = [];
        $user = auth::user();
        $donVi = $user->donVi;
        $nhomDonVi = NhomDonVi::where('ten_nhom_don_vi', 'LIKE', LANH_DAO_UY_BAN)->first();
        $donViCapHuyen = DonVi::where('nhom_don_vi', $nhomDonVi->id ?? null)->first();
        $ds_nguoiKy = null;
        $dataNguoiKy = [];
        $lanhDaoSo = User::role([CHU_TICH, PHO_CHU_TICH])
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })->get();
        $phongThanhTra = User::role([TRUONG_PHONG, PHO_PHONG])
            ->where('don_vi_id', 12)->whereNull('deleted_at')->get();

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
                    if ($phongThanhTra != null) {
                        foreach ($phongThanhTra as $data3) {
                            array_push($dataNguoiKy, $data3);
                        }

                    }

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    $ds_nguoiKy = $dataNguoiKy;
                } else {
                    $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    foreach ($chiCuc as $data3) {
                        array_push($dataNguoiKy, $data3);
                    }

                    $ds_nguoiKy = $dataNguoiKy;
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
                if ($phongThanhTra != null) {
                    foreach ($phongThanhTra as $data3) {
                        array_push($dataNguoiKy, $data3);
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
                    $truongpho1 = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                    foreach ($lanhDaoSo as $data2) {
                        array_push($dataNguoiKy, $data2);
                    }
                    if ($truongpho1 != null) {
                        foreach ($truongpho1 as $data2) {
                            array_push($dataNguoiKy, $data2);
                        }

                    }
                    $ds_nguoiKy = $dataNguoiKy;
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
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();
                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }
                $ds_nguoiKy = $dataNguoiKy;
                break;

            case VAN_THU_HUYEN:
                $ds_nguoiKy = $lanhDaoSo;
                break;

            case TRUONG_BAN:
                $chiCuc = User::role([CHU_TICH, PHO_CHU_TICH])->where('don_vi_id', auth::user()->donVi->parent_id)->get();

                foreach ($lanhDaoSo as $data2) {
                    array_push($dataNguoiKy, $data2);
                }
                foreach ($chiCuc as $data3) {
                    array_push($dataNguoiKy, $data3);
                }

                $ds_nguoiKy = $dataNguoiKy;
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
                $nguoinhan = User::role([TRUONG_BAN, PHO_TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;
            case VAN_THU_HUYEN:
                $nguoinhan = User::role([CHU_TICH, PHO_CHU_TICH, CHANH_VAN_PHONG, PHO_CHANH_VAN_PHONG])
                    ->whereHas('donVi', function ($query) {
                        return $query->whereNull('cap_xa');
                    })->get();
                break;
            case TRUONG_BAN:
                $nguoinhan = User::role([PHO_CHU_TICH, CHU_TICH])->where('don_vi_id', $donVi->parent_id)->get();;
                break;
            case PHO_TRUONG_BAN:
                $nguoinhan = User::role([TRUONG_BAN])->where('don_vi_id', auth::user()->don_vi_id)->get();
                break;

        }

        return view('vanbandi::van_ban_di.ketThuc', compact('ds_nguoiKy',
            'ds_soVanBan', 'ds_loaiVanBan', 'ds_doKhanCap', 'ds_DonVi_phatHanh', 'ds_mucBaoMat', 'ds_DonVi', 'nguoinhan', 'ds_DonVi_nhan',
            'emailtrongthanhpho', 'emailngoaithanhpho', 'emailQuanHuyen', 'emailSoBanNganh', 'emailTrucThuoc'));
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
        DB::beginTransaction();
        try {
            $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
            $filetrinhky = !empty($request['file']) ? $request['file'] : null;
            $nguoiky = User::where('id', $request->nguoiky_id)->first();
            $user = auth::user();
            $vanBanDenId = $request->get('van_ban_den_id') ?? null;


            $vanbandi = new VanBanDi();
            $vanbandi->trich_yeu = $request->vb_trichyeu;
            $vanbandi->so_di = $request->so_di;
            $vanbandi->van_ban_den_id = !empty($vanBanDenId) ? explode(',', $vanBanDenId) : null;
            $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
            $vanbandi->ngay_ban_hanh = !empty($request->vb_ngaybanhanh) ? formatYMD($request->vb_ngaybanhanh) : null;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->do_khan_cap_id = $request->dokhan_id;
            $vanbandi->phong_phat_hanh = $request->phong_phat_hanh;
            $vanbandi->chuc_vu = $request->chuc_vu;
            $vanbandi->do_bao_mat_id = $request->dobaomat_id;
            if ($nguoiky->role_id == QUYEN_VAN_THU_HUYEN || $nguoiky->role_id == QUYEN_CHU_TICH || $nguoiky->role_id == QUYEN_PHO_CHU_TICH ||
                $nguoiky->role_id == QUYEN_CHANH_VAN_PHONG || $nguoiky->role_id == QUYEN_PHO_CHANH_VAN_PHONG) //đây là huyện ký
            {
                if ($user->hasRole(VAN_THU_HUYEN) || $user->hasRole(CHU_TICH) || $user->hasRole(PHO_CHU_TICH) ||
                    $user->hasRole(PHO_CHANH_VAN_PHONG) || $user->hasRole(CHANH_VAN_PHONG)) {
                    //đây là huyện soạn thảo và huyện ký
//                    $vanbandi->don_vi_soan_thao = '';
                } else {//đây là đơn vị soạn thảo do huyện ký
//                    $vanbandi->don_vi_soan_thao = '';
                    $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                }
                $vanbandi->type = 1;
            } elseif ($nguoiky->role_id == QUYEN_CHUYEN_VIEN || $nguoiky->role_id == QUYEN_PHO_PHONG || $nguoiky->role_id == QUYEN_TRUONG_PHONG || $nguoiky->role_id == QUYEN_VAN_THU_DON_VI) {
                //đây là đơn vị ký
                $vanbandi->van_ban_huyen_ky = $request->donvisoanthao_id;
                $vanbandi->don_vi_soan_thao = $request->donvisoanthao_id;
                $vanbandi->type = 2;
            }

            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->loai_van_ban_giay_moi = 1;
            $vanbandi->nguoi_tao = auth::user()->id;
            $vanbandi->phat_hanh_van_ban = VanBanDi::DA_PHAT_HANH;
            $vanbandi->save();
            UserLogs::saveUserLogs(' Tạo văn bản đi', $vanbandi);

            if ($filetrinhky && count($filetrinhky) > 0) {
                foreach ($filetrinhky as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $vbDiFile = new FileVanBanDi();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0777, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);

                    $vbDiFile->ten_file = $fileName;
                    $vbDiFile->duong_dan = $urlFile;
                    $vbDiFile->duoi_file = $extFile;
                    $vbDiFile->van_ban_di_id = $vanbandi->id;
                    $vbDiFile->nguoi_dung_id = auth::user()->id;
                    $vbDiFile->don_vi_id = auth::user()->donvi_id;
                    $vbDiFile->trang_thai = 2;
                    $vbDiFile->file_chinh_gui_di = 2;
                    $vbDiFile->loai_file = FileVanBanDi::LOAI_FILE_DA_KY;
                    $vbDiFile->trang_thai_gui = FileVanBanDi::TRANG_THAI_DA_GUI;
                    $vbDiFile->save();

                }
            }
            VanBanDi::luuVanBanDiVanBanDen($vanbandi->id, $vanBanDenId);
            $this->updateVanBanDen($vanbandi);



            $isSuccess = true;


            DB::commit();
        } catch (Exception $e) {
            $isSuccess = false;
        }
        if ($isSuccess) {
            return redirect()->route('van-ban-di.index')
                ->with('success', 'Thêm văn bản đi thành công !');
        } else {
            redirect()->back()
                ->with('failed', 'Thêm văn bản thất bại, vui lòng thử lại !');
        }
    }


    public function updateVanBanDen($vanBanDi)
    {
        $vanBanDiVanBanDen = VanBanDiVanBanDen::where('van_ban_di_id', $vanBanDi->id)->get();
        if ($vanBanDiVanBanDen) {
            foreach ($vanBanDiVanBanDen as $vanBanDiDen) {
                if ($vanBanDiDen->van_ban_den_id != null) {
                    VanBanDen::updateHoanThanhVanBanDen([$vanBanDiDen->van_ban_den_id]);

                }
            }
        }

        $giayMoi = LoaiVanBan::where('ten_loai_van_ban', "LIKE", 'giấy mời')->select('id')->first();
        //update lich cong tac
        if (!empty($giayMoi) && $vanBanDi && $vanBanDi->loai_van_ban_id == $giayMoi->id) {
            LichCongTac::taoLichCongTac($vanBanDi);
        }
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
        return view('vanbandi::edit');
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
