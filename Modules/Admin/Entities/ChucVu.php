<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChucVu extends Model
{
//    use SoftDeletes;

    protected $table = 'chuc_vu';
    protected $fillable = [
        'ten_chuc_vu',
        'ten_viet_tat'


    ];
    public function nhomDonVi()
    {
        return $this->belongsTo(NhomDonVi::class, 'nhom_don_vi', 'id');
    }

    public function tenNhomDonvi($idchucvu)
    {
        $chucvu = ChucVu::where('id',$idchucvu)->first();
        if($chucvu)
        {
            $lay_nhom_don_vi = json_decode($chucvu->nhom_don_vi);
        }
        return $lay_nhom_don_vi;
    }

}

