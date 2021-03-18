<?php

namespace Modules\CongViecDonVi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\GiaiQuyetCongViecDonVi;
use Modules\CongViecDonVi\Entities\GiaiQuyetCongViecDonViFile;
use auth;

class GiaiQuyetCongViecController extends Controller
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
        $currentUser = auth::user();
        $data = $request->all();

        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::where('id', $data['chuyen_nhan_cong_viec_don_vi_id'])
            ->first();


        if ($chuyenNhanCongViecDonVi) {
            $chuyenNhanCongViecDonVi->chuyen_tiep = ChuyenNhanCongViecDonVi::GIAI_QUYET;
            $chuyenNhanCongViecDonVi->save();

            $giaiQuyetCongViecDonVi = new GiaiQuyetCongViecDonVi();
            $giaiQuyetCongViecDonVi->chuyen_nhan_cong_viec_don_vi_id = $chuyenNhanCongViecDonVi->id;
            $giaiQuyetCongViecDonVi->cong_viec_don_vi_id = $chuyenNhanCongViecDonVi->cong_viec_don_vi_id;
            $giaiQuyetCongViecDonVi->noi_dung = $data['noi_dung'];
            $giaiQuyetCongViecDonVi->lanh_dao_duyet_id = $data['lanh_dao_duyet_id'];
            $giaiQuyetCongViecDonVi->don_vi_id = $currentUser->don_vi_id;
            $giaiQuyetCongViecDonVi->user_id = $currentUser->id;
            $giaiQuyetCongViecDonVi->save();

            //upload file
            $txtFiles = !empty($data['txt_file']) ? $data['txt_file'] : null;
            $multiFiles = !empty($data['ten_file']) ? $data['ten_file'] : null;

            if ($multiFiles && count($multiFiles) > 0) {

                GiaiQuyetCongViecDonViFile::dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $giaiQuyetCongViecDonVi->id);
            }

            //xoa chuyen nhan cv
            ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $chuyenNhanCongViecDonVi->cong_viec_don_vi_id)
                ->whereNull('type')
                ->whereNull('hoan_thanh')
                ->where('id', '>', $chuyenNhanCongViecDonVi->id)
                ->delete();

            return redirect()->route('cong-viec-don-vi.da-xu-ly')->with('success', 'Hoành thành công việc chờ duyệt.');
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

        $giaiQuyetCongViecDonVi = GiaiQuyetCongViecDonVi::findOrFail($id);
        $giaiQuyetCongViecDonVi->noi_dung = $request->get('noi_dung');
        $giaiQuyetCongViecDonVi->lanh_dao_duyet_id = $request->get('lanh_dao_duyet_id');
        $giaiQuyetCongViecDonVi->save();

        //upload file
        $txtFiles = !empty($request->get('txt_file')) ? $request->get('txt_file') : null;
        $multiFiles = !empty($request->ten_file) ? $request->ten_file : null;

        if ($multiFiles && count($multiFiles) > 0) {
            GiaiQuyetCongViecDonViFile::dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $giaiQuyetCongViecDonVi->id);
        }

        return redirect()->route('cong-viec-don-vi.da-xu-ly')->with('success', 'Cập nhật thành công.');
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

    public function removeFileGiaiQuyet($id)
    {
        $giaiQuyetFile = GiaiQuyetCongViecDonViFile::findOrFail($id);
        $giaiQuyetFile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xoá file thành công.'
        ]);
    }
}
