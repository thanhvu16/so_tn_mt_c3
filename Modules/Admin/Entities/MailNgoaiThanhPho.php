<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class MailNgoaiThanhPho extends Model
{
    protected $table = 'tbl_thongtin_donvi';

    protected $fillable = [
        'ma_dinh_danh',
        'ten_don_vi',
        'email',
        'dia_chi',
        'sdt',
        'web'

    ];

    const ACCEPTED = 1;
    const EXCEPTED = 2;

    public function checkGuiMail()
    {
        if ($this->accepted == self::ACCEPTED) {

            return '<span class="label label-success">Hoạt động</span>';

        } else {
            return '<span class="label label-danger">Không hoạt động</span>';
        }
    }

}
