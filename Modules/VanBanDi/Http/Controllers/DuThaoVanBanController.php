<?php

namespace Modules\VanBanDi\Http\Controllers;


use App\Common\AllPermission;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use auth,File;
use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\Admin\Entities\MailTrongThanhPho;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use Modules\VanBanDi\Entities\Duthaovanbandi;
use Modules\VanBanDi\Entities\Fileduthao;
use function GuzzleHttp\Promise\all;

class DuThaoVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $date = Carbon::now()->format('Y-m-d');
        $donvikhongdieuhanh= DonVi::where('dieu_hanh', '!=',1)->whereNull('deleted_at')->get();
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $lanhdaotrongphong = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $lanhdaokhac = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
//        $lanhdaokhac = User::where('don_vi_id', '!=', auth::user()->don_vi_id)->whereNull('deleted_at')->get();

        $ds_nguoiKy = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
        return view('vanbandi::Du_thao_van_ban_di.index', compact('ds_loaiVanBan', 'ds_nguoiKy', 'lanhdaotrongphong', 'lanhdaokhac','date'));
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
                $extFile  = $getFile->extension();
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
        $lanhdaotrongphong = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $lanhdaokhac = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $ds_nguoiKy = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
        $lay_can_bo_phong = CanBoPhongDuThao::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $lay_can_bo_khac =  CanBoPhongDuThaoKhac::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $duthao = Duthaovanbandi::where('id', $id)->first();
        return view('vanbandi::Du_thao_van_ban_di.duthaocu', compact('duthao', 'ds_nguoiKy', 'lanhdaokhac', 'lanhdaotrongphong', 'ds_loaiVanBan', 'lay_can_bo_khac', 'lay_can_bo_phong'));
    }
    public function thongtinvanban($id)
    {
        $file = Fileduthao::where(['vb_du_thao_id' => $id])->where('stt', '!=', 0)->get();
        $ds_nguoiKy = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $ds_DonVi = Donvi::whereNull('deleted_at')
            ->orderBy('ten_don_vi', 'asc')->get();
        $ds_soVanBan  = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('ten_muc_do','asc')->get();
        $ds_mucBaoMat =DoMat::wherenull('deleted_at')->orderBy('ten_muc_do','asc')->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $date = Carbon::now()->format('Y-m-d');
        $id_duthao = $id;
        $vanbanduthao = Duthaovanbandi::where('id', $id)->first();
        $donvicap2 = Donvi::whereNull('deleted_at')->first();
        $nguoinhan = null;
        $nguoinhan = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
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


        return view('vanbandi::Du_thao_van_ban_di.tao_van_ban_di', compact('ds_nguoiKy', 'ds_loaiVanBan', 'ds_DonVi', 'ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'vanbanduthao', 'date', 'id_duthao', 'nguoinhan', 'file','emailngoaithanhpho','emailtrongthanhpho'));
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
        $lanhdaotrongphong = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $lanhdaokhac = User::where(['don_vi_id' => auth::user()->don_vi_id])->whereNull('deleted_at')->get();
        $ds_nguoiKy = User::orderBy('username', 'desc')->whereNull('deleted_at')->get();
        $lay_can_bo_phong = CanBoPhongDuThao::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $lay_can_bo_khac = CanBoPhongDuThaoKhac::where(['du_thao_vb_id' => $id])->whereIn('trang_thai', [1, 2])->get();
        $duthao = Duthaovanbandi::where('id', $id)->first();
        $file = Fileduthao::where(['vb_du_thao_id' => $id])->where('stt', '!=', 0)->get();
        return view('vanbandi::Du_thao_van_ban_di.edit', compact('duthao', 'ds_nguoiKy', 'lanhdaokhac', 'lanhdaotrongphong', 'ds_loaiVanBan', 'lay_can_bo_khac', 'lay_can_bo_phong','file'));
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






            if ($filehoso && count($filehoso) > 0) {
                foreach ($filehoso as $key => $getFile) {

                    $typeArray = explode('.', $getFile->getClientOriginalName());
                    $extFile = $getFile->extension();
                    $ten =strtolower($tenfilehoso[$key]). '_' . '.' . $extFile;
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
                    $extFile  = $getFile->extension();
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
        $donvinhanmailtrongtp = !empty($request['don_vi_nhan_trong_thanh_php']) ? $request['don_vi_nhan_trong_thanh_php'] : null;
        $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
        $canbothuocduthaocu = CanBoPhongDuThao::where('du_thao_vb_id', $request->id_duthao)->get();
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
        $uploadPath = public_path('vanBanDiFile_' . date('Y'));
        $tenfilehoso = !empty($request['txt_file']) ? $request['txt_file'] : null;
        $filehoso = !empty($request['file_name']) ? $request['file_name'] : null;
        $filephieutrinh = !empty($request['file_phieu_trinh']) ? $request['file_phieu_trinh'] : null;
        $filetrinhky = !empty($request['file_trinh_ky']) ? $request['file_trinh_ky'] : null;
        $duthaochot = Duthaovanbandi::where('id', $request->id_duthao)->first();
        $duthaochot->stt = 3;
        $duthaochot->save();
        $vanbandi = new VanBanDi();
        $vanbandi->vb_trichyeu = $request->vb_trichyeu;
        $vanbandi->vb_sokyhieu = $request->vb_sokyhieu;
        $vanbandi->vb_ngaybanhanh = $request->vb_ngaybanhanh;
        $vanbandi->loaivanban_id = $request->loaivanban_id;
        $vanbandi->dokhan_id = $request->dokhan_id;
        $vanbandi->chuc_vu = $request->chuc_vu;
        $vanbandi->dobaomat_id = $request->dobaomat_id;
        $vanbandi->linhvuc_id = $request->linhvuc_id;
        $vanbandi->donvisoanthao_id = $request->donvisoanthao_id;
        $vanbandi->sovanban_id = $request->sovanban_id;
        $vanbandi->nguoiky_id = $request->nguoiky_id;
        $vanbandi->vb_soBan = $request->vb_soBan;
        $vanbandi->vb_soTrang = $request->vb_soTrang;
        $vanbandi->van_ban_den_don_vi_id = $request->van_ban_den_don_vi_id ?? null;
        $vanbandi->nguoi_tao = $this->user->id;
        if ($duthaochot->loai_van_ban_id == 1000) {
            $vanbandi->loai_vanban_giay_moi = 2;
        } else {
            $vanbandi->loai_vanban_giay_moi = 1;
        }
        $vanbandi->save();


        if ($filetrinhky && count($filetrinhky) > 0) {
            if ($filehoso && count($filehoso) > 0) {
                foreach ($filehoso as $key => $getFile) {
                    $typeArray = explode('.', $getFile->getClientOriginalName());
                    $extFile = strtolower($typeArray[1]);
                    $ten = strSlugFileName(strtolower($tenfilehoso[$key]), '_') . '.' . $extFile;
                    $vbDiFile = new FileVanBanDi();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);

                    $vbDiFile->tenfile = isset($ten) ? $ten : $fileName;
                    $vbDiFile->duongdan = $urlFile;
                    $vbDiFile->vanbandi_id = $vanbandi->id;
                    $vbDiFile->nguoidung_id = $this->user->id;
                    $vbDiFile->donvi_id = $this->user->donvi_id;
                    $vbDiFile->trangthai = 3;
                    $vbDiFile->save();

                }


            }
            if ($filephieutrinh && count($filephieutrinh) > 0) {
                foreach ($filephieutrinh as $key => $getFile) {
                    $typeArray = explode('.', $getFile->getClientOriginalName());
                    $extFile = strtolower($typeArray[1]);
                    $vbDiFile = new FileVanBanDi();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);

                    $vbDiFile->tenfile = $fileName;
                    $vbDiFile->duongdan = $urlFile;
                    $vbDiFile->vanbandi_id = $vanbandi->id;
                    $vbDiFile->nguoidung_id = $this->user->id;
                    $vbDiFile->donvi_id = $this->user->donvi_id;
                    $vbDiFile->trangthai = 1;

                    if ($extFile == 'pdf') {
                        $vbDiFile->trangthai = 1;
                    } else {
                        $vbDiFile->trangthai = 3;
                    }
                    $vbDiFile->save();

                }
                if ($extFile == 'doc' || $extFile == 'docx') {
                    if (config('system.convert_doc_to_pdf') == true) {
                        $explodeFileDoc = explode('.', $vbDiFile->ten_file);
                        $filePdf = $explodeFileDoc[0] . '.' . 'pdf';
                        $urlFile2 = '/vanBanDiFile_' . date('Y') . '/' . $filePdf;
                        $convert = new OfficeConverter(public_path($vbDiFile->duong_dan));
                        $convert->convertTo($filePdf);
                        $vbDiFile2 = new FileVanBanDi();
                        $vbDiFile2->ten_file = $filePdf;
                        $vbDiFile2->duong_dan = $urlFile2;

                        $vbDiFile2->vanbandi_id = $vbDiFile->vanbandi_id;
                        $vbDiFile2->nguoidung_id = $vbDiFile->nguoidung_id;
                        $vbDiFile2->donvi_id = $vbDiFile->donvi_id;
                        $vbDiFile2->trangthai = 1;

                        $vbDiFile2->save();
                    }
                }

            }
            if ($filetrinhky && count($filetrinhky) > 0) {
                foreach ($filetrinhky as $key => $getFile) {
                    $typeArray = explode('.', $getFile->getClientOriginalName());
                    $extFile = strtolower($typeArray[1]);
                    $vbDiFile = new FileVanBanDi();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = '/vanBanDiFile_' . date('Y') . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);

                    $vbDiFile->tenfile = $fileName;
                    $vbDiFile->duongdan = $urlFile;
                    $vbDiFile->vanbandi_id = $vanbandi->id;
                    $vbDiFile->nguoidung_id = $this->user->id;
                    $vbDiFile->donvi_id = $this->user->donvi_id;
                    $vbDiFile->trangthai = 2;

                    if ($extFile == 'pdf') {
                        $vbDiFile->trangthai = 2;
                    } else {
                        $vbDiFile->trangthai = 3;
                    }
                    $vbDiFile->save();
                    if ($extFile == 'doc' || $extFile == 'docx') {
                        if (config('system.convert_doc_to_pdf') == true) {
                            $explodeFileDoc = explode('.', $vbDiFile->ten_file);
                            $filePdf = $explodeFileDoc[0] . '.' . 'pdf';
                            $urlFile2 = '/vanBanDiFile_' . date('Y') . '/' . $filePdf;
                            $convert = new OfficeConverter(public_path($vbDiFile->duong_dan));
                            $convert->convertTo($filePdf);
                            $vbDiFile2 = new FileVanBanDi();
                            $vbDiFile2->ten_file = $filePdf;
                            $vbDiFile2->duong_dan = $urlFile2;

                            $vbDiFile2->vanbandi_id = $vbDiFile->vanbandi_id;
                            $vbDiFile2->nguoidung_id = $vbDiFile->nguoidung_id;
                            $vbDiFile2->donvi_id = $vbDiFile->donvi_id;
                            $vbDiFile2->trangthai = 2;

                            $vbDiFile2->save();
                        }
                    }
                }

            }
        } else {

            //lấy file cũ vào để làm file vb đi
            $fileduthao = Fileduthao::where(['vb_du_thao_id' => $request->id_duthao])->where('stt', '!=', 0)->OrderBy('created_at', 'desc')->get();

            foreach ($fileduthao as $file) {
                $explodeFileDoc = explode('.', $file->ten_file);
                $extFile = strtolower($explodeFileDoc[1]);

                if($file->stt == 1 || $file->stt == 2 )
                {

                    if($extFile == 'doc' || $extFile == 'docx'){

                        if (config('system.convert_doc_to_pdf') == true) {

                            $explodeFileDoc = explode('.', $file->ten_file);
                            $filePdf = $explodeFileDoc[0] . '.' . 'pdf';
                            $urlFile2 = '/vanBanDiFile_' . date('Y') . '/' . $filePdf;
                            $convert = new OfficeConverter(public_path($file->duong_dan));
                            $convert->convertTo($filePdf);
                            $vbDiFile2 = new FileVanBanDi();
                            $vbDiFile2->tenfile = $filePdf;
                            $vbDiFile2->duongdan = $urlFile2;
                            $vbDiFile2->vanbandi_id = $vanbandi->id;
                            $vbDiFile2->nguoidung_id = $file->nguoi_tao;
                            $vbDiFile2->donvi_id = $file->don_vi;
                            $vbDiFile2->trangthai = $file->stt;
                            $vbDiFile2->save();
                        }
                    }else{
                        $filevanbandi = new FileVanBanDi();
                        $filevanbandi->tenfile = $file->ten_file ?? null;
                        $filevanbandi->duongdan = $file->duong_dan ?? null;
                        $filevanbandi->vanbandi_id = $vanbandi->id;
                        $filevanbandi->nguoidung_id = $file->nguoi_tao;
                        $filevanbandi->donvi_id = $file->don_vi;
                        $filevanbandi->trangthai = $file->stt;
                        $filevanbandi->save();
                    }

                }else{
                    ;
                    $filevanbandi = new FileVanBanDi();
                    $filevanbandi->tenfile = $file->ten_file ?? null;
                    $filevanbandi->duongdan = $file->duong_dan ?? null;
                    $filevanbandi->vanbandi_id = $vanbandi->id;
                    $filevanbandi->nguoidung_id = $file->nguoi_tao;
                    $filevanbandi->donvi_id = $file->don_vi;
                    $filevanbandi->trangthai = $file->stt;
                    $filevanbandi->save();
                }




            }


        }

        $canbonhan = new Vanbandichoduyet();
        $canbonhan->van_ban_di_id = $vanbandi->id;
        $canbonhan->can_bo_chuyen_id = $vanbandi->nguoi_tao;
        $canbonhan->can_bo_nhan_id = $request->nguoi_nhan;
        $canbonhan->id_du_thao = $duthaochot->id;
        $canbonhan->save();
        if ($donvinhanmailtrongtp && count($donvinhanmailtrongtp) > 0) {
            foreach ($donvinhanmailtrongtp as $key => $trong) {
                $mailtrong = new NoiNhanMail();
                $mailtrong->van_ban_di_id = $vanbandi->id;
                $mailtrong->email = $trong;
                $mailtrong->save();
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
        return redirect()->route('Danhsachduthao')->with('success', 'Thêm văn bản đi thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaDuThao());
        $duthao = Duthaovanbandi::where('id',$id)->first();
        $duthao->delete();
        return redirect()->route('Danhsachduthao')->with('success','Xóa thành công !');
    }
}
