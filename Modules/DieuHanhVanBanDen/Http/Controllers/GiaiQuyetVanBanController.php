<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVienPhoiHop;
use Modules\DieuHanhVanBanDen\Entities\DonViPhoiHop;
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
        $donVi = $currentUser->donVi;
        $roles = [TRUONG_PHONG, CHANH_VAN_PHONG, TRUONG_BAN];
        $rolePhoPhong = [PHO_CHANH_VAN_PHONG, PHO_PHONG, PHO_TRUONG_BAN];

        if ($donVi->id == 10084 || $donVi->id == 10085) {
            $truongPhongDonVi = User::where('don_vi_id', $currentUser->don_vi_id)
                ->whereHas('roles', function ($query) use ($roles) {
                    return $query->whereIn('name', $roles);
                })
                ->where('trang_thai', ACTIVE)
                ->orderBy('thu_tu_tp', 'desc')
                ->whereNull('deleted_at')->first();
        } else {
            $truongPhongDonVi = User::where('don_vi_id', $currentUser->don_vi_id)
                ->whereHas('roles', function ($query) use ($roles) {
                    return $query->whereIn('name', $roles);
                })
                ->where('trang_thai', ACTIVE)
                ->whereNull('deleted_at')->first();
        }


        $phoPhongDonVi = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($rolePhoPhong) {
                return $query->whereIn('name', $rolePhoPhong);
            })
            ->where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->get();

        $vanBanDenDonVi = VanBanDen::where('id', $data['van_ban_den_id'])->first();

        $chuyenNhanVanBanDonVi = DonViChuTri::where('van_ban_den_id', $vanBanDenDonVi->id)
            ->where('can_bo_nhan_id', auth::user()->id)
            ->whereNull('hoan_thanh')->first();

        // hoan thanh cong viec cap lanh dao
        if ($currentUser->hasRole([CHU_TICH, PHO_CHU_TICH])) {
            if ($donVi->cap_xa == DonVi::CAP_XA) {
                $this->updateVanBanHoanThanh($vanBanDenDonVi, $chuyenNhanVanBanDonVi, $currentUser, $donVi, $data);

                return redirect()->route('van-ban-lanh-dao-xu-ly.index')->with('success', 'Ho??nh th??nh v??n b???n.');
            } else {

                $chuyenNhanVanBanDonVi = XuLyVanBanDen::where('can_bo_nhan_id', $currentUser->id)
                    ->where('van_ban_den_id', $vanBanDenDonVi->id)
                    ->whereNull('status')
                    ->whereNull('hoan_thanh')
                    ->select('id', 'van_ban_den_id')
                    ->first();

                $this->updateVanBanHoanThanh($vanBanDenDonVi, $chuyenNhanVanBanDonVi, $currentUser, $donVi, $data);

                return redirect()->route('van-ban-lanh-dao-xu-ly.index')->with('success', 'Ho??nh th??nh v??n b???n.');
            }

        }

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
                $giaiQuyetVanBan->can_bo_duyet_id = auth::user()->id;
                $giaiQuyetVanBan->status = GiaiQuyetVanBan::STATUS_DA_DUYET;
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
                return redirect()->back()->with('success', 'Ho??nh th??nh v??n b???n.');
            }
        } else {
            // luu giai quyet vb
            $canBoDuyetId = $chuyenNhanVanBanDonVi->can_bo_chuyen_id;


            if (in_array($currentUser->id, $phoPhongDonVi->pluck('id')->toArray())) {
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

            if ($chuyenNhanVanBanDonVi) {
                DonViChuTri::where('van_ban_den_id', $vanBanDenDonVi->id)
                    ->where('id', '>', $chuyenNhanVanBanDonVi->id)
                    ->where('don_vi_id', auth::user()->don_vi_id)
                    ->whereNull('hoan_thanh')->delete();
            }

            return redirect()->route('van-ban-den-don-vi.index')->with('success', 'Ho??nh th??nh v??n b???n ch??? duy???t.');
        }

        return redirect()->back('warning', 'Kh??ng t??m th???y d??? li??u');

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

    public function updateVanBanHoanThanh($vanBanDenDonVi, $chuyenNhanVanBanDonVi, $currentUser, $donVi, $data)
    {
        if ($vanBanDenDonVi) {
            $vanBanDenDonVi->trinh_tu_nhan_van_ban = VanBanDen::HOAN_THANH_VAN_BAN;
            $vanBanDenDonVi->hoan_thanh_dung_han = VanBanDen::checkHoanThanhVanBanDungHan($vanBanDenDonVi->han_xu_ly);
            $vanBanDenDonVi->ngay_hoan_thanh = date('Y-m-d H:i:s');
            $vanBanDenDonVi->save();

            // luu giai quyet vb
            $dataGiaiQuyetVanBan = [
                'van_ban_den_id' => $vanBanDenDonVi->id,
                'noi_dung' => $data['noi_dung'] ?? null,
                'user_id' => $currentUser->id,
                'can_bo_duyet_id' => $currentUser->id,
                'status' => GiaiQuyetVanBan::STATUS_DA_DUYET
            ];


            $giaiQuyetVanBan = new GiaiQuyetVanBan();
            $giaiQuyetVanBan->fill($dataGiaiQuyetVanBan);
            $giaiQuyetVanBan->save();

            //upload file
            $txtFiles = !empty($data['txt_file']) ? $data['txt_file'] : null;
            $multiFiles = !empty($data['ten_file']) ? $data['ten_file'] : null;

            if ($multiFiles && count($multiFiles) > 0) {

                GiaiQuyetVanBanFile::dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $giaiQuyetVanBan->id);
            }

            //xoa chuyen nhan vb
            if ($donVi->cap_xa == DonVi::CAP_XA) {
                if ($chuyenNhanVanBanDonVi) {
                    DonViChuTri::where('van_ban_den_id', $vanBanDenDonVi->id)
                        ->where('id', '>', $chuyenNhanVanBanDonVi->id)
                        ->where('don_vi_id', $currentUser->don_vi_id)
                        ->whereNull('hoan_thanh')->delete();

                    //xoa don vi chu tri co parent_don_vi_id
                    DonViChuTri::where('van_ban_den_id', $vanBanDenDonVi->id)
                        ->where('id', '>', $chuyenNhanVanBanDonVi->id)
                        ->where('parent_don_vi_id', $currentUser->don_vi_id)
                        ->whereNull('hoan_thanh')->delete();

                    // update don vi phoi hop
                    DonViPhoiHop::where('van_ban_den_id', $vanBanDenDonVi->id)->where('don_vi_id', $currentUser->don_vi_id)
                        ->whereNull('hoan_thanh')
                        ->delete();

                    DonViPhoiHop::where('van_ban_den_id', $vanBanDenDonVi->id)
                        ->where('parent_don_vi_id', $currentUser->don_vi_id)
                        ->whereNull('hoan_thanh')
                        ->delete();
                }
            } else {
                if ($chuyenNhanVanBanDonVi) {
                    XuLyVanBanDen::where('van_ban_den_id', $vanBanDenDonVi->id)
                        ->whereNull('status')
                        ->where('id', '>', $chuyenNhanVanBanDonVi->id)
                        ->delete();

                    DonViPhoiHop::where('van_ban_den_id', $vanBanDenDonVi->id)
                        ->whereNull('hoan_thanh')
                        ->delete();

                    ChuyenVienPhoiHop::where('van_ban_den_id', $vanBanDenDonVi->id)
                        ->whereNull('status')
                        ->delete();
                }
            }

            //update luu vet van ban
            XuLyVanBanDen::where('van_ban_den_id', $vanBanDenDonVi->id)
                ->update(['hoan_thanh' => XuLyVanBanDen::HOAN_THANH_VB]);

            //update chuyen nhan vb don vi
            DonViChuTri::where('van_ban_den_id', $vanBanDenDonVi->id)
                ->update(['hoan_thanh' => DonViChuTri::HOAN_THANH_VB]);

        }
    }

}
