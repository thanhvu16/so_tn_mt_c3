<?php

namespace App\Http\ViewComposers;

use App\User;
use Illuminate\View\View;
use Modules\Admin\Entities\DonVi;

class DanhSachLanhDaoComposer
{
    private $users;

    public function __construct()
    {
        $role = [CHU_TICH, PHO_CHU_TICH];

        $this->users = User::whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })
            ->select('id', 'ho_ten')->get();
    }

    public function compose(View $view)
    {
        $view->with('users', $this->users);
    }
}
