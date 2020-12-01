<?php

namespace Modules\DieuHanhVanBanDen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use Modules\DieuHanhVanBanDen\Entities\LogXuLyVanBanDen;
use Modules\DieuHanhVanBanDen\Entities\VanBanTraLai;
use Modules\DieuHanhVanBanDen\Entities\XuLyVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;

class VanBanTraLaiController extends Controller
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
        $currentUser = auth::user();
        $active = $request->get('active');
        $vanBanDenId = $request->get('van_ban_den_id');
        $noiDung = $request->get('noi_dung');
        $type = $request->get('type') ?? null;

        $vanBanDen = VanBanDen::findOrFail($vanBanDenId);

        if ($vanBanDen) {
            $xuLyVanBanDen = XuLyVanBanDen::where('can_bo_nhan_id', $currentUser->id)
                ->where('van_ban_den_id', $vanBanDenId)
                ->whereNull('status')
                ->first();

            // check van ban tra lai
            $checkVanBanTraLai = VanBanTraLai::where('van_ban_den_id', $vanBanDenId)
                ->where('can_bo_nhan_id', $currentUser->id)->whereNull('status')->first();
            if ($checkVanBanTraLai) {
                $checkVanBanTraLai->status = VanBanTraLai::STATUS_GIAI_QUYET;
                $checkVanBanTraLai->save();
            }

            $canBoNhan = $xuLyVanBanDen->can_bo_chuyen_id;

            $dataVanBanTraLai = [
                'van_ban_den_id' => $vanBanDenId,
                'can_bo_chuyen_id' => $currentUser->id,
                'can_bo_nhan_id' => $canBoNhan,
                'noi_dung' => $noiDung,
                'type' => 1
            ];

            switch ($active) {
                case 2:
                    //PCT tra lai vb
                    if ($type == 2) {
                        //tra lai chu tich
                        $vanBanDen->trinh_tu_nhan_van_ban = VanBanDen::CHU_TICH_NHAN_VB;
                        $vanBanDen->save();
                    } else {
                        // Tra lai tham muu
                        $xuLyVanBanDen = XuLyVanBanDen::where('van_ban_den_id', $vanBanDenId)
                            ->whereNull('status')
                            ->orderBy('created_at', 'ASC')->first();

                        $canBoNhan = $xuLyVanBanDen->can_bo_chuyen_id;
                        $dataVanBanTraLai['can_bo_nhan_id'] = $canBoNhan;

                        $vanBanDen->trinh_tu_nhan_van_ban = null;
                        $vanBanDen->save();
                    }
                    break;

                default:
                    $vanBanDen->trinh_tu_nhan_van_ban = null;
                    $vanBanDen->save();

                    break;

            }

            // luu van ban tra lai
            $vanBanTraLai = new VanBanTraLai();
            $vanBanTraLai->fill($dataVanBanTraLai);
            $vanBanTraLai->save();

            $dataXuLyVanBanDen = [
                'van_ban_den_id' => $vanBanDenId,
                'can_bo_chuyen_id' => $currentUser->id,
                'can_bo_nhan_id' => $xuLyVanBanDen->can_bo_chuyen_id,
                'noi_dung' => $noiDung,
                'tom_tat' => $xuLyVanBanDen->tom_tat ?? null,
                'user_id' => $currentUser->id,
                'status' => XuLyVanBanDen::STATUS_TRA_LAI
            ];

            //luu trinh tu xu ly van ban den
            $xuLyVanBanDen = new XuLyVanBanDen();
            $xuLyVanBanDen->fill($dataXuLyVanBanDen);
            $xuLyVanBanDen->save();

            // luu log xu ly van ban den
            $luuVetVanBanDen = new LogXuLyVanBanDen();
            $luuVetVanBanDen->fill($dataXuLyVanBanDen);
            $luuVetVanBanDen->save();

            return redirect()->back()->with('success', 'Đã  gửi trả lại văn bản');
        }

        return redirect()->back()->with('warning', 'Không tìm thấy dữ liệu');

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
