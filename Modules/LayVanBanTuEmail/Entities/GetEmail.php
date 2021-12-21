<?php

namespace Modules\LayVanBanTuEmail\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\VanBanDen\Entities\EmailFile;

class GetEmail extends Model
{
    protected $table = 'vbd_email';
    protected $fillable = [
        'id',
        'mail_subject',
        'mail_from',
        'mail_date',
        'mail_attachment',
        'mail_pdf',
        'mail_doc',
        'mail_xls',
        'mail_active',
        'noigui',
        'mail_status',
        'don_vi_id',
        'user_id'
    ];

    public function emailFile()
    {
        return $this->hasMany(EmailFile::class, 'email_id', 'id');

    }
}
