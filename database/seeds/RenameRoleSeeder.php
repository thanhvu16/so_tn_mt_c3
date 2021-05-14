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

        Role::where('name', 'chủ tịch')->update([
           'name' => 'giám đốc / chi cục trưởng'
        ]);

        Role::where('name', 'phó chủ tịch')->update([
            'name' => 'phó giám đốc / phó chi cục trưởng'
        ]);

        Role::where('name', 'phó phòng')->update([
            'name' => 'phó trưởng phòng'
        ]);

        Role::where('name', 'trưởng ban')->update([
            'name' => 'tp đơn vị cấp 2'
        ]);

        Role::where('name', 'phó trưởng ban')->update([
            'name' => 'phó tp đơn vị cấp 2'
        ]);

        Role::where('name', 'văn thư huyện')->update([
            'name' => 'văn thư sở'
        ]);
    }
}
