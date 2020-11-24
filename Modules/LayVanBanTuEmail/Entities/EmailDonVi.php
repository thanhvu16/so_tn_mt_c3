<?php

namespace Modules\LayVanBanTuEmail\Entities;

use Illuminate\Database\Eloquent\Model;

class EmailDonVi extends Model
{
    protected $table = 'vbd_email_don_vi';


    protected $fillable = [
        'id',
        'email',
        'ten_don_vi',
        'ngay_nhap',
        'mail_group',
        'mail_cha',
        'trang_thai',
        'sdt'
        ];
    const ACCEPTED = 1;

    public function checkGuiMail()
    {
        if ($this->accepted == self::ACCEPTED) {

            return '<span class="label label-success">Hoạt động</span>';

        } else {
            return '<span class="label label-danger">Không hoạt động</span>';
        }
    }
}
