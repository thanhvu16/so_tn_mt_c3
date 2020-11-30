<?php

namespace Modules\VanBanDi\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\MailTrongThanhPho;

class NoiNhanMail extends Model
{
    protected $table = 'vbd_noi_nhan_mail';
    public function laytendonvi()
    {
        return $this->belongsTo(MailTrongThanhPho::class, 'email', 'id');
    }
}
