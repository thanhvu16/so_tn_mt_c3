<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Role::findOrCreate('chủ tịch');
        Role::findOrCreate('phó chủ tịch');
        Role::findOrCreate('trưởng phòng ');
        Role::findOrCreate('phó phòng ');
        Role::findOrCreate('văn thư đơn vị');
        Role::findOrCreate('văn thư huyện');
        Role::findOrCreate('chuyên viên');
        Role::findOrCreate('tham mưu');
        Role::findOrCreate('chánh văn phong');
        Role::findOrCreate('phó chánh văn phòng');
        Role::findOrCreate('trưởng ban');
        Role::findOrCreate('phó trưởng ban');
    }
}
