<?php

namespace Modules\CongViecDonVi\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB,auth,File;
use Modules\Admin\Entities\DonVi;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonViFile;
use Modules\CongViecDonVi\Entities\CongViecDonViPhoiHop;

class TaoCongViecDonViController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('congviecdonvi::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('congviecdonvi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $noiDungDauViec = $request->get('noi_dung_dau_viec');
        $currentUser = auth::user();

        $textChuyenVienPhoiHop = null;

        if (!empty($request->get('chuyen_vien_phoi_hop_id'))) {
            $chuyenVienPhoiHop = User::whereIn('id', $request->get('chuyen_vien_phoi_hop_id'))->get();

            $textChuyenVienPhoiHop = implode(', ', $chuyenVienPhoiHop->pluck('ho_ten')->toArray());
        }

        try {
            DB::beginTransaction();

            $txtFiles = !empty($data['txt_file']) ? $data['txt_file'] : null;
            $multiFiles = !empty($data['ten_file']) ? $data['ten_file'] : null;

            $dataCongViecDonVi = [
                'lich_cong_tac_id' => $request->get('lich_cong_tac_id') ?? null,
                'noi_dung_cuoc_hop' => $request->get('noi_dung') ?? null,
                'noi_dung_dau_viec' => $request->get('noi_dung') ?? null,
                'user_id' => $currentUser->id
            ];

            $congViecDonVi = new CongViecDonVi();
            $congViecDonVi->fill($dataCongViecDonVi);
            $congViecDonVi->save();

            if (count($noiDungDauViec) > 0) {
                foreach ($noiDungDauViec as $key => $dauViec) {
                    $canBoNhanId = null;
                    $donViId = null;
                    $donViPhoiHopId = null;
                    $hanXuLy = null;

                    $donVi = Donvi::where('id', $request->get('don_vi_chu_tri'))
//                        ->where('trang_thai', DonVi::TRANG_THAI_HOAT_DONG)
                        ->whereNull('deleted_at')
                        ->first();
                    if ($donVi) {
                        $donViId = $donVi->id;
                        $nguoiDung = User::where('don_vi_id', $donVi->id)
//                            ->where('trang_thai', User::TRANG_THAI_HOAT_DONG)
//                            ->where('vai_tro', CAP_TRUONG)
                            ->first();

                        $canBoNhanId = $nguoiDung->id;
                    }
                    $donViPhoiHopId = $request->get('don_vi_phoi_hop');
                    $hanXuLy = $request->get('han_xu_ly');

                    //save file
                    //upload file
                    if ($multiFiles && count($multiFiles) > 0) {

                        CongViecDonViFile::dinhKemFile($multiFiles, $txtFiles, $congViecDonVi->id);
                    }

                    //luu don vi chu tri
                    $chuyenNhanCongViecDonVi = new ChuyenNhanCongViecDonVi();
                    $chuyenNhanCongViecDonVi->cong_viec_don_vi_id = $congViecDonVi->id;
                    $chuyenNhanCongViecDonVi->noi_dung = $dauViec;
                    $chuyenNhanCongViecDonVi->can_bo_chuyen_id = $currentUser->id;
                    $chuyenNhanCongViecDonVi->can_bo_nhan_id = $canBoNhanId;
                    $chuyenNhanCongViecDonVi->don_vi_id = $donViId;
                    $chuyenNhanCongViecDonVi->han_xu_ly = $hanXuLy;
                    $chuyenNhanCongViecDonVi->chuyen_tiep = ChuyenNhanCongViecDonVi::CHUYEN_TIEP;
                    $chuyenNhanCongViecDonVi->save();

                    //luu don vi phoi hop
                    if (!empty($donViPhoiHopId)) {
                        ChuyenNhanCongViecDonVi::saveDonViPhoiHop($donViPhoiHopId, $congViecDonVi->id, $dauViec);
                    }

                    //luu pho phong
                    if (!empty($request->get('pho_phong_id'))) {

                        $phoPhong = User::where('id', $request->get('pho_phong_id'))->first();
                        $txtphoPhong = 'Chuyển phó phòng '.$phoPhong->ho_ten.' chỉ đạo';

                        $dataChuyenNhanDonVi = [
                            'cong_viec_don_vi_id' => $chuyenNhanCongViecDonVi->cong_viec_don_vi_id ?? null,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $request->get('pho_phong_id'),
                            'noi_dung' => $chuyenNhanCongViecDonVi->noi_dung ?? null,
                            'noi_dung_chuyen' => $txtphoPhong,
                            'don_vi_id' => $currentUser->don_vi_id,
                            'han_xu_ly' => $chuyenNhanCongViecDonVi->han_xu_ly,
                            'parent_id' => $chuyenNhanCongViecDonVi ? $chuyenNhanCongViecDonVi->id : null,
                        ];

                        //save pho phong
                        $chuyenNhanCongViecDonViPhoPhong = new ChuyenNhanCongViecDonVi();
                        $chuyenNhanCongViecDonViPhoPhong->fill($dataChuyenNhanDonVi);
                        $chuyenNhanCongViecDonViPhoPhong->save();
                    }

                    // save chuyen vien thuc hien
                    if (!empty($request->get('chuyen_vien_id'))) {
                        $chuyenVien = User::where('id', $request->get('chuyen_vien_id'))->first();
                        $txtChuyenVien = 'Chuyển chuyên viên '.$chuyenVien->ho_ten .' giải quyết.';

                        if (!is_null($textChuyenVienPhoiHop)) {
                            $txtChuyenVien = 'Chuyển chuyên viên '.$chuyenVien->ho_ten.' giải quyết. '. $textChuyenVienPhoiHop .' phối hợp';
                        }

                        $dataChuyenNhanDonViChuyenVien = [
                            'cong_viec_don_vi_id' => $chuyenNhanCongViecDonVi->cong_viec_don_vi_id ?? null,
                            'can_bo_chuyen_id' => $currentUser->id,
                            'can_bo_nhan_id' => $request->get('chuyen_vien_id'),
                            'noi_dung' => $chuyenNhanCongViecDonVi->noi_dung ?? null,
                            'noi_dung_chuyen' => $txtChuyenVien,
                            'don_vi_id' => $currentUser->don_vi_id,
                            'han_xu_ly' => $chuyenNhanCongViecDonVi->han_xu_ly,
                            'parent_id' => $chuyenNhanCongViecDonVi ? $chuyenNhanCongViecDonVi->id : null
                        ];

                        $chuyenNhanCongViecDonViChuyenVien = new ChuyenNhanCongViecDonVi();
                        $chuyenNhanCongViecDonViChuyenVien->fill($dataChuyenNhanDonViChuyenVien);
                        $chuyenNhanCongViecDonViChuyenVien->save();
                    }

                    // save chuyen vien phoi hop
                    if (!empty($request->get('chuyen_vien_phoi_hop_id'))) {
                        //save chuyen vien phoi hop
                        CongViecDonViPhoiHop::savechuyenVienPhoiHop($request->get('chuyen_vien_phoi_hop_id'),
                            $chuyenNhanCongViecDonVi->cong_viec_don_vi_id, $chuyenNhanCongViecDonVi->id, $currentUser->don_vi_id);
                    }

                }
            }

            DB::commit();

            return redirect()->route('cong-viec-don-vi.dang-xu-ly')->with('success', 'Tạo công việc thành công.');

        } catch (\Exception $e) {
            DB::rollback();
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
        return view('congviecdonvi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('congviecdonvi::edit');
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
