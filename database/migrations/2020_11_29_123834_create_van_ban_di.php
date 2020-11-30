<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVanBanDi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('van_ban_di')) {

            Schema::create('van_ban_di', function (Blueprint $table) {
                $table->id();
                $table->text('trich_yeu')->nullable();
                $table->integer('so_di')->nullable();
                $table->string('so_ky_hieu')->nullable();
                $table->date('ngay_ban_hanh')->nullable();
                $table->integer('loai_van_ban_id')->nullable();
                $table->integer('do_khan_cap_id')->nullable();
                $table->integer('do_bao_mat_id')->nullable();
                $table->integer('don_vi_soan_thao')->nullable();
                $table->integer('so_van_ban_id')->nullable();
                $table->string('chuc_vu')->nullable();
                $table->integer('nguoi_ky')->nullable();
                $table->text('noi_dung_hop')->nullable();
                $table->string('dia_diem')->nullable();
                $table->date('ngay_hop')->nullable();
                $table->time('gio_hop')->nullable();
                $table->integer('don_vi_nhan_id')->nullable();
                $table->tinyInteger('van_ban_du_thao')->nullable()->default(1)->comment('1: là văn bản dự thảo');
                $table->integer('van_ban_den_id')->nullable()->comment('id cua vb den');
                $table->tinyInteger('status')->nullable()->default(1)->comment('1 Duyet du thao');
                $table->integer('dia_diem_id')->nullable()->comment('id tbl danh muc dia diem');
                $table->integer('lanh_dao_id')->nullable()->comment('id của lãnh đạo chỉ đạo cv tu vb đến');
                $table->integer('user_id')->nullable()->comment('cán bộ duyệt dự thảo');
                $table->string('nhan_xet')->nullable()->comment('nhan_xet can bo duyet du thao');
                $table->integer('can_bo_id')->nullable()->comment('cán bộ tao vb du thao');
                $table->integer('nguoi_tao')->nullable()->comment('cán bộ tao vb du thao');
                $table->integer('van_ban_den_don_vi_id')->nullable();
                $table->tinyInteger('loai_van_ban_giay_moi')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', 1)
            ->get();

//        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {

                Schema::create('van_ban_di_'.$donVi->id, function (Blueprint $table) {
                    $table->id();
                    $table->text('trich_yeu')->nullable();
                    $table->integer('so_di')->nullable();
                    $table->string('so_ky_hieu')->nullable();
                    $table->date('ngay_ban_hanh')->nullable();
                    $table->integer('loai_van_ban_id')->nullable();
                    $table->integer('do_khan_cap_id')->nullable();
                    $table->integer('do_bao_mat_id')->nullable();
                    $table->integer('don_vi_soan_thao')->nullable();
                    $table->integer('so_van_ban_id')->nullable();
                    $table->string('chuc_vu')->nullable();
                    $table->integer('nguoi_ky')->nullable();
                    $table->text('noi_dung_hop')->nullable();
                    $table->string('dia_diem')->nullable();
                    $table->date('ngay_hop')->nullable();
                    $table->time('gio_hop')->nullable();
                    $table->integer('don_vi_nhan_id')->nullable();
                    $table->tinyInteger('van_ban_du_thao')->nullable()->default(1)->comment('1: là văn bản dự thảo');
                    $table->integer('van_ban_den_id')->nullable()->comment('id cua vb den');
                    $table->tinyInteger('status')->nullable()->default(1)->comment('1 Duyet du thao');
                    $table->integer('dia_diem_id')->nullable()->comment('id tbl danh muc dia diem');
                    $table->integer('lanh_dao_id')->nullable()->comment('id của lãnh đạo chỉ đạo cv tu vb đến');
                    $table->integer('user_id')->nullable()->comment('cán bộ duyệt dự thảo');
                    $table->string('nhan_xet')->nullable()->comment('nhan_xet can bo duyet du thao');
                    $table->integer('can_bo_id')->nullable()->comment('cán bộ tao vb du thao');
                    $table->integer('nguoi_tao')->nullable()->comment('cán bộ tao vb du thao');
                    $table->integer('van_ban_den_don_vi_id')->nullable();
                    $table->tinyInteger('loai_van_ban_giay_moi')->nullable();
                    $table->softDeletes();
                    $table->timestamps();
                });

                $donVi->migrated = 1;
                $donVi->save();
            }

//        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('van_ban_di');

        $danhSachDonVi = \Modules\Admin\Entities\DonVi::where('dieu_hanh', \Modules\Admin\Entities\DonVi::DIEU_HANH)
            ->where('migrated', 1)
            ->get();

        if (count($danhSachDonVi) > 0) {
            foreach ($danhSachDonVi as $donVi) {

                Schema::dropIfExists('van_ban_di'.$donVi->id);

                $donVi->migrated = null;
                $donVi->save();
            }
        }
    }
}
