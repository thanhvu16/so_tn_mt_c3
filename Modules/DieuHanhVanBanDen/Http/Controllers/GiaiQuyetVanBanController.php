<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBan;
use Modules\DieuHanhVanBanDen\Entities\GiaiQuyetVanBanFile;
use Modules\VanBanDen\Entities\VanBanDen;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Auth;

class GiaiQuyetVanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('dieuhanhvanbanden::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('dieuhanhvanbanden::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $currentUser = auth::user();

        $roles = [TRUONG_PHONG, CHANH_VAN_PHONG];
        $rolePhoPhong = [PHO_CHANH_VAN_PHONG, PHO_PHONG];

        $truongPhongDonVi = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->where('trang_thai',ACTIVE)
            ->whereNull('deleted_at')->first();

        $phoPhongDonVi = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($rolePhoPhong) {
                return $query->whereIn('name', $rolePhoPhong);
            })
            ->where('trang_thai',ACTIVE)
            ->whereNull('deleted_at')->get();

        $vanBanDenDonVi = VanBanDen::where('id', $data['van_ban_den_id'])->first();

        $chuyenNhanVanBanDonVi = DonViChuTri::where('van_ban_den_id', $vanBanDenDonVi->id)
            ->where('can_bo_nhan_id', auth::user()->id)
            ->whereNull('hoan_thanh')->first();

        if ($truongPhongDonVi && $truongPhongDonVi->id == $currentUser->id) {
            if ($vanBanDenDonVi) {
                $vanBanDenDonVi->trinh_tu_nhan_van_ban = VanBanDen::HOAN_THANH_VAN_BAN;
                $vanBanDenDonVi->hoan_thanh_dung_han = VanBanDen::checkHoanThanhVanBanDungHan($vanBanDenDonVi->han_xu_ly);
                $vanBanDenDonVi->ngay_hoan_thanh = date('Y-m-d H:i:s');
                $vanBanDenDonVi->save();

                // luu giai quyet vb
                $giaiQuyetVanBan = new GiaiQuyetVanBan();
                $giaiQuyetVanBan->van_ban_den_id = $vanBanDenDonVi->id;
                $giaiQuyetVanBan->noi_dung = $data['noi_dung'] ?? null;
                $giaiQuyetVanBan->user_id = auth::user()->id;
                $giaiQuyetVanBan->save();

                //upload file
                $txtFiles = !empty($data['txt_file']) ? $data['txt_file'] : null;
                $multiFiles = !empty($data['ten_file']) ? $data['ten_file'] : null;

                if ($multiFiles && count($multiFiles) > 0) {

                    GiaiQuyetVanBanFile::dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $giaiQuyetVanBan->id);
                }

                //xoa chuyen nhan vb
                if ($chuyenNhanVanBanDonVi) {
                    DonViChuTri::where('van_ban_den_id', $vanBanDenDonVi->id)
                        ->where('id', '>', $chuyenNhanVanBanDonVi->id)
                        ->where('don_vi_id', auth::user()->don_vi_id)
                        ->whereNull('hoan_thanh')->delete();
                }


                //update luu vet van ban
                XuLyVanBanDen::where('van_ban_den_id', $vanBanDenDonVi->id)
                    ->update(['hoan_thanh' => XuLyVanBanDen::HOAN_THANH_VB]);

                //update chuyen nhan vb don vi
                DonViChuTri::where('van_ban_den_id', $vanBanDenDonVi->id)
                    ->update(['hoan_thanh' => DonViChuTri::HOAN_THANH_VB]);

                return redirect()->back()->with('success', 'Hoành thành văn bản.');
            }
        } else {

            // luu giai quyet vb
            $canBoDuyetId = $chuyenNhanVanBanDonVi->can_bo_chuyen_id;

            if (in_array($currentUser->id , $phoPhongDonVi->pluck('id')->toArray())) {
                $canBoDuyetId = $truongPhongDonVi->id;
            }


            $giaiQuyetVanBan = new GiaiQuyetVanBan();
            $giaiQuyetVanBan->van_ban_den_id = $vanBanDenDonVi->id;
            $giaiQuyetVanBan->noi_dung = $data['noi_dung'] ?? null;
            $giaiQuyetVanBan->user_id = auth::user()->id;
            $giaiQuyetVanBan->can_bo_duyet_id = $canBoDuyetId;
            $giaiQuyetVanBan->save();

            //upload file
            $txtFiles = !empty($data['txt_file']) ? $data['txt_file'] : null;
            $multiFiles = !empty($data['ten_file']) ? $data['ten_file'] : null;

            if ($multiFiles && count($multiFiles) > 0) {

                GiaiQuyetVanBanFile::dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $giaiQuyetVanBan->id);
            }

            $vanBanDenDonVi->trinh_tu_nhan_van_ban = VanBanDen::HOAN_THANH_CHO_DUYET;
            $vanBanDenDonVi->save();

            return redirect()->route('van-ban-den-hoan-thanh.cho-duyet')->with('success', 'Hoành thành văn bản chờ duyệt.');
        }

        return redirect()->back('warning', 'Không tìm thấy dữ liêu');

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('dieuhanhvanbanden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('dieuhanhvanbanden::edit');
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
