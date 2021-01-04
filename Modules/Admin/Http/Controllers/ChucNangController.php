<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class ChucNangController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    function index(Request $request)
    {
        $name = $request->get('name') ?? null;
        $permissions = Permission::where(function ($query) use ($name) {
            if (!empty($name)) {
                return $query->where('name', 'LIKE', "%$name");
            }
        })->paginate(PER_PAGE);

        return view('admin::chuc-nang.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::chuc-nang.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $permission = Permission::create(['name' => $request->get('name')]);

        return redirect()->route('chuc-nang.index')->with('success','Thêm mới thành công .');

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $permission = Permission::findById($id);

        return view('admin::chuc-nang.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findById($id);

        $permission->name = $request->get('name');
        $permission->save();

        return redirect()->route('chuc-nang.index')->with('success','Cập nhật thành công .');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $permission = Permission::findById($id);
        $permission->delete();

        return redirect()->route('chuc-nang.index')->with('success','Xoá thành công .');


    }
}
