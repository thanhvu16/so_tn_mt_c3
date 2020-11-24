<?php

namespace Modules\VanBanDi\Http\Controllers;


use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use auth,File;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\Fileduthao;

class DuThaoVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $donvikhongdieuhanh= DonVi::where('dieu_hanh', '!=',1)->whereNull('deleted_at')->get();
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $lanhdaotrongphong = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $lanhdaokhac = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
//        $lanhdaokhac = User::where('don_vi_id', '!=', auth::user()->don_vi_id)->whereNull('deleted_at')->get();

        $ds_nguoiKy = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
        return view('vanbandi::Du_thao_van_ban_di.index', compact('ds_loaiVanBan', 'ds_nguoiKy', 'lanhdaotrongphong', 'lanhdaokhac'));
//        return view('vanbandi::index');
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
        //file
        $uploadPath = public_path('vanBanDiFile_' . date('Y'));
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
        $duthao->so_trang = $request->so_trang;
        $duthao->han_xu_ly = $request->han_xu_ly;
        $duthao->ngay_thang = $request->ngay_thang;
        $duthao->nguoi_tao = auth::user()->id;
        $duthao->y_kien = $request->y_kien;
        $duthao->lan_du_thao = 1;
        $duthao->van_ban_den_don_vi_id = $request->get('van_ban_den_don_vi_id') ?? null;
        $duthao->save();
//        update id văn bản
        $duthao_id = Duthaovanbandi::where('id', $duthao->id)->first();
        $duthao_id->du_thao_id = $duthao->id;
        $duthao_id->save();



        if ($filehoso && count($filehoso) > 0) {
            foreach ($filehoso as $key => $getFile) {

                $typeArray = explode('.', $getFile->getClientOriginalName());
                $extFile = $getFile->extension();
                $ten =strtolower($tenfilehoso[$key]). '_' . '.' . $extFile;
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
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
                $extFile  = $getFile->extension();
                $fileduthao = new Fileduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
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

                $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
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
        $ds_loaiVanBan = LoaiVanBan::where('trang_thai', $this->trang_thai['active'])
            ->orderBy('ngay_tao', 'desc')->get();
        $lanhdaotrongphong = User::where(['donvi_id' => $this->user->donvi_id, 'trang_thai' => $this->trang_thai['active']])->get();
        $lanhdaokhac = User::where(['trang_thai' => $this->trang_thai['active']])->where('donvi_id', '!=', $this->user->donvi_id)->get();
        $ds_nguoiKy = User::whereNull('deleted_at')->orderBy('ngay_tao', 'desc')->get();
        $lay_can_bo_phong = CanBoPhongDuThao::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $lay_can_bo_khac = CanBoPhongDuThaoKhac::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $duthao = Duthaovanbandi::where('id', $id)->first();
        return view('VanBanDi::Du_thao_van_ban_di.duthaocu', compact('duthao', 'ds_nguoiKy', 'lanhdaokhac', 'lanhdaotrongphong', 'ds_loaiVanBan', 'lay_can_bo_khac', 'lay_can_bo_phong'));
    }
    public function thongtinvanban($id)
    {
        $file = Fileduthao::where(['vb_du_thao_id' => $id])->where('stt', '!=', 0)->get();
        $ds_nguoiKy = User::where('trang_thai', $this->trang_thai['active'])
            ->orderBy('ho_ten', 'asc')->get();
        $ds_loaiVanBan = LoaiVanBan::where('trang_thai', $this->trang_thai['active'])
            ->orderBy('ten_loai_van_ban', 'asc')->get();
        $ds_DonVi = Donvi::where(['trang_thai' => $this->trang_thai['active'], 'deleted_at' => null])
            ->orderBy('ten_don_vi', 'asc')->get();
//        $ds_linhVuc = LinhVucVanBan::where('trang_thai', $this->trang_thai['active'])
//            ->orderBy('ngay_tao', 'desc')->get();
        $ds_CQBH = CoQuanBanHanh::where('trang_thai', $this->trang_thai['active'])
            ->orderBy('ngay_tao', 'desc')->get();
        $ds_soVanBan = SoVanBan::where('trang_thai', $this->trang_thai['active'])
            ->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhanCap::where('trang_thai', $this->trang_thai['active'])
            ->orderBy('thu_tu', 'asc')->get();
        $ds_mucBaoMat = MucDoBaoMat::where('trangthai', $this->trang_thai['active'])
            ->orderBy('thu_tu', 'asc')->get();
//        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
//        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $date = Carbon::now()->format('Y-m-d');
        $id_duthao = $id;
        $vanbanduthao = Duthaovanbandi::where('id', $id)->first();
        $donvicap2 = Donvi::whereNull('deleted_at')->first();
        $nguoinhan = null;
//        switch ($vanbanduthao->User->donvi->cap_don_vi) {
//            case 1:
//                break;
//            case 2:
//                break;
//            case 3:
//                switch ($vanbanduthao->User->vai_tro) {
//                    case 1:
//                        break;
//                    case 2:
//                        $nguoinhan = User::where('donvi_id', $donvicap2->ma_id)->whereIn('vai_tro', [2, 3])->get();
//                        break;
//                    case 3:
//                        $nguoinhan = User::where('donvi_id', $vanbanduthao->User->donvi_id)->whereIn('vai_tro', [2])->get();
//                        break;
//                    case 4:
//                        $nguoinhan = User::where('donvi_id', $vanbanduthao->User->donvi_id)->whereIn('vai_tro', [2, 3])->get();
//                        break;
//                }
//                break;
//        }


        return view('quanlyvanban::Du_thao_van_ban_di.tao_van_ban_di', compact('ds_nguoiKy', 'ds_loaiVanBan', 'ds_DonVi', 'ds_linhVuc',
            'ds_CQBH', 'ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'vanbanduthao', 'date', 'id_duthao', 'nguoinhan', 'file', 'emailtrongthanhpho', 'emailngoaithanhpho'));
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
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $lanhdaotrongphong = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $lanhdaokhac = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $ds_nguoiKy = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
        $lay_can_bo_phong = CanBoPhongDuThao::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $lay_can_bo_khac = CanBoPhongDuThaoKhac::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $duthao = Duthaovanbandi::where('id', $id)->first();
        return view('vanbandi::Du_thao_van_ban_di.edit', compact('duthao', 'ds_nguoiKy', 'lanhdaokhac', 'lanhdaotrongphong', 'ds_loaiVanBan', 'lay_can_bo_khac', 'lay_can_bo_phong'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        {
            $uploadPath = public_path('vanBanDiFile_' . date('Y'));
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
            if ($filehoso && count($filehoso) > 0) {
                foreach ($filehoso as $key => $getFile) {
                    $typeArray = explode('.', $getFile->getClientOriginalName());
                    $extFile = strtolower($typeArray[1]);
                    $ten = strSlugFileName(strtolower($tenfilehoso[$key]), '_') . '.' . $extFile;
                    $fileduthao = new Fileduthao();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);
                    $fileduthao->ten_file = $ten;
                    $fileduthao->duong_dan = $urlFile;
                    $fileduthao->duoi_file = $extFile;
                    $fileduthao->vb_du_thao_id = $duthao->id;
                    $fileduthao->nguoi_tao = $this->user->id;
                    $fileduthao->don_vi = $this->user->donvi_id;
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
                    $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);
                    $fileduthao->ten_file = $fileName;
                    $fileduthao->duong_dan = $urlFile;
                    $fileduthao->duoi_file = $extFile;
                    $fileduthao->vb_du_thao_id = $duthao->id;
                    $fileduthao->nguoi_tao = $this->user->id;
                    $fileduthao->don_vi = $this->user->donvi_id;
                    if ($extFile == 'pdf') {
                        $fileduthao->stt = 1;
                    } else {
                        $fileduthao->stt = 3;
                    }
                    $fileduthao->save();
                    if ($fileduthao->duoi_file == 'doc' || $fileduthao->duoi_file == 'docx') {
                        if (config('system.convert_doc_to_pdf') == true) {
                            $explodeFileDoc = explode('.', $fileduthao->ten_file);
                            $filePdf = $explodeFileDoc[0] . '.' . 'pdf';
                            $urlFile2 = '/vanBanDiFile_' . date('Y') . '/' . $filePdf;
                            $convert = new OfficeConverter(public_path($fileduthao->duong_dan));
                            $convert->convertTo($filePdf);
                            $fileduthao2 = new Fileduthao();
                            $fileduthao2->ten_file = $filePdf;
                            $fileduthao2->duong_dan = $urlFile2;
                            $fileduthao2->duoi_file = 'pdf';
                            $fileduthao2->vb_du_thao_id = $fileduthao->vb_du_thao_id;
                            $fileduthao2->nguoi_tao = $fileduthao->nguoi_tao;
                            $fileduthao2->don_vi = $fileduthao->don_vi;
                            $fileduthao2->stt = 1;
                            $fileduthao2->save();
                        }
                    }

                }

            }
            if ($filetrinhky && count($filetrinhky) > 0) {
                foreach ($filetrinhky as $key => $getFile) {
                    $typeArray = explode('.', $getFile->getClientOriginalName());
                    $extFile = strtolower($typeArray[1]);
                    $fileduthao = new Fileduthao();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);
                    $fileduthao->ten_file = $fileName;
                    $fileduthao->duong_dan = $urlFile;
                    $fileduthao->duoi_file = $extFile;
                    $fileduthao->vb_du_thao_id = $duthao->id;
                    $fileduthao->nguoi_tao = $this->user->id;
                    $fileduthao->don_vi = $this->user->donvi_id;
                    if ($extFile == 'pdf') {
                        $fileduthao->stt = 2;
                    } else {
                        $fileduthao->stt = 3;
                    }
                    $fileduthao->save();
                }
                if ($fileduthao->duoi_file == 'doc' || $fileduthao->duoi_file == 'docx') {
                    if (config('system.convert_doc_to_pdf') == true) {
                        $explodeFileDoc = explode('.', $fileduthao->ten_file);
                        $filePdf = $explodeFileDoc[0] . '.' . 'pdf';
                        $urlFile2 = '/vanBanDiFile_' . date('Y') . '/' . $filePdf;
                        $convert = new OfficeConverter(public_path($fileduthao->duong_dan));
                        $convert->convertTo($filePdf);
                        $fileduthao3 = new Fileduthao();
                        $fileduthao3->ten_file = $filePdf;
                        $fileduthao3->duong_dan = $urlFile2;
                        $fileduthao3->duoi_file = 'pdf';
                        $fileduthao3->vb_du_thao_id = $fileduthao->vb_du_thao_id;
                        $fileduthao3->nguoi_tao = $fileduthao->nguoi_tao;
                        $fileduthao3->don_vi = $fileduthao->don_vi;
                        $fileduthao3->stt = 2;
                        $fileduthao3->save();
                    }
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
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $duthao = Duthaovanbandi::where('id',$id)->first();
        $duthao->delete();
        return redirect()->route('Danhsachduthao')->with('success','Xóa thành công !');
    }
}
