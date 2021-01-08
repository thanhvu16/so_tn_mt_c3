<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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

        return view('admin::Logs.index',compact('logs'));
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
        if ($request->ajax()) {
            $userLog = UserLogs::with('TenNguoiDung')->findOrFail($id);

            if ($userLog) {
                $userLog->decode_content = json_decode($userLog->content, true);
            }

            $returnHTML =  view('admin::Logs.show', compact('userLog'))->render();

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
