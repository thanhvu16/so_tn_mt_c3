<?php

namespace App\Http\ViewComposers;

use App\User;
use Illuminate\View\View;
use Modules\Admin\Entities\DonVi;
use Auth;

class DanhSachLanhDaoComposer
{
    private $users;

    public function __construct()
    {
        $role = [CHU_TICH, PHO_CHU_TICH];
        $currentUser = auth::user();
        $donVi = $currentUser->donVi;

        $this->users = User::whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })
            ->whereHas('donVi', function ($query) {
                return $query->whereNull('cap_xa');
            })
            ->select('id', 'ho_ten')->get();

        if (isset($donVi) && $donVi->parent_id != DonVi::NO_PARENT_ID) {
            $this->users = User::whereHas('roles', function ($query) use ($role) {
                return $query->whereIn('name', $role);
            })->where('don_vi_id', $donVi->parent_id)->get();
        }
    }

    public function compose(View $view)
    {
        $view->with('users', $this->users);
    }
}
