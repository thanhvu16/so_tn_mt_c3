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

        //nguoi dunggit
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

        //Sổ Văn bản
        Permission::findOrCreate(AllPermission::themSoVanBan());
        Permission::findOrCreate(AllPermission::suaSoVanBan());
        Permission::findOrCreate(AllPermission::xoaSoVanBan());
        //Loại văn bản
        Permission::findOrCreate(AllPermission::themLoaiVanBan());
        Permission::findOrCreate(AllPermission::suaLoaiVanBan());
        Permission::findOrCreate(AllPermission::xoaLoaiVanBan());
        //Độ mật
        Permission::findOrCreate(AllPermission::themDoMat());
        Permission::findOrCreate(AllPermission::suaDoMat());
        Permission::findOrCreate(AllPermission::xoaDoMat());
        //Độ khẩn
        Permission::findOrCreate(AllPermission::themDoKhan());
        Permission::findOrCreate(AllPermission::suaDoKhan());
        Permission::findOrCreate(AllPermission::xoaDoKhan());
        //văn thư
        Permission::findOrCreate(AllPermission::vanThuDonVi());
        Permission::findOrCreate(AllPermission::vanThuHuyen());

        //tham mưu văn bản
        Permission::findOrCreate(AllPermission::thamMuu());

        //văn bản đến
        Permission::findOrCreate(AllPermission::themVanBanDen());
        Permission::findOrCreate(AllPermission::suaVanBanDen());
        Permission::findOrCreate(AllPermission::xoaVanBanDen());
        //Dự thảo văn bản đi
        Permission::findOrCreate(AllPermission::themDuThao());
        Permission::findOrCreate(AllPermission::suaDuThao());
        Permission::findOrCreate(AllPermission::xoaDuThao());

        //Góp ý dự thảo
        Permission::findOrCreate(AllPermission::themGopY());
        Permission::findOrCreate(AllPermission::suaGopY());
        Permission::findOrCreate(AllPermission::xoaGopY());
        //Giấy mời đến
        Permission::findOrCreate(AllPermission::themGiayMoiDen());
        Permission::findOrCreate(AllPermission::suaGiayMoiDen());
        Permission::findOrCreate(AllPermission::xoaGiayMoiDen());
        //Giấy mời đi
        Permission::findOrCreate(AllPermission::themGiayMoiDi());
        Permission::findOrCreate(AllPermission::suaGiayMoiDi());
        Permission::findOrCreate(AllPermission::xoaGiayMoiDi());
        //Văn bản đi
        Permission::findOrCreate(AllPermission::themVanBanDi());
        Permission::findOrCreate(AllPermission::suaVanBanDi());
        Permission::findOrCreate(AllPermission::xoaVanBanDi());

        //lich cong tac
        Permission::findOrCreate(AllPermission::xemLichCongTac());
        Permission::findOrCreate(AllPermission::suaLichCongTac());
        Permission::findOrCreate(AllPermission::themLichCongTac());
        //hòm thư công
        Permission::findOrCreate(AllPermission::homThuCong());
        //đánh giá cán bộ
        Permission::findOrCreate(AllPermission::tuDanhGiaCanBo());
        Permission::findOrCreate(AllPermission::capTrenDanhGia());
        //in sổ văn bản
        Permission::findOrCreate(AllPermission::inSoVanBan());


        if ($role) {
            $permissions = Permission::all();
            $role->syncPermissions($permissions);
        }

    }
}
