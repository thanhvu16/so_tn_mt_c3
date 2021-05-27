<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = [
        'title',
        'body',
        'user_sender_id',
        'user_id',
        'status'
    ];

    const STATUS_SEND_SUCCESS = 1;
    const STATUS_SEND_FAIL = 2;



}
