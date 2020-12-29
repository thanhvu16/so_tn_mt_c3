<?php

namespace Modules\CongViecDonVi\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use auth;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use Modules\CongViecDonVi\Entities\CongViecDonViPhoiHop;
use Modules\CongViecDonVi\Entities\DonViPhoiHopGiaiQuyet;
use Modules\CongViecDonVi\Entities\DonViPhoiHopGiaiQuyetFile;

class CongViecDonViPhoiHopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $currentUser = auth::user();
        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')->where('can_bo_nhan_id',
            $currentUser->id)
            ->where('type', ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP)
            ->whereNull('chuyen_tiep')
            ->whereNull('hoan_thanh')
            ->paginate(PER_PAGE);

        $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG];
        $danhSachPhoPhong = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->wherenull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $danhSachChuyenVien = User::Role(CHUYEN_VIEN)->where('don_vi_id', $currentUser->don_vi_id)->whereNull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $order = ($chuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        $typeDonViPhoiHop = ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP;

        if ($currentUser->hasRole(CHUYEN_VIEN)) {

            return view('congviecdonvi::cong-viec-don-vi.chuyen-vien', compact('chuyenNhanCongViecDonVi',
                'danhSachPhoPhong', 'danhSachChuyenVien', 'order', 'typeDonViPhoiHop'));
        }

        return view('congviecdonvi::cong-viec-don-vi.index', compact('chuyenNhanCongViecDonVi',
            'danhSachPhoPhong', 'danhSachChuyenVien', 'order', 'typeDonViPhoiHop'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('congviecdonvi::create');
    }
    public function dangXuLy()
    {
        $currentUser = auth::user();
        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')
            ->where('can_bo_nhan_id', $currentUser->id)
            ->where('type', ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP)
            ->where('chuyen_tiep', ChuyenNhanCongViecDonVi::CHUYEN_TIEP)
            ->whereNull('hoan_thanh')
            ->paginate(PER_PAGE);

        $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG];
        $danhSachPhoPhong = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->wherenull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $danhSachChuyenVien = User::Role(CHUYEN_VIEN)->where('don_vi_id', $currentUser->don_vi_id)->whereNull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $order = ($chuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        $typeDonViPhoiHop = ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP;

        return view('congviecdonvi::cong-viec-don-vi.dang-xu-ly', compact('chuyenNhanCongViecDonVi',
            'danhSachPhoPhong', 'danhSachChuyenVien', 'order', 'typeDonViPhoiHop'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth::user()->id;
        $data['don_vi_id'] = auth::user()->don_vi_id;
        $type = $request->get('type');

        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::where('id',
            $data['chuyen_nhan_cong_viec_don_vi_id'])->first();

        $data['cong_viec_don_vi_id'] = $chuyenNhanCongViecDonVi->cong_viec_don_vi_id ?? 0;

        if (!empty($type)) {
            $data['status'] = DonViPhoiHopGiaiQuyet::GIAI_QUYET_CHUYEN_VIEN_PHOI_HOP;
        }

        //tao giai quyet vb don vi
        $phoiHopGiaiQuyet = new DonViPhoiHopGiaiQuyet();
        $phoiHopGiaiQuyet->fill($data);
        $phoiHopGiaiQuyet->save();

        if (empty($type)) {
            //update chuyen nhan van ban don vi phoi hop
            $chuyenNhanCongViecDonVi->chuyen_tiep = ChuyenNhanCongViecDonVi::GIAI_QUYET;
            $chuyenNhanCongViecDonVi->save();

            ChuyenNhanCongViecDonVi::where('cong_viec_don_vi_id', $chuyenNhanCongViecDonVi->cong_viec_don_vi_id)
                ->where('type', ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP)
                ->whereNull('hoan_thanh')
                ->where('id', '>', $chuyenNhanCongViecDonVi->id)
                ->delete();

            //update chuyen nhan cv don vi
            ChuyenNhanCongViecDonVi::where('don_vi_id', $chuyenNhanCongViecDonVi->don_vi_id)
                ->where('type', ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP)
                ->where('cong_viec_don_vi_id', $chuyenNhanCongViecDonVi->cong_viec_don_vi_id)
                ->update(['hoan_thanh' => ChuyenNhanCongViecDonVi::HOAN_THANH_CONG_VIEC]);
        }

        if (!empty($type)) {
            $chuyenVienPhoiHop = CongViecDonViPhoiHop::where([
                'cong_viec_don_vi_id' => $chuyenNhanCongViecDonVi->cong_viec_don_vi_id,
                'can_bo_nhan_id' => auth::user()->id,
            ])
                ->whereNull('type')
                ->first();

            if ($chuyenVienPhoiHop) {
                $chuyenVienPhoiHop->status = CongViecDonViPhoiHop::STATUS_GIAI_QUYET;
                $chuyenVienPhoiHop->save();
            }
        }

        //upload file
        $txtFiles = !empty($data['txt_file']) ? $data['txt_file'] : null;
        $multiFiles = !empty($data['ten_file']) ? $data['ten_file'] : null;

        if ($multiFiles && count($multiFiles) > 0) {

            DonViPhoiHopGiaiQuyetFile::dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $phoiHopGiaiQuyet->id);
        }

        return redirect()->back()->with('success', 'Đã phối hợp giải quyết.');
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
    public function daXuLy()
    {
        $currentUser = auth::user();
        $danhSachChuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')
            ->where('can_bo_nhan_id', $currentUser->id)
            ->where('type', ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP)
            ->where('hoan_thanh', ChuyenNhanCongViecDonVi::HOAN_THANH_CONG_VIEC)
            ->paginate(PER_PAGE);

        $order = ($danhSachChuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        return view('congviecdonvi::cong-viec-don-vi.hoan-thanh.hoan-thanh-phoi-hop', compact('danhSachChuyenNhanCongViecDonVi', 'order'));
    }
}
