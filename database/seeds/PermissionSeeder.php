<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Common\AllPermission;

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
        Permission::findOrCreate(AllPermission::themNguoiDung());
        Permission::findOrCreate(AllPermission::suaNguoiDung());
        Permission::findOrCreate(AllPermission::xoaNguoiDung());


        //don vi
        Permission::findOrCreate(AllPermission::themDonVi());
        Permission::findOrCreate(AllPermission::suaDonVi());
        Permission::findOrCreate(AllPermission::xoaDonVi());

        //chuc vu
        Permission::findOrCreate(AllPermission::themChucVu());
        Permission::findOrCreate(AllPermission::suaChucVu());
        Permission::findOrCreate(AllPermission::xoaChucVu());


        //tham mưu văn bản
        Permission::findOrCreate(AllPermission::thamMuu());


        if ($role) {
            $permissions = Permission::all();
            $role->syncPermissions($permissions);
        }

    }
}
