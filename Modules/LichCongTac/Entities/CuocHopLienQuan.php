<?php

namespace Modules\LichCongTac\Entities;

use App\Models\LichCongTac;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CuocHopLienQuan extends Model
{
    protected $table = 'qlch_cuoc_hop_lien_quan';
    protected $fillable = [];

    public function CuocHopLienQuan()
    {
        return $this->belongsTo(LichCongTac::class, 'id_cuoc_hop_lien_quan', 'id');
    }
}
