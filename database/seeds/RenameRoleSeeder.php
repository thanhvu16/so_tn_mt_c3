<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RenameRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::where('name', 'admin')->update([
            'name' => 'quản trị hệ thống'
        ]);

        Role::where('name', CHU_TICH)->update([
           'name' => 'giám đốc / chi cục trưởng'
        ]);

        Role::where('name', PHO_CHU_TICH)->update([
            'name' => 'phó giám đốc / phó chi cục trưởng'
        ]);

        Role::where('name', PHO_PHONG)->update([
            'name' => 'phó trưởng phòng'
        ]);

        Role::where('name', TRUONG_BAN)->update([
            'name' => 'tp đơn vị cấp 2'
        ]);

        Role::where('name', PHO_TRUONG_BAN)->update([
            'name' => 'phó tp đơn vị cấp 2'
        ]);

        Role::where('name', VAN_THU_HUYEN)->update([
            'name' => 'văn thư sở'
        ]);

        Role::where('name', VAN_THU_DON_VI)->update([
            'name' => 'văn thư đơn vị'
        ]);
    }
}
