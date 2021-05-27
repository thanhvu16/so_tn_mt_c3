<?php

namespace App\Repositories;

use App\User;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    public function getUserInfo($user)
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'ho_ten' => $user->ho_ten,
            'gioi_tinh' => $user->gioi_tinh,
            'ngay_sinh' => $user->ngay_sinh,
            'ma_nhan_su' => $user->ma_nhan_su,
            'anh_dai_dien' => $user->getAvatar(),
            'trinh_do' => $user->trinh_do,
            'so_dien_thoai' => $user->so_dien_thoai,
            'so_dien_thoai_ky_sim' => $user->so_dien_thoai_ky_sim,
            'don_vi_id' => $user->don_vi_id,
            'chuc_vu_id' => $user->chuc_vu_id,
            'chu_ky_chinh' => $user->getChuKyChinh(),
            'chu_ky_nhay' => $user->getChuKyNhay(),
            'quyen_han'   => $user->getRole(),
            'don_vi' => $user->donVi,
            'chuc_vu' => $user->chucVu,
            'token' => !empty($user->userDevice) ? $user->userDevice->token : null
        ];
    }

    public function update($input, $id)
    {
        return parent::update($input, $id); // TODO: Change the autogenerated stub
    }


}
