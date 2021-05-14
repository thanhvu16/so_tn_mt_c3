<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\UserLogs;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use File;

class UserLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $name = $request->get('name') ?? null;
        $action = $request->get('action') ?? null;
        $date = $request->get('date') ?? null;

        $userId = null;

        if (!empty($name)) {
            $user = User::where('ho_ten', 'LIKE', "%$name%")
                ->select('id')->first();
            $userId = $user->id ?? null;
        }

        $logs = UserLogs::with('TenNguoiDung')
            ->where(function ($query) use ($userId) {
                if (!empty($userId)) {
                    return $query->where('user_id', $userId);
                }
            })
            ->where(function ($query) use ($action) {
                if (!empty($action)) {
                    return $query->where('action', "LIKE", "%$action%");
                }
            })
            ->where(function ($query) use ($date) {
                if (!empty($date)) {
                    return $query->where('created_at', $date);
                }
            })
            ->whereYear('created_at', date("Y"))
            ->orderBy('id', 'DESC')->paginate(PER_PAGE);


        $checkFileExists = file_exists(storage_path('logs/user_action_'.date('m_Y').'.log'));

        $logCollection = [];
        $newLogCollections = [];
        // Loop through an array, show HTML source as HTML source; and line numbers too.
        if ($checkFileExists) {
            $filePath = file(storage_path('logs/user_action_'.date('m_Y').'.log'));
            foreach (array_reverse($filePath) as $line_num => $line) {
                $explodeData = explode('.INFO: ', $line);
                $logCollection[] = $explodeData[1];
            }

            if ($logCollection) {
                foreach ($logCollection as $log) {
                    $convertLog = explode('<br>', $log);
                    $newLogCollections[] = [
                        'user' => $convertLog[0],
                        'action' => $convertLog[1],
                        'date' => $convertLog[2],
                        'content' => $convertLog[3],
                    ];
                }
            }
        }

        return view('admin::Logs.index1',compact('logs', 'newLogCollections'));
    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
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
     * @param Request $request
     * @return Renderable
     */
    public function show($id, Request $request)
    {
        $content = $request->get('content');

        if ($request->ajax()) {
            $decode_content = json_decode($content, true);

            $returnHTML =  view('admin::Logs.show', compact('decode_content'))->render();

            return response()->json(array('success' => true, 'html' => $returnHTML));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
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
