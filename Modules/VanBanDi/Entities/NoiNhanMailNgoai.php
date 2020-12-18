<?php

namespace Modules\VanBanDi\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\MailNgoaiThanhPho;

class NoiNhanMailNgoai extends Model
{
    protected $table = 'vbd_noi_nhan_mail_ngoai';


    public function laytendonvingoai()
    {
        return $this->belongsTo(MailNgoaiThanhPho::class, 'email', 'id');
    }
}
