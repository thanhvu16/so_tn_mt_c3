<?php

namespace Modules\VanBanDi\Http\Controllers;

use App\Common\AllPermission;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\MailTrongThanhPho;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\NoiNhanMail;
use Modules\VanBanDi\Entities\NoiNhanMailNgoai;
use Modules\VanBanDi\Entities\VanBanDi;
use auth , File ,DB;

class VanBanDiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $user= auth::user();
        $trichyeu = $request->get('vb_trichyeu');
        $loaivanban = $request->get('loaivanban_id');
        $so_ky_hieu = $request->get('vb_sokyhieu');
        $chucvu = $request->get('chuc_vu');
        $donvisoanthao = $request->get('donvisoanthao_id');
        $so_van_ban = $request->get('sovanban_id');

        $nguoi_ky = $request->get('nguoiky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_nguoiKy = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $ds_vanBanDi = VanBanDi::where('loai_van_ban_giay_moi',1)->whereNull('deleted_at')
//        $ds_vanBanDi = VanBanDi::where('loai_van_ban_giay_moi',1)->where('so_di', '!=', null)->whereNull('deleted_at')
            ->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
                }
            })
            ->where(function ($query) use ($chucvu) {
                if (!empty($chucvu)) {
                    return $query->where('chuc_vu', 'LIKE', "%$chucvu%");
                }
            })
            ->where(function ($query) use ($nguoi_ky) {
                if (!empty($nguoi_ky)) {
                    return $query->where('nguoi_ky', $nguoi_ky);
                }
            })->where(function ($query) use ($loaivanban) {
                if (!empty($loaivanban)) {
                    return $query->where('loai_van_ban_id', $loaivanban);
                }
            })->where(function ($query) use ($donvisoanthao) {
                if (!empty($donvisoanthao)) {
                    return $query->where('don_vi_soan_thao', $donvisoanthao);
                }
            })
            ->where(function ($query) use ($so_ky_hieu) {
                if (!empty($so_ky_hieu)) {
                    return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
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


        return view('vanbandi::van_ban_di.index', compact('ds_vanBanDi','ds_loaiVanBan', 'ds_soVanBan', 'ds_DonVi', 'ds_nguoiKy'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
//        $nguoinhan = null;
//        switch ($this->user->donvi->cap_don_vi) {
//            case 1:
//                break;
//            case 2:
//                break;
//            case 3:
//                switch ($this->user->vai_tro) {
//                    case 1:
//                        break;
//                    case 2:
//                        $nguoinhan = NguoiDung::where('donvi_id', $donvicap2->ma_id)->whereIn('vai_tro', [2, 3])->orderBy('ho_ten', 'asc')->get();
//                        break;
//                    case 3:
//                        $nguoinhan = NguoiDung::where('donvi_id', $this->user->donvi_id)->whereIn('vai_tro', [2])->orderBy('ho_ten', 'asc')->get();
//                        break;
//                    case 4:
//                        $nguoinhan = NguoiDung::where('donvi_id', $this->user->donvi_id)->whereIn('vai_tro', [2, 3])->orderBy('ho_ten', 'asc')->get();
//                        break;
//                }
//                break;
//        }
        canPermission(AllPermission::themVanBanDi());
        $user= auth::user();
        $nguoinhan = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();




        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();

        $ds_nguoiKy =  User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        return view('vanbandi::van_ban_di.create', compact('ds_nguoiKy',
            'ds_soVanBan', 'ds_loaiVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'ds_DonVi', 'nguoinhan', 'emailtrongthanhpho', 'emailngoaithanhpho'));
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
            $donvinhanmailtrongtp = !empty($request['don_vi_nhan_trong_thanh_php']) ? $request['don_vi_nhan_trong_thanh_php'] : null;
            $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
            $vanbandi = new VanBanDi();
            $vanbandi->trich_yeu = $request->vb_trichyeu;
            $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
            $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->do_khan_cap_id = $request->dokhan_id;
            $vanbandi->chuc_vu = $request->chuc_vu;
            $vanbandi->do_bao_mat_id = $request->dobaomat_id;
            $vanbandi->don_vi_soan_thao = $request->donvisoanthao_id;
            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->loai_van_ban_giay_moi = 1;
            $vanbandi->nguoi_tao = auth::user()->id;
            $vanbandi->save();

//            $canbonhan = new Vanbandichoduyet();
//            $canbonhan->van_ban_di_id = $vanbandi->id;
//            $canbonhan->can_bo_chuyen_id = $vanbandi->nguoi_tao;
//            $canbonhan->can_bo_nhan_id = $request->nguoi_nhan;
//            $canbonhan->save();
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
        canPermission(AllPermission::suaVanBanDi());
        $vanbandi = VanBanDi::where('id',$id)->first();
        $user = auth::user();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_nguoiKy = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();

        $lay_emailtrongthanhpho = NoiNhanMail::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_emailngoaithanhpho = NoiNhanMailNgoai::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        return view('vanbandi::van_ban_di.edit',compact('vanbandi','ds_soVanBan','ds_loaiVanBan','ds_DonVi','ds_doKhanCap',
            'ds_mucBaoMat','ds_nguoiKy','emailtrongthanhpho','emailngoaithanhpho','lay_emailtrongthanhpho','lay_emailngoaithanhpho'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $vanbandi = VanBanDi::where('id', $id)->first();
            $vanbandi->trich_yeu = $request->vb_trichyeu;
            $vanbandi->so_ky_hieu = $request->vb_sokyhieu;
            $vanbandi->ngay_ban_hanh = $request->vb_ngaybanhanh;
            $vanbandi->loai_van_ban_id = $request->loaivanban_id;
            $vanbandi->do_khan_cap_id = $request->dokhan_id;
            $vanbandi->chuc_vu = $request->chuc_vu;
            $vanbandi->do_bao_mat_id = $request->dobaomat_id;
            $vanbandi->don_vi_soan_thao = $request->donvisoanthao_id;
            $vanbandi->so_van_ban_id = $request->sovanban_id;
            $vanbandi->nguoi_ky = $request->nguoiky_id;
            $vanbandi->loai_van_ban_giay_moi = 1;
            $vanbandi->nguoi_tao = auth::user()->id;
            $vanbandi->save();
            $donvinhanmailtrongtp = !empty($request['don_vi_nhan_trong_thanh_php']) ? $request['don_vi_nhan_trong_thanh_php'] : null;
            $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
            $mailtrongtp = NoiNhanMail::where(['van_ban_di_id' => $id, 'status' => 1])->get();
            $mailngoaitp = NoiNhanMailNgoai::where(['van_ban_di_id' => $id, 'status' => 1])->get();
            $iddonviphong = $mailtrongtp->pluck('email')->toArray();
            $iddoviphongkhac = $mailngoaitp->pluck('email')->toArray();
            if ($donvinhanmailtrongtp && count($donvinhanmailtrongtp) > 0) {
                if (array_diff($donvinhanmailtrongtp, $iddonviphong) == null && count($iddonviphong) == count($donvinhanmailtrongtp)) {
                    //đây là trường hợp không thay đổi
                } else {
                    $mailtrong = NoiNhanMail::where('van_ban_di_id', $id)->get();
                    if (count($mailtrong) > 0) {
                        foreach ($mailtrong as $key => $xoahet) {
                            $mailtrongxoa = NoiNhanMail::where('id', $xoahet->id)->first();
                            $mailtrongxoa->status = 0;
                            $mailtrongxoa->save();
                        }
                    }


                    foreach ($donvinhanmailtrongtp as $key => $trong) {
                        $laymailmoi = new NoiNhanMail();
                        $laymailmoi->van_ban_di_id = $vanbandi->id;
                        $laymailmoi->email = $trong;
                        $laymailmoi->save();


                    }
                }
            }
            if ($donvinhanmailngoaitp && count($donvinhanmailngoaitp) > 0) {
                if (array_diff($donvinhanmailngoaitp, $iddoviphongkhac) == null && count($iddoviphongkhac) == count($donvinhanmailngoaitp)) {
                    //đây là trường hợp không thay đổi
                } else {
                    $mailngoai = NoiNhanMailNgoai::where('van_ban_di_id', $id)->get();
                    if (count($mailngoai) > 0) {
                        foreach ($mailngoai as $key => $xoahetngoai) {
                            $mailngoaixoa = NoiNhanMailNgoai::where('id', $xoahetngoai->id)->first();
                            $mailngoaixoa->status = 0;
                            $mailngoaixoa->save();
                        }
                    }
                    foreach ($donvinhanmailngoaitp as $key => $ngoai) {
                        $mailngoaimoi = new NoiNhanMailNgoai();
                        $mailngoaimoi->van_ban_di_id = $vanbandi->id;
                        $mailngoaimoi->email = $ngoai;
                        $mailngoaimoi->save();
                    }
                }

            }


            $isSuccess = true;
            DB::commit();
        } catch (Exception $e) {
            $isSuccess = false;
        }
        if ($isSuccess) {

            return redirect()->route('van-ban-di.index')
                ->with('success', 'Cập nhật thông tin văn bản thành công !');

        } else {
            redirect()->back()
                ->with('failed', 'Cập nhật thất bại, vui lòng thử lại !');
        }
    }
    public function multiple_file_di(Request $request)
    {
        $uploadPath = UPLOAD_FILE_VAN_BAN_DI;
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true, true);
        }
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        if (empty($multiFiles) || count($multiFiles) == 0 || (count($multiFiles) > 19)) {
            return redirect()->back()->with('warning', 'Bạn phải chọn file hoặc phải chọn số lượng file nhỏ hơn 20 file   !');
        }
        foreach ($multiFiles as $key => $getFile) {
            $typeArray = explode('.', $getFile->getClientOriginalName());
            $tenchinhfile = strtolower($typeArray[0]);
            $extFile = $getFile->extension();
            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $urlFile = UPLOAD_FILE_VAN_BAN_DI . '/' . $fileName;
            $tachchuoi = explode("-", $tenchinhfile);
            $tenviettatso = strtoupper($tachchuoi[0]);
            $sodi = (int)$tachchuoi[1];
            $loaivanban = LoaiVanBan::where(['ten_viet_tat' => $tenviettatso])->whereNull('deleted_at')->first();
            $vanban = null;
            if (!empty($loaivanban)) {
                $vanban = VanBanDi::where(['loai_van_ban_id' => $loaivanban->id, 'so_di' => $sodi])->first();
            }
            if ($vanban) {
                $vanBanDiFile = new FileVanBanDi();
                $getFile->move($uploadPath, $fileName);
                $vanBanDiFile->ten_file = $tenchinhfile;
                $vanBanDiFile->duong_dan = $urlFile;
                $vanBanDiFile->duoi_file = $extFile;
                $vanBanDiFile->van_ban_di_id = $vanban->id;
                $vanBanDiFile->nguoi_dung_id = auth::user()->id;
                $vanBanDiFile->don_vi_id = auth::user()->donvi_id;
//                $vanBanDiFile->loai_file = FileVanBanDi::LOAI_FILE_DA_KY;
                $vanBanDiFile->save();
            }
        }



        return redirect()->back()->with('success', 'Thêm file thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaVanBanDi());
        $vanbandi = VanBanDi::where('id',$id)->first();
        $vanbandi->delete();
        return redirect()->back()
            ->with('success', 'Xóa văn bản thành công !');
    }
}
