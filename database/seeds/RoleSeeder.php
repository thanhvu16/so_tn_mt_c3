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
        Role::findOrCreate('trưởng phòng đơn vị');
        Role::findOrCreate('phó phòng đơn vị');
        Role::findOrCreate('văn thư đơn vị');
        Role::findOrCreate('văn thư huyện');
        Role::findOrCreate('chuyên viên');
        Role::findOrCreate('tham mưu');
    }
}
