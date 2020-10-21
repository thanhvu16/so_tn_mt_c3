<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = Role::where('name', 'admin')->first();

        if (empty($role)) {
            $role = Role::create(['name' => 'admin']);
            $user = \App\User::where('username', 'admin')->update([
                'role_id' => $role->id
            ]);

        }

        //nguoi dung
        $permission = Permission::create(['name' => 'thêm người dùng']);
        $permission = Permission::create(['name' => 'sửa người dùng']);
        $permission = Permission::create(['name' => 'xoá người dùng']);

        if ($role) {
            $permissions = Permission::take(4)->get();
            $role->syncPermissions($permissions);
        }

        //don vi
        $permission = Permission::create(['name' => 'thêm đơn vị']);
        $permission = Permission::create(['name' => 'sửa đơn vị']);
        $permission = Permission::create(['name' => 'xoá đơn vị']);

        //chuc vu
        $permission = Permission::create(['name' => 'thêm chức vụ']);
        $permission = Permission::create(['name' => 'sửa chức vụ']);
        $permission = Permission::create(['name' => 'xoá chức vụ']);

    }
}
