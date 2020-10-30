<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTrinhTuNhanVanBanToVanBanDen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->tinyInteger('trinh_tu_nhan_van_ban')->nullable()->after('lanh_dao_tham_muu')->comment('1 =>Chu Tich, 2=>Pho CT, 3=>Truong Phong, 4=>Pho Phong, 5=>Chuyen Vien, 6=>hoan thanh');
        });

        // van ban den don vi
        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', 1)->get();

        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {
                Schema::table('van_ban_den_'.$donVi->id, function (Blueprint $table) {
                    $table->tinyInteger('trinh_tu_nhan_van_ban')->nullable()->after('lanh_dao_tham_muu')->comment('1 =>Chu Tich, 2=>Pho CT, 3=>Truong Phong, 4=>Pho Phong, 5=>Chuyen Vien, 6=>hoan thanh');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->dropColumn('trinh_tu_nhan_van_ban');
        });

        // van ban den don vi
        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', 1)->get();

        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {
                Schema::table('van_ban_den_'.$donVi->id, function (Blueprint $table) {
                    $table->dropColumn('trinh_tu_nhan_van_ban');
                });
            }
        }
    }
}
