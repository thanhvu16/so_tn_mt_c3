<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;

class VaiTroController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $name = $request->get('name') ?? null;
        $roles = Role::where(function ($query) use ($name) {
            if (!empty($name)) {
                return $query->where('name', 'LIKE', "%$name");
            }
        })->paginate(PER_PAGE);

        return view('admin::vai-tro.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $permissions = Permission::orderBy('id', 'ASC')->get();

        return view('admin::vai-tro.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required|unique:roles,name'
            ],
            [
                'name.required' => 'Vui lòng nhập quyền .',
                'name.unique' => 'Quyền này đã tồn tại vui lòng nhập quyền khác',
            ]);

        $permissions = $request->get('permission');

        $role = Role::create(['name' => $request->get('name')]);

        if (!empty($permissions)  && count($permissions) > 0) {
            $role->syncPermissions($permissions);
        }

        return redirect()->route('vai-tro.index')->with('success', 'thêm quyền thành công.');
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
        $role = Role::findOrFail($id);
        $arrPermisson = $role->permissions->pluck('id')->toArray();
        $permissions = Permission::orderBy('id', 'ASC')->get();

        return view('admin::vai-tro.edit', compact('role', 'arrPermisson',
            'permissions'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $role = Role::findById($id);
        $permissions = $request->get('permission') ?? null;
        $users = User::where('role_id', $role->id)->get();

        DB::table('role_has_permissions')->where('role_id', $id)->delete();

        if ($users) {
            DB::table('model_has_permissions')->whereIn('model_id', $users->pluck('id')->toArray())
                ->delete();
        }

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);

        }

        if (count($users) > 0 && !empty($permissions)) {
            $listPermission = Permission::whereIn('id', $permissions)->get();

            $permissionsName = $listPermission->pluck('name')->toArray();

            foreach ($users as $user) {
                $user->syncPermissions($permissionsName);
            }
        }

        return redirect()->route('vai-tro.index')->with('success', 'thêm quyền thành công.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $role = Role::findById($id);

        DB::table('role_has_permissions')->where('role_id', $id)->delete();

        $role->delete();

        return redirect()->back()->with('success', 'Xoá thành công.');
    }
}
