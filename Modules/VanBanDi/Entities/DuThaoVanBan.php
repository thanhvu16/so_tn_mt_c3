<?php

namespace Modules\VanBanDi\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DuThaoVanBan extends Model
{
    protected $fillable = [];
    use SoftDeletes;
}
