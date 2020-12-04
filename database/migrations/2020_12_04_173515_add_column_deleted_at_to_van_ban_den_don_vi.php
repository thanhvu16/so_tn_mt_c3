<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDeletedAtToVanBanDenDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', 1)->get();

        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {
                Schema::table('van_ban_den_'.$donVi->id, function (Blueprint $table) {
                    $table->softDeletes();

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
        // van ban den don vi
        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', 1)->get();

        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {
                Schema::table('van_ban_den_'.$donVi->id, function (Blueprint $table) {
                    $table->dropColumn('deleted_at');
                });
            }
        }
    }
}
