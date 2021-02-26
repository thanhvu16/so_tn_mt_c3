<?php

namespace Modules\VanBanDi\Http\Controllers;

use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\VanBanDi\Entities\CanBoPhongDuThao;
use Modules\VanBanDi\Entities\CanBoPhongDuThaoKhac;
use File,auth;
use Modules\VanBanDi\Entities\Filecanbogopyduthao;
use Modules\VanBanDi\Entities\Filecanbogopyduthaongoai;

class GopYVanbanDiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('vanbandi::index');
    }
    public function danhsachgopy()
    {
        $canbogopy = CanBoPhongDuThao::where(['can_bo_id' => auth::user()->id, 'trang_thai' => 1])->get();
        $key2 = count($canbogopy);
        $canbogopyngoai = CanBoPhongDuThaoKhac::where(['can_bo_id' => auth::user()->id, 'trang_thai' => 1])->get();
        $key1 = count($canbogopyngoai);
//        $nguoinhan = null;
//        switch (auth::user()->roles->pluck('name')[0]) {
//
//            case PHO_PHONG:
//                $nguoinhan = User::role([ CHUYEN_VIEN])->where('don_vi_id',auth::user()->don_vi_id)->get();
//                break;
//            case TRUONG_PHONG:
//                $nguoinhan = User::role([ PHO_PHONG])->get();
//                break;
//            case CHU_TICH:
//                $nguoinhan = User::role([PHO_CHUC_TICH])->where('don_vi_id',auth::user()->don_vi_id)->get();
//                break;
//        }
        return view('vanbandi::gop_y_du_thao.Danh_sach_gop_y_du_thao', compact('canbogopy', 'canbogopyngoai','key2','key1'));
    }
    public function danhsachgopyxong()
    {
        $canbogopy = CanBoPhongDuThao::where(['can_bo_id' => auth::user()->id])->whereIn('trang_thai', [2, 12])->get();
        $key2 = count($canbogopy);
        $canbogopyngoai = CanBoPhongDuThaoKhac::where(['can_bo_id' => auth::user()->id])->whereIn('trang_thai', [2, 12])->get();
        $key1 = count($canbogopyngoai);
        return view('vanbandi::gop_y_du_thao.Danh_sach_gop_y_du_thao_cu', compact('canbogopy', 'canbogopyngoai','key2','key1'));
    }

    public function gopy(Request $request, $id)
    {
        $uploadPath =FILE_Y_KIEN_GOP_Y;
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        $gopy = CanBoPhongDuThao::where('id', $id)->first();
        $gopy->y_kien = $request->y_kien;
        $gopy->trang_thai = 2;
        $gopy->save();

        if ($multiFiles && count($multiFiles) > 0) {
            foreach ($multiFiles as $key => $getFile) {
                $extFile = $getFile->extension();
                $fileduthao = new Filecanbogopyduthao();
                $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                $urlFile =FILE_Y_KIEN_GOP_Y . '/' . $fileName;
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true, true);
                }
                $getFile->move($uploadPath, $fileName);
                $fileduthao->duong_dan = $urlFile;
                $fileduthao->duoi_file = $extFile;
                $fileduthao->Du_thao_id = $request->id_van_ban;
                $fileduthao->can_bo_gop_y = $request->id_can_bo;
                $fileduthao->save();
                UserLogs::saveUserLogs('góp ý dự thảo văn bản', $fileduthao);
            }

        }
        return redirect()->back()->with('success', 'Góp ý thành công !');
    }
    public function themgopyvbngoai(Request $request, $id)
    {
        $uploadPath = FILE_Y_KIEN_GOP_Y;
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        if ($request->can_bo_chuyen_xuong) {
            $laycanbotrung = CanBoPhongDuThaoKhac::where('du_thao_vb_id',$request->id_van_ban)->get();
            $idcanbo = $laycanbotrung->pluck('can_bo_id');
            foreach ($idcanbo as $data)
            {
                if ($data ==  $request->can_bo_chuyen_xuong)
                {
                    $taogopymoi = CanBoPhongDuThaoKhac::where('id', $request->id_can_bo)->first();
                    $taogopymoi->y_kien = $request->y_kien;
                    $taogopymoi->trang_thai = 2;
                    $taogopymoi->can_bo_giao_xuong = $request->can_bo_chuyen_xuong;
                    $taogopymoi->save();

                }else{
                    $taogopymoi = CanBoPhongDuThaoKhac::where('id', $request->id_can_bo)->first();
                    $taogopymoi->y_kien = $request->y_kien;
                    $taogopymoi->trang_thai = 2;
                    $taogopymoi->can_bo_giao_xuong = $request->can_bo_chuyen_xuong;
                    $taogopymoi->save();
                    $taomoi = new CanBoPhongDuThaoKhac();
                    $taomoi->can_bo_id = $request->can_bo_chuyen_xuong;
                    $taomoi->du_thao_vb_id = $taogopymoi->du_thao_vb_id;
                    $taomoi->gop_y_cha = $taogopymoi->id;
                    $taomoi->save();
                }
            }

            if ($multiFiles && count($multiFiles) > 0) {
                foreach ($multiFiles as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $fileduthao = new Filecanbogopyduthaongoai();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
                    $urlFile = FILE_Y_KIEN_GOP_Y . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);
                    $fileduthao->duong_dan = $urlFile;
                    $fileduthao->duoi_file = $extFile;
                    $fileduthao->Du_thao_id = $request->id_van_ban;
                    $fileduthao->can_bo_gop_y = $request->id_can_bo;
                    $fileduthao->save();
                }

            }


        } else {

            $gopy = CanBoPhongDuThaoKhac::where('id', $id)->first();
            $gopy->y_kien = $request->y_kien;
            $gopy->trang_thai = 2;
            $gopy->save();

            if ($multiFiles && count($multiFiles) > 0) {
                foreach ($multiFiles as $key => $getFile) {
                    $extFile =$getFile->extension();
                    $fileduthao = new Filecanbogopyduthaongoai();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = FILE_Y_KIEN_GOP_Y . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);
                    $fileduthao->duong_dan = $urlFile;
                    $fileduthao->duoi_file = $extFile;
                    $fileduthao->Du_thao_id = $request->id_van_ban;
                    $fileduthao->can_bo_gop_y = $request->id_can_bo;
                    $fileduthao->save();
                }

            }
        }

        return redirect()->back()->with('success', 'Góp ý thành công !');
    }

    public function sugopy(Request $request)
    {
        $uploadPath = FILE_Y_KIEN_GOP_Y;
        $multiFiles = !empty($request['ten_file']) ? $request['ten_file'] : null;
        $txtFiles = !empty($request['txt_file']) ? $request['txt_file'] : null;

        if ($request->type == 1) {
            $gopy = CanBoPhongDuThao::where('id', $request->id_can_bo)->first();
            $gopy->y_kien = $request->y_kien_sua;
            $gopy->save();

            if ($multiFiles && count($multiFiles) > 0) {
                $filegopy = Filecanbogopyduthao::where(['can_bo_gop_y' => $request->id_can_bo, 'Du_thao_id' => $request->id_van_ban])->get();
                dd($filegopy);
                foreach ($filegopy as $filevb) {
                    $fileid = Filecanbogopyduthao::where('id', $filevb->id)->first();
                    $fileid->trang_thai = 0;
                    $fileid->save();
                }
                foreach ($multiFiles as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $fileduthao = new Filecanbogopyduthao();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = FILE_Y_KIEN_GOP_Y . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);
                    $fileduthao->duong_dan = $urlFile;
                    $fileduthao->duoi_file = $extFile;
                    $fileduthao->Du_thao_id = $request->id_van_ban;
                    $fileduthao->can_bo_gop_y = $request->id_can_bo;
                    $fileduthao->save();
                }
                UserLogs::saveUserLogs(' sửa góp ý dự thảo văn bản', $gopy);

            }
        } else {
            $gopy = CanBoPhongDuThaoKhac::where('id', $request->id_can_bo)->first();
            $gopy->y_kien = $request->y_kien_sua;
            $gopy->save();

            if ($multiFiles && count($multiFiles) > 0) {
                $filegopy = Filecanbogopyduthaongoai::where(['can_bo_gop_y' => $request->id_can_bo, 'Du_thao_id' => $request->id_van_ban])->get();
                foreach ($filegopy as $filevb) {
                    $fileid = Filecanbogopyduthaongoai::where('id', $filevb->id)->first();
                    $fileid->trang_thai = 0;
                    $fileid->save();
                }
                foreach ($multiFiles as $key => $getFile) {
                    $extFile = $getFile->extension();
                    $fileduthao = new Filecanbogopyduthaongoai();
                    $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();

                    $urlFile = FILE_Y_KIEN_GOP_Y . '/' . $fileName;
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true, true);
                    }
                    $getFile->move($uploadPath, $fileName);
                    $fileduthao->duong_dan = $urlFile;
                    $fileduthao->duoi_file = $extFile;
                    $fileduthao->Du_thao_id = $request->id_van_ban;
                    $fileduthao->can_bo_gop_y = $request->id_can_bo;
                    $fileduthao->save();
                }
                UserLogs::saveUserLogs(' sửa góp ý dự thảo văn bản', $gopy);

            }
        }
        return redirect()->back()->with('success', 'Cập nhật góp ý thành công !');
    }
    public function quytrinhtruyennhangopy($id)
    {

        $quatrinhtruyennhanphong = CanBoPhongDuThao::where('du_thao_vb_id', $id)->get();
        $quatrinhtruyennhankhac = CanBoPhongDuThaoKhac::where('du_thao_vb_id', $id)->get();
        return view('vanbandi::Du_thao_van_ban_di.Quytrinhtruyennhangopy', compact('quatrinhtruyennhanphong', 'quatrinhtruyennhankhac'));
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
        //
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
