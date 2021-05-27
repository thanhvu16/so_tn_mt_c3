<?php

namespace App;

use App\Models\UserDevice;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Auth;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    CONST TRANG_THAI_HOAT_DONG = 1;
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
        'trang_thai',
        'uu_tien',
        'cap_xa'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'password_email',
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

        if ($role->name == QUAN_TRI_HT) {
            return true;
        }

        return false;

    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id', 'id')->select('id', 'ten_chuc_vu', 'ten_viet_tat');
    }

    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'id')
            ->select('id', 'ten_don_vi', 'ten_viet_tat', 'dieu_hanh', 'nhom_don_vi', 'cap_xa', 'ma_hanh_chinh', 'parent_id', 'type');
    }
    public function donViKhacXa()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id', 'id')->whereNull('cap_xa');
    }

    public function getAvatar()
    {
        if (!empty($this->anh_dai_dien)) {

            return asset($this->anh_dai_dien);
        }
    }

    public function getChuKyChinh()
    {
        if (!empty($this->chu_ky_chinh)) {

            return asset($this->chu_ky_chinh);
        }
    }

    public function getChuKyNhay()
    {
        if (!empty($this->chu_ky_nhay)) {

            return asset($this->chu_ky_nhay);
        }
    }

    public function getRole()
    {
        $type = null;

        if (auth::user()->hasRole([CHU_TICH, PHO_CHU_TICH]) && auth::user()->donVi->cap_xa == DonVi::CAP_XA) {
           $type = DonVi::CAP_XA;
        }

        return [
            'name' => auth::user()->roles->pluck('name')[0],
            'type' => $type
        ];
    }

    public function userDevice()
    {
        return $this->hasOne(UserDevice::class, 'user_id', 'id');
    }

}
