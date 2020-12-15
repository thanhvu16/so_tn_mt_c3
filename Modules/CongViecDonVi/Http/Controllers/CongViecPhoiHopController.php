<?php

namespace Modules\CongViecDonVi\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CongViecDonVi\Entities\ChuyenNhanCongViecDonVi;
use auth;

class CongViecPhoiHopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $currentUser = auth::user();
        $chuyenNhanCongViecDonVi = ChuyenNhanCongViecDonVi::with('congViecDonVi')->where('can_bo_nhan_id', $currentUser->id)
            ->where('type', ChuyenNhanCongViecDonVi::TYPE_DV_PHOI_HOP)
            ->whereNull('chuyen_tiep')
            ->orWhere('chuyen_tiep', 0)
            ->whereNull('hoan_thanh')
            ->paginate(PER_PAGE);

        $roles = [PHO_PHONG, PHO_CHANH_VAN_PHONG];
        $danhSachPhoPhong = User::where('don_vi_id', $currentUser->don_vi_id)
            ->whereHas('roles', function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
            })
            ->wherenull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $danhSachChuyenVien = User::Role(PHO_PHONG)->where('don_vi_id', $currentUser->don_vi_id)->whereNull('deleted_at')
            ->orderBy('id', 'DESC')->get();

        $order = ($chuyenNhanCongViecDonVi->currentPage() - 1) * PER_PAGE + 1;

        if ($currentUser->hasRole(CHUYEN_VIEN) ) {

            return view('congviecdonvi::cong-viec-don-vi.chuyen-vien', compact('chuyenNhanCongViecDonVi',
                'danhSachPhoPhong', 'danhSachChuyenVien', 'order'));
        }

        return view('congviecdonvi::cong-viec-don-vi.index', compact('chuyenNhanCongViecDonVi',
            'danhSachPhoPhong', 'danhSachChuyenVien', 'order'));

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
        //
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
