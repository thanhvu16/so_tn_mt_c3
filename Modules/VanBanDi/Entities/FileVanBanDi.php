<?php

namespace Modules\VanBanDi\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileVanBanDi extends Model
{
    protected $table = 'file_van_ban_di';
    const LOAI_FILE_DA_KY = 1;
    const DUOI_FILE = 'pdf';
    const TRANG_THAI_DA_GUI = 1;
    const TRANG_THAI_FILE_PHIEU_TRINH = 1;
    const TRANG_THAI_FILE_TRINH_KY = 2;
    const TRANG_THAI_FILE_HO_SO = 3;

    protected $fillable = [
        'ten_file',
        'duong_dan',
        'duoi_file',
        'vb_du_thao_id',
        'don_vi',
        'nguoi_tao',
        'stt'

    ];
    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_dung_id', 'id');
    }

}

