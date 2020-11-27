<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGioNgayHopPhuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


            Schema::table('van_ban_den', function (Blueprint $table) {
                $table->date('ngay_hop_phu')->nullable();
                $table->time('gio_hop_phu')->nullable();
                $table->string('noi_dung_hop_phu')->nullable();
                $table->string('dia_diem_phu')->nullable();
            });


        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', 1)->get();

        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {
                Schema::table('van_ban_den_'.$donVi->id, function (Blueprint $table) {
                    $table->date('ngay_hop_phu')->nullable();
                    $table->time('gio_hop_phu')->nullable();
                    $table->string('noi_dung_hop_phu')->nullable();
                    $table->string('dia_diem_phu')->nullable();
                    $table->integer('nguoi_tao')->nullable();
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
            $table->dropColumn('ngay_hop_phu');
            $table->dropColumn('gio_hop_phu');
            $table->dropColumn('noi_dung_hop_phu');
            $table->dropColumn('dia_diem_phu');
        });

        // van ban den don vi
        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', 1)->get();

        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {
                Schema::table('van_ban_den_'.$donVi->id, function (Blueprint $table) {
                    $table->dropColumn('ngay_hop_phu');
                    $table->dropColumn('gio_hop_phu');
                    $table->dropColumn('noi_dung_hop_phu');
                    $table->dropColumn('dia_diem_phu');
                    $table->dropColumn('nguoi_tao');
                });
            }
        }
    }
}
