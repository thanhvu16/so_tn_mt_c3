<?php

namespace Modules\GiayMoiDi\Http\Controllers;

use App\Common\AllPermission;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use File , auth;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\Admin\Entities\MailTrongThanhPho;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDi\Entities\NoiNhanMail;
use Modules\VanBanDi\Entities\NoiNhanMailNgoai;
use Modules\VanBanDi\Entities\VanBanDi;

class GiayMoiDiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $user= auth::user();
        $trichyeu = $request->get('vb_trichyeu');
        $so_ky_hieu = $request->get('vb_sokyhieu');
        $chucvu = $request->get('chuc_vu');
        $donvisoanthao = $request->get('donvisoanthao_id');
        $so_van_ban = $request->get('sovanban_id');
        $giohop = $request->get('gio_hop');
        $nguoi_ky = $request->get('nguoiky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $ngaybanhanhstart = $request->get('vb_ngaybanhanh_start');
        $ngaybanhanhend = $request->get('vb_ngaybanhanh_end');
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_nguoiKy = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 2, 'loai_van_ban_id' => 1000])->where('so_di', '!=', '')->whereNull('deleted_at')
//        $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 2, 'loai_van_ban_id' => 1000])->whereNull('deleted_at')
            ->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
                }
            })
            ->where(function ($query) use ($giohop) {
                if (!empty($giohop)) {
                    return $query->where('gio_hop', $giohop);
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
                    return $query->where('ngay_hop', '>=', $ngaybatdau)
                        ->where('ngay_hop', '<=', $ngayketthuc);
                }
                if ($ngaybatdau == '' && $ngayketthuc != '') {
                    $ngaybatdau = $ngayketthuc;
                    return $query->where('ngay_hop', '>=', $ngaybatdau)
                        ->where('ngay_hop', '<=', $ngayketthuc);
                }
                if ($ngaybatdau != '' && $ngayketthuc == '') {
                    $ngayketthuc = $ngaybatdau;
                    return $query->where('ngay_hop', '>=', $ngaybatdau)
                        ->where('ngay_hop', '<=', $ngayketthuc);
                }
            })
            ->where(function ($query) use ($ngaybanhanhstart, $ngaybanhanhend) {
                if ($ngaybanhanhstart != '' && $ngaybanhanhend != '' && $ngaybanhanhstart <= $ngaybanhanhend) {
                    return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhstart)
                        ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                }
                if ($ngaybanhanhstart == '' && $ngaybanhanhend != '') {
                    $ngaybatdau = $ngaybanhanhend;
                    return $query->where('ngay_ban_hanh', '>=', $ngaybatdau)
                        ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                }
                if ($ngaybanhanhstart != '' && $ngaybanhanhend == '') {
                    $ngaybanhanhend = $ngaybanhanhstart;
                    return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhstart)
                        ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                }
            })
            ->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        return view('giaymoidi::giay_moi_di.index', compact('ds_vanBanDi', 'ds_DonVi', 'ds_nguoiKy','ds_soVanBan'));
    }
    public function giay_moi_di_co_so(Request $request)
    {
        $user= auth::user();
        $trichyeu = $request->get('vb_trichyeu');
        $so_ky_hieu = $request->get('vb_sokyhieu');
        $chucvu = $request->get('chuc_vu');
        $donvisoanthao = $request->get('donvisoanthao_id');
        $so_van_ban = $request->get('sovanban_id');
        $giohop = $request->get('gio_hop');
        $nguoi_ky = $request->get('nguoiky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $ngaybanhanhstart = $request->get('vb_ngaybanhanh_start');
        $ngaybanhanhend = $request->get('vb_ngaybanhanh_end');
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_nguoiKy = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
//        $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 2, 'loai_van_ban_id' => 1000])->where('so_di', '!=', '')->whereNull('deleted_at')
        $ds_vanBanDi = VanBanDi::where(['loai_van_ban_giay_moi' => 2, 'loai_van_ban_id' => 1000])->whereNull('deleted_at')
            ->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichyeu%");
                }
            })
            ->where(function ($query) use ($giohop) {
                if (!empty($giohop)) {
                    return $query->where('gio_hop', $giohop);
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
                    return $query->where('ngay_hop', '>=', $ngaybatdau)
                        ->where('ngay_hop', '<=', $ngayketthuc);
                }
                if ($ngaybatdau == '' && $ngayketthuc != '') {
                    $ngaybatdau = $ngayketthuc;
                    return $query->where('ngay_hop', '>=', $ngaybatdau)
                        ->where('ngay_hop', '<=', $ngayketthuc);
                }
                if ($ngaybatdau != '' && $ngayketthuc == '') {
                    $ngayketthuc = $ngaybatdau;
                    return $query->where('ngay_hop', '>=', $ngaybatdau)
                        ->where('ngay_hop', '<=', $ngayketthuc);
                }
            })
            ->where(function ($query) use ($ngaybanhanhstart, $ngaybanhanhend) {
                if ($ngaybanhanhstart != '' && $ngaybanhanhend != '' && $ngaybanhanhstart <= $ngaybanhanhend) {
                    return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhstart)
                        ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                }
                if ($ngaybanhanhstart == '' && $ngaybanhanhend != '') {
                    $ngaybatdau = $ngaybanhanhend;
                    return $query->where('ngay_ban_hanh', '>=', $ngaybatdau)
                        ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                }
                if ($ngaybanhanhstart != '' && $ngaybanhanhend == '') {
                    $ngaybanhanhend = $ngaybanhanhstart;
                    return $query->where('ngay_ban_hanh', '>=', $ngaybanhanhstart)
                        ->where('ngay_ban_hanh', '<=', $ngaybanhanhend);
                }
            })
            ->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        return view('giaymoidi::giay_moi_di.dacoso', compact('ds_vanBanDi', 'ds_DonVi', 'ds_nguoiKy','ds_soVanBan'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themGiayMoiDi());
        $user= auth::user();
//        $donvicap2 = Donvi::where(['cap_don_vi' => DON_VI_CAP_2, 'trang_thai' => 1])->whereNull('deleted_at')->first();
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
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $ds_nguoiKy = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan =LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $nguoinhan = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        return view('giaymoidi::giay_moi_di.create',compact('ds_mucBaoMat','nguoinhan','ds_doKhanCap','ds_loaiVanBan','ds_soVanBan',
            'ds_nguoiKy','emailngoaithanhpho','emailtrongthanhpho','ds_DonVi'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $donvinhanmailtrongtp = !empty($request['don_vi_nhan_trong_thanh_php']) ? $request['don_vi_nhan_trong_thanh_php'] : null;
        $donvinhanmailngoaitp = !empty($request['don_vi_nhan_ngoai_thanh_pho']) ? $request['don_vi_nhan_ngoai_thanh_pho'] : null;
        $gio_hop= date ('H:i',strtotime($request->gio_hop));
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
        $vanbandi->gio_hop = $gio_hop;
        $vanbandi->ngay_hop = $request->ngay_hop;
        $vanbandi->dia_diem = $request->dia_diem;
        $vanbandi->user_id = $request->nguoi_nhan;
        $vanbandi->loai_van_ban_giay_moi = 2;
        $vanbandi->nguoi_tao = auth::user()->id;
        $vanbandi->save();

//        $canbonhan = new Vanbandichoduyet();
//        $canbonhan->van_ban_di_id = $vanbandi->id;
//        $canbonhan->can_bo_chuyen_id = $vanbandi->nguoi_tao;
//        $canbonhan->can_bo_nhan_id = $request->nguoi_nhan;
//        $canbonhan->save();

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
        return redirect()->route('giay-moi-di.index')->with('success', 'Thêm giấy mời thành công ! ');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('giaymoidi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        canPermission(AllPermission::suaGiayMoiDi());
        $user= auth::user();
        $ds_DonVi = DonVi::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $emailtrongthanhpho = MailTrongThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $emailngoaithanhpho = MailNgoaiThanhPho::orderBy('ten_don_vi', 'asc')->get();
        $ds_nguoiKy = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $ds_soVanBan = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_loaiVanBan =LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $ds_mucBaoMat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $nguoinhan = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $giaymoidi = VanBanDi::where('id',$id)->first();
        $lay_emailtrongthanhpho = NoiNhanMail::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        $lay_emailngoaithanhpho = NoiNhanMailNgoai::where(['van_ban_di_id' => $id])->whereIn('status', [1, 2])->get();
        return view('giaymoidi::giay_moi_di.edit',compact('ds_mucBaoMat','nguoinhan','ds_doKhanCap','ds_loaiVanBan','ds_soVanBan',
            'ds_nguoiKy','emailngoaithanhpho','emailtrongthanhpho','ds_DonVi','giaymoidi','lay_emailngoaithanhpho','lay_emailtrongthanhpho'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $gio_hop= date ('H:i',strtotime($request->gio_hop));
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
        $vanbandi->gio_hop = $gio_hop;
        $vanbandi->ngay_hop = $request->ngay_hop;
        $vanbandi->dia_diem = $request->dia_diem;
        $vanbandi->user_id = $request->nguoi_nhan;
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
        return redirect()->back()
            ->with('failed', 'Cập nhật thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaGiayMoiDi());
        $giaymoidi = VanBanDi::where('id',$id)->first();
        $giaymoidi ->delete();
        return redirect()->back()->with('xóa giấy mời thành công!');
    }
}
