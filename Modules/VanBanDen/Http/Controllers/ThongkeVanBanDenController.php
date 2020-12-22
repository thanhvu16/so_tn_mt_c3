<?php

namespace Modules\VanBanDen\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth, Excel, PDF, DB;
use App\Exports\VanbandenExport;
use Modules\Admin\Entities\SoVanBan;
use Modules\VanBanDen\Entities\VanBanDen;

class ThongkeVanBanDenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request  $request)
    {
        $id = (int)$request->get('id');
        $sovanban = SoVanBan::whereNull('deleted_at')->whereIn('loai_so',[1,3])->get();
        $timloaiso = $request->get('so_van_ban');
        $vanbanden=null;
        if ($id) {
            $vanbanden = VanBanDen::where('id', $id)->first();
        }
        $ds_vanBanDen =  VanBanDen::
        where([
            'don_vi_id' => auth::user()->don_vi_id,
            'type' => 2])
            ->where('so_van_ban_id', '!=', 100)->whereNull('deleted_at')
            ->where(function ($query) use ($timloaiso) {
                if (!empty($timloaiso)) {
                    return $query->where('so_van_ban_id',$timloaiso);
                }
            })->orderBy('created_at', 'desc')->paginate(PER_PAGE);
        $totalRecord = $ds_vanBanDen->count();

        if ($request->get('type') == 'pdf') {
            $fileName = 'in_so_van_ban_den'.date('d_m_Y') .'.pdf';

            $pdf = PDF::loadView('vanbanden::thong_ke.view_index',compact('vanbanden','ds_vanBanDen' ) );

            return $pdf->download($fileName)->header('Content-Type','application/pdf');
        }

        //export word
        if ($request->get('type') == 'word') {
            $fileName = 'in_so_van_ban_di'.date('d_m_Y') .'.doc';

            $headers = array(
                "Content-type"=>"text/html",
                "Content-Disposition"=>"attachment;Filename=".$fileName
            );

            $content = view('vanbanden::thong_ke.view_index',compact('vanbanden','ds_vanBanDen' ) );

            return \Response::make($content,200, $headers);

        }
        if ($request->get('type') == 'excel') {
            $fileName = 'in_so_van_ban_den'.date('d_m_Y') .'.xlsx';
            return Excel::download(new vanbandenExport($ds_vanBanDen,$totalRecord),
                $fileName);
        }



        if ($request->ajax()) {

            $html = view('vanbanden::thong_ke.view_index',compact('vanbanden','ds_vanbanden' ) )->render();;
            return response()->json([
                'html' => $html,
            ]);
        }


        return view('vanbanden::thong_ke.index', compact('vanbanden','ds_vanBanDen','sovanban' ));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('vanbanden::create');
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
        return view('vanbanden::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('vanbanden::edit');
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
