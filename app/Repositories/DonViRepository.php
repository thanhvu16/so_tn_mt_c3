<?php
namespace App\Repositories;

use Modules\Admin\Entities\DonVi;

class DonViRepository extends BaseRepository
{
    public function model()
    {
        return DonVi::class;
    }

    public function getData()
    {
        $danhSachDonVi = DonVi::where('parent_id', DonVi::NO_PARENT_ID)
            ->whereNull('deleted_at')->select('id', 'ten_don_vi', 'cap_xa', 'type')->get();

        if ($danhSachDonVi) {
            foreach ($danhSachDonVi as $donVi) {
                $donVi->phong_ban = $this->getPhongBan($donVi);
            }
        }
       return $danhSachDonVi->toArray();
    }

    public function getPhongBan($donVi)
    {
        $danhSachPhongBan = DonVi::where('parent_id', $donVi->id)
            ->select('id', 'ten_don_vi', 'parent_id')->get();

        return $danhSachPhongBan->toArray();
    }
}
