<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVanBanDenTable extends Migration
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

                Schema::create('van_ban_den_'.$donVi->id, function (Blueprint $table) {
                    $table->id();
                    $table->integer('loai_van_ban_id')->nullable();
                    $table->integer('so_van_ban_id')->nullable();
                    $table->integer('so_den')->nullable();
                    $table->string('so_ky_hieu')->nullable();
                    $table->date('ngay_ban_hanh')->nullable();
                    $table->string('co_quan_ban_hanh')->nullable();
                    $table->string('nguoi_ky')->nullable();
                    $table->string('chuc_vu')->nullable();
                    $table->text('trich_yeu')->nullable();
                    $table->string('noi_dung')->nullable();
                    $table->string('tom_tat')->nullable();
                    $table->integer('do_khan_cap_id')->nullable();
                    $table->integer('do_bao_mat_id')->nullable();
                    $table->string('noi_gui_den')->nullable();
                    $table->date('ngay_hop')->nullable();
                    $table->time('gio_hop')->nullable();
                    $table->string('noi_dung_hop')->nullable();
                    $table->string('dia_diem')->nullable();
                    $table->date('han_xu_ly')->nullable();
                    $table->integer('lanh_dao_tham_muu')->nullable()
                        ->comment('id lanh dao tham muu van ban');
                    $table->timestamps();
                });

                $donVi->migrated = 1;
                $donVi->save();
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
        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', \Modules\Admin\Entities\DonVi::DIEU_HANH)
            ->where('migrated', 1)
            ->get();

        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {

                Schema::dropIfExists('van_ban_den_'.$donVi->id);

                $donVi->migrated = null;
                $donVi->save();
            }
        }
    }
}
