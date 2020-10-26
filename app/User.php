<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'ho_ten',
        'gioi_tinh',
        'ngay_sinh',
        'ma_nhan_su',
        'anh_dai_dien',
        'cmnd',
        'trinh_do',
        'so_dien_thoai',
        'so_dien_thoai_ky_sim',
        'don_vi_id',
        'chuc_vu_id',
        'role_id',
        'chu_ky_chinh',
        'chu_ky_nhay',
        'trang_thai'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function checkRole()
    {
        $role = Role::findById($this->role_id);

        if ($role->name == 'admin') {
            return true;
        }

        return false;

    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id', 'id');
    }

    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'id');
    }
}
