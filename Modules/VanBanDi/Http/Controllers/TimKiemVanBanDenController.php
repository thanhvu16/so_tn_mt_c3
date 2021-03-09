<?php

namespace Modules\VanBanDi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\VanBanDen\Entities\VanBanDen;

class TimKiemVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {

        $soDen = $request->get('vb_so_den');
        $soKyHieu = $request->get('vb_so_ky_hieu');
        $trichYeu = $request->get('vb_trich_yeu');

        $danhSachVanBanDen = VanBanDen::where(function ($query) use ($soDen) {
                if (!empty($soDen)) {
                    return $query->where('so_den', $soDen);
                }
            })
            ->where(function ($query) use ($soKyHieu) {
                if (!empty($soKyHieu)) {
                    return $query->where('so_ky_hieu', 'LIKE', "%$soKyHieu%");
                }
            })
            ->where(function ($query) use ($trichYeu) {
                if (!empty($trichYeu)) {
                    return $query->where('trich_yeu', 'LIKE', "%$trichYeu%");
                }
            })->take(10)->get();

        $htmlResponse = view('vanbandi::van_ban_di.response_van_ban_den', compact('danhSachVanBanDen'))->render();

        return response()->json([
            'success' => true,
            'data' => $htmlResponse
        ]);
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
