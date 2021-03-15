<?php

namespace Modules\VanBanDi\Entities;

use App\Models\UserLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\Admin\Entities\NgayNghi;
use Modules\DieuHanhVanBanDen\Entities\DonViChuTri;
use Modules\VanBanDen\Entities\FileVanBanDen;
use Modules\VanBanDen\Entities\VanBanDen;
use Auth, File, DB;

class NoiNhanVanBanDi extends Model
{
    protected $table = 'don_vi_nhan_van_ban_di';

    public function laytendonvinhan()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id_nhan', 'id');
    }

    public function donvigui()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_gui', 'id');
    }

    public function vanbandi()
    {
        return $this->belongsTo(VanBanDi::class, 'van_ban_di_id', 'id');
    }

    public static function taoVanBanDenDonViKhongCoDieuHanh($donViNhanVanBanDi, $vanBanDi)
    {
        $ngaynhan = date('Y-m-d');
        $songay = 10;
        $ngaynghi = NgayNghi::where('ngay_nghi', '>', date('Y-m-d'))->where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $i = 0;

        foreach ($ngaynghi as $key => $value) {
            if ($value['ngay_nghi'] != $ngaynhan) {
                if ($ngaynhan <= $value['ngay_nghi'] && $value['ngay_nghi'] <= dateFromBusinessDays((int)$songay, $ngaynhan)) {
                    $i++;
                }
            }

        }
        $hangiaiquyet = dateFromBusinessDays((int)$songay + $i, $ngaynhan);
        $soDen = 1;
        $maxSoDen = VanBanDen::where('don_vi_id', $donViNhanVanBanDi->don_vi_id_nhan)
            ->where('type', VanBanDen::TYPE_VB_DON_VI)
            ->whereYear('created_at', date('Y'))
            ->max('so_den');
        if (!empty($maxSoDen)) {
           $soDen += $maxSoDen;
        }

        $data = [
            'loai_van_ban_id' => $vanBanDi->loai_van_ban_id,
            'so_van_ban_id' => $vanBanDi->so_van_ban_id,
            'so_den' => $soDen,
            'so_ky_hieu' => $vanBanDi->so_ky_hieu,
            'ngay_ban_hanh' => $vanBanDi->ngay_ban_hanh,
            'co_quan_ban_hanh' => $vanBanDi->dvSoanThao->ten_don_vi ?? null,
            'trich_yeu' => $vanBanDi->trich_yeu,
            'nguoi_ky' => $vanBanDi->nguoidung2->ho_ten ?? null,
            'do_khan_cap_id' => $vanBanDi->do_khan_cap_id,
            'do_bao_mat_id' => $vanBanDi->do_bao_mat_id,
            'han_xu_ly' => $hangiaiquyet,
            'han_giai_quyet' => $hangiaiquyet,
            'don_vi_id' => $donViNhanVanBanDi->don_vi_id_nhan,
            'type'  => VanBanDen::TYPE_VB_DON_VI,
            'nguoi_tao' => auth::user()->id,
            'trinh_tu_nhan_van_ban' => VanBanDen::TRUONG_PHONG_NHAN_VB,
        ];
        try {
            DB::beginTransaction();
                $vanBanDen = new VanBanDen();
                $vanBanDen->fill($data);
                $vanBanDen->save();
                UserLogs::saveUserLogs('Vào sổ văn bản đến', $vanBanDen);

                //save chuyen don vi chu tri
                DonViChuTri::LuuDonViKhongDieuHanh($vanBanDen->id, $donViNhanVanBanDi->don_vi_id_nhan);

                //van ban di file chinh
                $files = $vanBanDi->filechinh;

                if (!empty($files) && count($files) > 0) {
                    foreach ($files as $file) {
                        $vanBanDenFile = new FileVanBanDen();
                        $vanBanDenFile->ten_file = $file->ten_file ?? null;
                        $vanBanDenFile->duong_dan = $file->duong_dan ?? null;
                        $vanBanDenFile->duoi_file = $file->duoi_file ?? null;
                        $vanBanDenFile->vb_den_id = $vanBanDen->id ?? null;
                        $vanBanDenFile->nguoi_dung_id = $vanBanDen->nguoi_tao;
                        $vanBanDenFile->don_vi_id = $donViNhanVanBanDi->don_vi_id_nhan;
                        $vanBanDenFile->save();
                        UserLogs::saveUserLogs('Upload file văn bản đến', $vanBanDenFile);
                    }
                }

                //update don vi nhan van ban di
                $donViNhanVanBanDi->trang_thai = 3;
                $donViNhanVanBanDi->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

    }
}
