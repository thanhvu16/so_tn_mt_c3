<?php

namespace Modules\LichCongTac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LichCongTac;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use DB;

class ThongKeCuocHopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $ngaybatdau= $request->get('tu_ngay');
        $ngayketthuc= $request->get('den_ngay');
        $chu_tri = LichCongTac::select('lanh_dao_id')->distinct('lanh_dao_id')
            ->where(function ($query) use ($ngaybatdau, $ngayketthuc) {
                if ($ngaybatdau != '' && $ngayketthuc != '' && $ngaybatdau <= $ngayketthuc) {

                    return $query->where('ngay', '>=', $ngaybatdau)
                        ->where('ngay', '<=', $ngayketthuc);
                }
                if ($ngayketthuc == '' && $ngaybatdau != '') {
                    return $query->where('ngay', $ngaybatdau);

                }
                if ($ngaybatdau == '' && $ngayketthuc != '') {
                    return $query->where('ngay', $ngayketthuc);

                }
            })
            ->paginate(PER_PAGE);


        return view('lichcongtac::thong-ke.index',compact('chu_tri'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('lichcongtac::create');
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
