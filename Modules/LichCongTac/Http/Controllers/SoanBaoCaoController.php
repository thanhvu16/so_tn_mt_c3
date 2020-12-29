<?php

namespace Modules\LichCongTac\Http\Controllers;

use App\Models\NguoiDung;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonViFile;
use Auth, DB;

class SoanBaoCaoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('lichcongtac::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $danhSachDonViChutri = DonVi::whereNull('deleted_at')->get();

        return view('lichcongtac::soan-bao-cao.create', compact('danhSachDonViChutri'));
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

        $txtFiles = !empty($data['txt_file']) ? $data['txt_file'] : null;
        $multiFiles = !empty($data['ten_file']) ? $data['ten_file'] : null;

        try {
            DB::beginTransaction();

            $dataCongViecDonVi = [
                'lich_cong_tac_id' => $request->get('lich_cong_tac_id') ?? null,
                'noi_dung_cuoc_hop' => $request->get('noi_dung') ?? null,
                'user_id' => $currentUser->id
            ];

            $congViecDonVi = new CongViecDonVi();
            $congViecDonVi->fill($dataCongViecDonVi);
            $congViecDonVi->save();
            $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];

            if (count($noiDungDauViec) > 0) {
                foreach ($noiDungDauViec as $key => $dauViec) {
                    $canBoNhanId = null;
                    $donViId = null;
                    $donViPhoiHopId = null;
                    $hanXuLy = null;

                    if ($key == 0) {
                        $donVi = DonVi::where('id', $request->get('don_vi_chu_tri'))
                            ->whereNull('deleted_at')
                            ->first();

                        if ($donVi) {
                            $donViId = $donVi->id;

                            $nguoiDung = User::where('trang_thai', ACTIVE)
                                ->where('don_vi_id', $donVi->id)
                                ->whereHas('roles', function ($query) use ($roles) {
                                    return $query->whereIn('name', $roles);
                                })
                                ->orderBy('id', 'DESC')
                                ->whereNull('deleted_at')->first();

                            $canBoNhanId = $nguoiDung->id;
                        }
                        $donViPhoiHopId = $request->get('don_vi_phoi_hop');
                        $hanXuLy = $request->get('han_xu_ly');

                    } else {
                        $donVi = Donvi::where('id', $request->get('don_vi_chu_tri_' . $key))
                            ->whereNull('deleted_at')
                            ->first();

                        if ($donVi) {
                            $donViId = $donVi->id;

                            $nguoiDung = User::where('trang_thai', ACTIVE)
                                ->where('don_vi_id', $donVi->id)
                                ->whereHas('roles', function ($query) use ($roles) {
                                    return $query->whereIn('name', $roles);
                                })
                                ->orderBy('id', 'DESC')
                                ->whereNull('deleted_at')->first();

                            $canBoNhanId = $nguoiDung->id;
                        }

                        $donViPhoiHopId = $request->get('don_vi_phoi_hop_' . $key);
                        $hanXuLy = $request->get('han_xu_ly_' . $key);

                    }

                    //luu don vi chu tri
                    $chuyenNhanCongViecDonVi = new ChuyenNhanCongViecDonVi();
                    $chuyenNhanCongViecDonVi->cong_viec_don_vi_id = $congViecDonVi->id;
                    $chuyenNhanCongViecDonVi->noi_dung = $dauViec;
                    $chuyenNhanCongViecDonVi->can_bo_chuyen_id = $currentUser->id;
                    $chuyenNhanCongViecDonVi->can_bo_nhan_id = $canBoNhanId;
                    $chuyenNhanCongViecDonVi->don_vi_id = $donViId;
                    $chuyenNhanCongViecDonVi->han_xu_ly = $hanXuLy;
                    $chuyenNhanCongViecDonVi->save();

                    //luu don vi phoi hop
//                    if (!empty($donViPhoiHopId)) {
                    ChuyenNhanCongViecDonVi::saveDonViPhoiHop($donViPhoiHopId, $congViecDonVi->id, $dauViec);
//                    }
                }

                //save file
                if ($multiFiles && count($multiFiles) > 0) {

                    CongViecDonViFile::dinhKemFile($multiFiles, $txtFiles, $congViecDonVi->id);
                }

                DB::commit();

                if (empty($request->get('lich_cong_tac_id'))) {

                    return redirect()->route('cong-viec-don-vi.index')->with('success', 'Tạo công việc thành công.');
                }

                return redirect()->back()->with('success', 'Đã gửi thành công.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

        return redirect()->back()->with('warning', 'Không có dữ liệu');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('lichcongtac::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('lichcongtac::edit');
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
