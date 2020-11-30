<?php

namespace Modules\VanBanDen\Http\Controllers;

use App\Common\AllPermission;
use App\Http\Controllers\Controller;
use App\Models\QlvbVbDenDonVi as VbDenDonVi;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Admin\Entities\DoKhan;
use Modules\Admin\Entities\DoMat;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\LoaiVanBan;
use Modules\Admin\Entities\NgayNghi;
use Modules\Admin\Entities\SoVanBan;
use File, auth, DB;
use Modules\LayVanBanTuEmail\Entities\GetEmail;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;
use function GuzzleHttp\Promise\all;

class VanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $trichyeu = $request->get('vb_trich_yeu');
        $so_ky_hieu = $request->get('vb_so_ky_hieu');
        $co_quan_ban_hanh = $request->get('co_quan_ban_hanh_id');

        $so_den = $request->get('vb_so_den');
        $loai_van_ban = $request->get('loai_van_ban_id');
        $so_van_ban = $request->get('so_van_ban_id');
        $nguoi_ky = $request->get('nguoi_ky_id');
        $ngaybatdau = $request->get('start_date');
        $ngayketthuc = $request->get('end_date');
        $ds_vanBanDen = VanBanDen::
//            where([
//                'don_vi_id' => $donvi->ma_don_vi_cha,
//                'type' => 2,
//                'trang_thai' => 1
            where('so_van_ban_id', '!=', 100)
            ->where(function ($query) use ($trichyeu) {
                if (!empty($trichyeu)) {
                    return $query->where('vb_trich_yeu', 'LIKE', "%$trichyeu%");
                }
            })->where(function ($query) use ($so_den) {
                if (!empty($so_den)) {
                    return $query->where('so_den', 'LIKE', "%$so_den%");
                }
            })
            ->where(function ($query) use ($co_quan_ban_hanh) {
                if (!empty($co_quan_ban_hanh)) {
                    return $query->where('co_quan_ban_hanh', 'LIKE', "%$co_quan_ban_hanh%");
                }
            })
            ->where(function ($query) use ($nguoi_ky) {
                if (!empty($nguoi_ky)) {
                    return $query->where('nguoi_ky', 'LIKE', "%$nguoi_ky%");
                }
            })
            ->where(function ($query) use ($so_ky_hieu) {
                if (!empty($so_ky_hieu)) {
                    return $query->where('so_ky_hieu', 'LIKE', "%$so_ky_hieu%");
                }
            })->where(function ($query) use ($loai_van_ban) {
                if (!empty($loai_van_ban)) {
                    return $query->where('loai_van_ban_id', "$loai_van_ban");
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
        $ds_loaiVanBan  = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $ds_soVanBan = $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('ten_muc_do','asc')->get();
        $ds_mucBaoMat =DoMat::wherenull('deleted_at')->orderBy('ten_muc_do','asc')->get();

        return view('vanbanden::van_ban_den.index',compact('ds_vanBanDen','ds_soVanBan','ds_doKhanCap','ds_mucBaoMat','ds_loaiVanBan'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themVanBanDen());
        $user = auth::user();
        $user ->can('văn thư đơn vị');
//        dd($user ->can('văn thư đơn vị'));
//        if($user ->can('văn thư huyện') == true)
//        {
//            $soDen = VanBanDen::where([
//            'don_vi_id' => $datadonvi->ma_don_vi_cha,
//            'so_van_ban_id' => $request->so_van_ban_id,
//            'trang_thai' => 1
//        ])->whereYear('vb_ngay_ban_hanh', '=', $nam_sodi)->max('vb_so_den');
//        }else{
//            dd(0);
//        }

//        $soDenvb = $soDen + 1;
        $domat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $sovanban = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission('tham mưu')->where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        $ngaynhan = date('Y-m-d');
        $songay = 10;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;

        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }

        }

        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);

        return view('vanbanden::van_ban_den.create',compact('domat','dokhan','loaivanban','sovanban','users','hangiaiquyet'));
    }

    public function laysoden(Request $request)
    {
        $nam = date("Y");
        $soDenvb = VanBanDen::where([
            'don_vi_id' => auth::user()->don_vi_id,
            'so_van_ban_id' => $request->soVanBanId
//            'trang_thai' => 1
        ])->whereYear('ngay_ban_hanh', '=', $nam)->max('so_den');
        $soDen= $soDenvb+1;
//        dd($soDen);
            return response()->json(
                [
                    'html' => $soDen
                ]
            );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $user = auth::user();
        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($requestData['noi_dung']) ? $requestData['noi_dung'] : null;
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
//                if ($request->han_giai_quyet[$key] == null) {
//                    $vanbandv->han_xu_ly = $request->han_xu_ly;
//                } else {
//                    $vanbandv->han_xu_ly = $han_gq[$key];
//                }
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
            $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
            $vanbandv->don_vi_id = auth::user()->don_vi_id;
            $vanbandv->nguoi_tao = auth::user()->id;
            $vanbandv->save();
        }
        return redirect()->back()->with('success','Thêm văn bản thành công !!');
    }

    public function multiple_file(Request $request)
    {

        $uploadPath = UPLOAD_FILE_VAN_BAN_DEN;
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true, true);
        }
        $txtFiles = !empty($request['txt_file']) ? $request['txt_file'] : null;
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        if (empty($multiFiles) || count($multiFiles) == 0 || (count($multiFiles) > 19)) {
            return redirect()->back()->with('warning', 'Bạn phải chọn file hoặc phải chọn số lượng file nhỏ hơn 20 file   !');
        }
        foreach ($multiFiles as $key => $getFile) {
            $typeArray = explode('.', $getFile->getClientOriginalName());
            $tenchinhfile = strtolower($typeArray[0]);
            $extFile = $getFile->extension();
            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $urlFile = UPLOAD_FILE_VAN_BAN_DEN  . '/' . $fileName;
            $tachchuoi = explode("-", $tenchinhfile);
            $tenviettatso = strtoupper($tachchuoi[0]);
            $soden = (int)$tachchuoi[1];
            $yearsfile = (int)$tachchuoi[2];
            $sovanban = SoVanBan::where(['ten_viet_tat' => $tenviettatso])->whereNull('deleted_at')->first();
            $vanban = VanBanDen::where(['so_van_ban_id' => $sovanban->id, 'so_den' => $soden])->whereYear('ngay_ban_hanh', '=', $yearsfile)->first();
            if ($vanban) {
                $vbDenFile = new FileVanBanDen();
                $getFile->move($uploadPath, $fileName);
                $vbDenFile->ten_file = $tenchinhfile;
                $vbDenFile->duong_dan = $urlFile;
                $vbDenFile->duoi_file = $extFile;
                $vbDenFile->vb_den_id = $vanban->id;
                $vbDenFile->nguoi_dung_id = auth::user()->id;
                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                $vbDenFile->save();
            }

        }

        return redirect()->back()->with('success', 'Thêm file thành công !');
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

    public function chi_tiet_van_ban_den($id)
    {
        canPermission(AllPermission::suaVanBanDen());
        $user= auth::user();
        $van_ban_den = VanBanDen::where('id',$id)->WhereNull('deleted_at')->first();
        $domat = DoMat::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $dokhan = DoKhan::wherenull('deleted_at')->orderBy('id', 'desc')->get();
        $loaivanban = LoaiVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $sovanban = SoVanBan::wherenull('deleted_at')->orderBy('id', 'asc')->get();
        $users = User::permission('tham mưu')->where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();
        return view('vanbanden::van_ban_den.edit',compact('van_ban_den','domat','dokhan','loaivanban','sovanban','users'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $noi_dung = !empty($request['noi_dung']) ? $request['noi_dung'] : null;
        $han_giai_quyet = !empty($request['han_giai_quyet']) ? $request['han_giai_quyet'] : null;
        $vanbandv = VanBanDen::where('id', $id)->first();
        $checktrungsoden = VanBanDen::where(['so_van_ban_id'=>$request->so_van_ban,'id'=>$vanbandv->id])->first();
        $vanbandv->loai_van_ban_id = $request->loai_van_ban;
        $vanbandv->so_van_ban_id = $request->so_van_ban;
        if($checktrungsoden == null)
        {
            $soDen = VanBanDen::where([
                'so_van_ban_id' => $request->so_van_ban
            ])->max('so_den');
            $soDenvb = $soDen + 1;
            $vanbandv->so_den = $soDenvb;
        }
        $vanbandv->so_ky_hieu = $request->so_ky_hieu;
        $vanbandv->ngay_ban_hanh = $request->ngay_ban_hanh;
        $vanbandv->co_quan_ban_hanh = $request->co_quan_ban_hanh;
        $vanbandv->trich_yeu = $request->trich_yeu;
        $vanbandv->nguoi_ky = $request->nguoi_ky;
        $vanbandv->han_xu_ly = $request->han_xu_ly;
        $vanbandv->do_khan_cap_id = $request->do_khan;
        $vanbandv->do_bao_mat_id = $request->do_mat;
        $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;;
//        $vanbandv->han_giai_quyet = $request->han_giai_quyet;
        $vanbandv->don_vi_id = auth::user()->don_vi_id;;

        $vanbandv->noi_dung = $noi_dung[0];
        if ($request->han_giai_quyet[0] == null) {
            $vanbandv->han_xu_ly = $request->han_xu_ly;
            $vanbandv->han_giai_quyet = $request->han_xu_ly;
        } else {
            $vanbandv->han_xu_ly = $request->han_xu_ly;
            $vanbandv->han_giai_quyet = $han_giai_quyet[0];
        }
//        $vanbandv->han_giai_quyet = $han_giai_quyet[0];
        $vanbandv->save();


        return redirect()->back()->with('success', 'Cập nhật dữ liệu thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function delete_vb_den(Request $request)
    {
        canPermission(AllPermission::xoaVanBanDen());
        $vanbanden = VanBanDen::where('id',$request->id_vb)->first();
        $vanbanden->delete();
        return redirect()->route('van-ban-den.index')->with('success','Xóa thành công !');
    }


    public function dsvanbandentumail(Request $request)
    {
        $noiguimail = $request->get('noiguimail') ?? null;
        $tinhtrang = $request->get('tinhtrang') ?? 1;
        $getEmail = GetEmail::where(['mail_active' => $tinhtrang])
            ->where(function ($query) use ($noiguimail) {
                if (!empty($noiguimail)) {
                    return $query->where('noigui', $noiguimail);
                }
            })->orderBy('mail_date', 'desc')->paginate(20);
        return view('vanbanden::van_ban_den.dsvanbandentumail', compact('getEmail'));
    }

    public function deleteEmail($id)
    {
        $idEmail = GetEmail::find($id);
        $idEmail->mail_active = 3;
        $idEmail->save();
        return redirect()->back()->with('success', 'Xoá thành công!');

    }

    public function taovbdentumail(Request $request)
    {
        $file_xml = $request->get('xml');
        $id = $request->get('id');
        $file_pdf = $request->get('pdf');
        $file_doc = $request->get('doc');
        $file_xls = $request->get('xls');
        $email = GetEmail::where('id', $id)->first();
        $url_file = 'emailFile_' . substr($email->mail_date, 0, 4) . '/';
        $url_pdf = $url_file . $file_pdf;
        if (isset($file_doc))
            $url_doc = $url_file . $file_doc;
        else
            $url_doc = '';
        if (isset($file_xls))
            $url_xls = $url_file . $file_xls;
        else
            $url_xls = '';
        if (!empty($file_xml)) {
            $conten_xml = file_get_contents($url_file . $file_xml);
        }
        if (!empty($file_xml) && $conten_xml != '') {
            $string = preg_replace('/[\x00-\x1F\x7F]/u', '', $conten_xml);
            if (empty($string)) {
                $string = iconv('UTF-16LE', 'UTF-8', $conten_xml);
            }
            $data_xml = simplexml_load_string($string);

            $data_xml->STRNGAYKY = @date('Y-m-d', strtotime(str_replace('/', '-', $data_xml->STRNGAYKY)));
            if (isset($data_xml->STRNGAYHOP))
                $data_xml->STRNGAYHOP = date('Y-m-d', strtotime(str_replace('/', '-', $data_xml->STRNGAYHOP)));
            else

                $data_xml->STRNGAYHOP = '';
//            dd($data_xml->STRLOAIVANBAN);
            $loaivb_email = LoaiVanBan::where('ten_loai_van_ban', strtolower($data_xml->STRLOAIVANBAN))->first();
            $vb_so_den=null;
//            if (!empty($loaivb_email) && $loaivb_email == 100) {
//
//                $vb_so_den = QlvbVbDenDonVi::where(['so_van_ban_id' => 100, 'trang_thai' => 1])->orderBy('vb_so_den', 'desc')->first()->vb_so_den;
//                if (!empty($vb_so_den)) $vb_so_den = $vb_so_den + 1;
//                else $vb_so_den = 1;
//            } else {
//                $soDen = VbDenDonVi::where(['so_van_ban_id' => $loaivb_email, 'trang_thai' => 1])->max('vb_so_den');
//                dd($loaivb_email);
//                $soDen = empty($soDen) ? 1 : $soDen + 1;
//
//                $vb_so_den = $soDen;
//            }

            //check trung van ban
            if (!empty($data_xml->STRNGAYHOP)) {
                $data_xml->STRNGAYHOP = date('Y-m-d', strtotime(str_replace('/', '-', $data_xml->STRNGAYHOP)));

                $data_trung = VanBanDen::where(['so_ky_hieu' =>strtolower($data_xml->STRKYHIEU) , 'ngay_hop' => strtolower($data_xml->STRNGAYHOP), 'nguoi_ky' => strtolower($data_xml->STRNGUOIKY)])->first();
            } else {
                $data_trung = VanBanDen::where(['so_ky_hieu' => strtolower($data_xml->STRKYHIEU), 'ngay_ban_hanh' =>strtolower($data_xml->STRNGAYKY) , 'nguoi_ky' =>strtolower($data_xml->STRNGUOIKY)])->first();
            }
        } else {
            $data_xml = null;
            $loaivb_email = null;
            $data_trung = null;
            $vb_so_den = null;
        }


        $ds_loaiVanBan = LoaiVanBan::whereNull('deleted_at')->whereIn('loai_van_ban', [2, 3])
            ->orderBy('ten_loai_van_ban', 'desc')->get();
        $ds_loaiVanBan  = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get();
        $ds_soVanBan = $ds_sovanban = SoVanBan::wherenull('deleted_at')->orderBy('ten_so_van_ban', 'asc')->get();
        $ds_doKhanCap = DoKhan::wherenull('deleted_at')->orderBy('ten_muc_do','asc')->get();
        $ds_mucBaoMat =DoMat::wherenull('deleted_at')->orderBy('ten_muc_do','asc')->get();
        $user = auth::user();
//        $ds_capbanhanh = CapBanHanh::where('trang_thai', $this->trang_thai['active'])
//            ->orderBy('ngay_tao', 'desc')->get();
        $nguoi_dung = User::permission('tham mưu')->where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get();


        $ds_donvi_vb = DonVi::where('id', $user->donvi_id)->first();


        $type = 2;

        //lấy hạn
        $ngaynhan = date('Y-m-d');
        $songay = 10;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;
        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }
        }
        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);

        return view('vanbanden::van_ban_den.tao_vb_tu_mail', compact('data_xml', 'ds_loaiVanBan', 'ds_soVanBan', 'ds_doKhanCap', 'ds_mucBaoMat', 'type', 'email', 'loaivb_email', 'hangiaiquyet', 'url_pdf', 'url_doc', 'url_xls', 'id', 'data_trung', 'vb_so_den', 'nguoi_dung'));
    }

    public function hanmail(Request $request)
    {

        $ngaynhan = $request->get('ngay_hop_chinh');
        $songay = 2;
        $ngaynghi = NgayNghi::where('ngayNghi', '>', date('Y-m-d'))->where('trangthai', 1)->orderBy('id', 'desc')->get();
        $i = 0;
        foreach ($ngaynghi as $key => $value) {
            if ($value['ngayNghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngayNghi'] && $value['ngayNghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }
        }
        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        return response()->json(
            [
                'html' => $hangiaiquyet
            ]
        );
    }


    /**
     * Lưu văn bản đến từ mail
     * @param Request $request
     * @return Response
     */
    public function luuvanbantumail(Request $request)
    {
        $requestData = $request->all();
        //vb tu truc
        if (!empty($request->get('type_van_ban'))) {
            $docEmail = DocEmails::where('id', $request->id_vanban_tumail)->first();
            if ($docEmail) {
                $docEmail->status = 1;
                $docEmail->save();
            }
        } else {
            //vb tu mail
            $tbl_email = GetEmail::find($request->id_vanban_tumail);
            $tbl_email->mail_active = 2;
            $tbl_email->save();
        }
        $han_gq = $request->han_giai_quyet;
        $noi_dung = !empty($requestData['noi_dung']) ? $requestData['noi_dung'] : null;
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
                if ($request->han_giai_quyet[$key] == null) {
                    $vanbandv->han_xu_ly = $request->han_xu_ly;
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
            $vanbandv->lanh_dao_tham_muu = $request->lanh_dao_tham_muu;
            $vanbandv->don_vi_id = auth::user()->don_vi_id;
            $vanbandv->nguoi_tao = auth::user()->id;
            $vanbandv->save();
        }


        //upload file
        if (!empty($request->get('file_pdf'))) {
            foreach ($requestData['file_pdf'] as $file) {
                $vbDenFile = new FileVanBanDen();
                $vbDenFile->ten_file = str_replace('/', '_', $request->vb_so_ky_hieu) . $this->filename_extension($file);
                $vbDenFile->duong_dan = $file;
                $vbDenFile->duoi_file = $this->filename_extension($file);
                $vbDenFile->vb_den_id = $vanbandv->id;
                $vbDenFile->nguoi_dung_id = $vanbandv->nguoi_tao;
                $vbDenFile->don_vi_id = auth::user()->don_vi_id;
                $vbDenFile->save();
            }
        }
        return redirect()->route('dsvanbandentumail')->with('success', 'Thêm văn bản thành công !');

    }

    public function kiemTraTrichYeu(Request $request)
    {
        $user=auth::user();
        $so_ky_hieu = $request->input('so_ky_hieu');
        $ngay_ban_hanh = $request->input('ngay_ban_hanh');
        $data = VanBanDen::where(['so_ky_hieu' => $so_ky_hieu, 'ngay_ban_hanh' => $ngay_ban_hanh])->orderBy('id', 'desc')->take(5)->get();
        $ds_nguoiDung = User::orderBy('created_at', 'desc')->get(['id', 'ho_ten'])->toArray();
        $ds_nguoiDung = array_column($ds_nguoiDung, 'ho_ten', 'id');
        $ds_nguoiKy = User::where(['trang_thai'=> ACTIVE,'don_vi_id'=>$user->don_vi_id])->get(['id', 'ho_ten'])->toArray();
        $ds_nguoiKy = array_column($ds_nguoiKy, 'ho_ten', 'id');
        $ds_loaiVanBan = LoaiVanBan::wherenull('deleted_at')->orderBy('ten_loai_van_ban', 'asc')->get(['id', 'ten_loai_van_ban'])->toArray();
        $ds_loaiVanBan = array_column($ds_loaiVanBan, 'ten_loai_van_ban', 'id');
        $returnHTML = $data->isNotEmpty() ? view('vanbanden::van_ban_den.check_trung_van_ban',
            compact('data', 'ds_nguoiDung', 'ds_nguoiKy', 'ds_loaiVanBan'))->render() : '';
        return response()->json(
            [
                'is_relate' => $data->isNotEmpty() ? true : false,
                'html' => $returnHTML
            ]
        );
    }

    function filename_extension($filename)
    {
        $pos = strrpos($filename, '.');
        if ($pos === false) {
            return false;
        } else {
            return substr($filename, $pos + 1);
        }
    }
}










