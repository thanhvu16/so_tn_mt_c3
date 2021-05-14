<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Common\AllPermission;

class ParentPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nguoiDung = Permission::findOrCreate(AllPermission::nguoiDung());
        Permission::where('name', 'LIKE', "%người dùng%")
            ->where('id', '!=', $nguoiDung->id)
            ->update([
                'parent_id' => $nguoiDung->id
            ]);

        $donVi = Permission::findOrCreate(AllPermission::donVi());
        Permission::where('name', 'LIKE', "%".AllPermission::donVi()."%")
            ->whereNotIn('name', [AllPermission::vanThuDonVi(), AllPermission::inSoVanBan()])
            ->where('id', '!=', $donVi->id)
            ->update([
                'parent_id' => $donVi->id
            ]);

        $chucVu = Permission::findOrCreate(AllPermission::chucVu());
        Permission::where('name', 'LIKE', "%".AllPermission::chucVu()."%")
            ->where('id', '!=', $chucVu->id)
            ->update([
                'parent_id' => $chucVu->id
            ]);

        $soVanBan = Permission::findOrCreate(AllPermission::soVanBan());
        Permission::where('name', 'LIKE', "%".AllPermission::soVanBan()."%")
            ->whereNotIn('name', [AllPermission::inSoVanBan()])
            ->where('id', '!=', $soVanBan->id)
            ->update([
                'parent_id' => $soVanBan->id
            ]);

        $loaiVanBan = Permission::findOrCreate(AllPermission::loaiVanBan());
        Permission::where('name', 'LIKE', "%".AllPermission::loaiVanBan()."%")
            ->where('id', '!=', $loaiVanBan->id)
            ->update([
                'parent_id' => $loaiVanBan->id
            ]);

        $doMat = Permission::findOrCreate(AllPermission::doMat());
        Permission::where('name', 'LIKE', "%".AllPermission::doMat()."%")
            ->where('id', '!=', $doMat->id)
            ->update([
                'parent_id' => $doMat->id
            ]);

        $doKhan = Permission::findOrCreate(AllPermission::doKhan());
        Permission::where('name', 'LIKE', "%".AllPermission::doKhan()."%")
            ->where('id', '!=', $doKhan->id)
            ->update([
                'parent_id' => $doKhan->id
            ]);

        $vanThu = Permission::findOrCreate(AllPermission::vanThu());
        Permission::where('name', 'LIKE', "%".AllPermission::vanThu()."%")
            ->where('id', '!=', $vanThu->id)
            ->update([
                'parent_id' => $vanThu->id
            ]);

        $vanBanDen = Permission::findOrCreate(AllPermission::vanBanDen());
        Permission::where('name', 'LIKE', "%".AllPermission::vanBanDen()."%")
            ->where('id', '!=', $vanBanDen->id)
            ->update([
                'parent_id' => $vanBanDen->id
            ]);

        $duThaoVanBan = Permission::findOrCreate(AllPermission::duThaoVanBan());
        Permission::where('name', 'LIKE', "%".AllPermission::duThaoVanBan()."%")
            ->where('id', '!=', $duThaoVanBan->id)
            ->update([
                'parent_id' => $duThaoVanBan->id
            ]);
        Permission::where('name', 'LIKE', "%".AllPermission::suaDuThao()."%")
            ->where('id', '!=', $duThaoVanBan->id)
            ->update([
                'parent_id' => $duThaoVanBan->id
            ]);
        Permission::where('name', 'LIKE', "%".AllPermission::xoaDuThao()."%")
            ->where('id', '!=', $duThaoVanBan->id)
            ->update([
                'parent_id' => $duThaoVanBan->id
            ]);

        $gopYDuThao = Permission::findOrCreate(AllPermission::gopYDuThao());
        Permission::where('name', 'LIKE', "%".AllPermission::themGopY()."%")
            ->where('id', '!=', $gopYDuThao->id)
            ->update([
                'parent_id' => $gopYDuThao->id
            ]);
        Permission::where('name', 'LIKE', "%".AllPermission::suaGopY()."%")
            ->where('id', '!=', $gopYDuThao->id)
            ->update([
                'parent_id' => $gopYDuThao->id
            ]);
        Permission::where('name', 'LIKE', "%".AllPermission::xoaGopY()."%")
            ->where('id', '!=', $gopYDuThao->id)
            ->update([
                'parent_id' => $gopYDuThao->id
            ]);

        $giayMoiDen = Permission::findOrCreate(AllPermission::giayMoiDen());
        Permission::where('name', 'LIKE', "%".AllPermission::giayMoiDen()."%")
            ->where('id', '!=', $giayMoiDen->id)
            ->update([
                'parent_id' => $giayMoiDen->id
            ]);

        $giayMoiDi = Permission::findOrCreate(AllPermission::giayMoiDi());
        Permission::where('name', 'LIKE', "%".AllPermission::giayMoiDi()."%")
            ->where('id', '!=', $giayMoiDi->id)
            ->update([
                'parent_id' => $giayMoiDi->id
            ]);

        $vanBanDi = Permission::findOrCreate(AllPermission::vanBanDi());
        Permission::where('name', 'LIKE', "%".AllPermission::vanBanDi()."%")
            ->where('id', '!=', $vanBanDi->id)
            ->update([
                'parent_id' => $vanBanDi->id
            ]);

        $tieuChuan = Permission::findOrCreate(AllPermission::tieuChuan());
        Permission::where('name', 'LIKE', "%".AllPermission::tieuChuan()."%")
            ->where('id', '!=', $tieuChuan->id)
            ->update([
                'parent_id' => $tieuChuan->id
            ]);

        $danhGiaCanBo = Permission::findOrCreate(AllPermission::danhGiaCanBo());
        Permission::where('name', 'LIKE', "%".AllPermission::danhGiaCanBo()."%")
            ->where('id', '!=', $danhGiaCanBo->id)
            ->update([
                'parent_id' => $danhGiaCanBo->id
            ]);

        $lichCongTac = Permission::findOrCreate(AllPermission::lichCongTac());
        Permission::where('name', 'LIKE', "%".AllPermission::lichCongTac()."%")
            ->where('id', '!=', $lichCongTac->id)
            ->update([
                'parent_id' => $lichCongTac->id
            ]);

        $chung = Permission::findOrCreate(AllPermission::chung());

        Permission::where('name', 'LIKE', "%".AllPermission::thamMuu()."%")
            ->update([
                'parent_id' => $chung->id
            ]);

        Permission::where('name', 'LIKE', "%".AllPermission::homThuCong()."%")
            ->update([
                'parent_id' => $chung->id
            ]);

        Permission::where('name', 'LIKE', "%".AllPermission::inSoVanBan()."%")
            ->update([
                'parent_id' => $chung->id
            ]);

        Permission::where('name', 'LIKE', "%".AllPermission::deXuatCongViec()."%")
            ->update([
                'parent_id' => $chung->id
            ]);

        Permission::where('name', 'LIKE', "%".AllPermission::capNhatHomThuCongHomThuCong()."%")
            ->update([
                'parent_id' => $chung->id
            ]);

        $thongKe = Permission::findOrCreate(AllPermission::thongKe());
        Permission::where('name', 'LIKE', "%".AllPermission::thongKeVanBanSo()."%")
            ->update([
                'parent_id' => $thongKe->id
            ]);

        Permission::where('name', 'LIKE', "%".AllPermission::thongKeVanBanChiCuc()."%")
            ->update([
                'parent_id' => $thongKe->id
            ]);

    }
}
