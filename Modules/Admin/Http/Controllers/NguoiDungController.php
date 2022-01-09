<?php

namespace Modules\Admin\Http\Controllers;

use App\Common\AllPermission;
use App\Http\Controllers\Controller;
use App\Models\UserLogs;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Hash, DB, Auth, Session;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Modules\Admin\Entities\NhomDonVi;
use Modules\Admin\Entities\NhomDonVi_chucVu;
use Modules\VanBanDen\Entities\VanBanDenDonVi;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class NguoiDungController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        canPermission(AllPermission::themNguoiDung());
        $donViId = $request->get('don_vi_id') ?? null;
        $chucVuId = $request->get('chuc_vu_id') ?? null;
        $hoTen = $request->get('ho_ten') ?? null;
        $permission = $request->get('permission') ?? null;
        $username = $request->get('username') ?? null;
        $trangThai = $request->get('trang_thai') ?? null;
        $danhSachPhongBan = null;
        $phonBanId = $request->get('phong_ban_id') ?? null;

        if ($donViId) {
            $danhSachPhongBan = DonVi::where('parent_id', $donViId)->whereNull('deleted_at')->get();
        }

        $users = User::with(['chucVu' => function ($query) {
            return $query->select('id', 'ten_chuc_vu');
        },
            'donVi' => function ($query) {
                return $query->select('id', 'ten_don_vi');
            }])
            ->where(function ($query) use ($donViId, $phonBanId, $danhSachPhongBan) {
                if (!empty($donViId) && empty($phonBanId)) {
                    return $query->where('don_vi_id', $donViId)
                                ->orWhereIn('don_vi_id', $danhSachPhongBan->pluck('id')->toArray());
                } else if (!empty($donViId) && !empty($phonBanId)) {
                    return $query->where('don_vi_id', $phonBanId);
                }
            })
            ->where(function ($query) use ($chucVuId) {
                if (!empty($chucVuId)) {
                    return $query->where('chuc_vu_id', $chucVuId);
                }
            })
            ->where(function ($query) use ($hoTen) {
                if (!empty($hoTen)) {
                    return $query->where('ho_ten', 'LIKE', "%$hoTen%");
                }
            })
            ->where(function ($query) use ($username) {
                if (!empty($username)) {
                    return $query->where('username', 'LIKE', "%$username%");
                }
            })
            ->where(function ($query) use ($trangThai) {
                if (!empty($trangThai)) {
                    return $query->where('trang_thai', $trangThai);
                }
            })
            ->where(function ($query) use ($permission) {
                if (!empty($permission)) {
                    return $query->whereHas('permissions', function ($query) use ($permission) {
                        $query->where('name', 'LIKE', "%$permission%");
                    });
                }
            })
            ->whereNull('deleted_at')
            ->select('id', 'username', 'ho_ten', 'chuc_vu_id', 'don_vi_id', 'gioi_tinh', 'trang_thai')
            ->orderBy('id', 'DESC')
            ->paginate(PER_PAGE);

        $order = ($users->currentPage() - 1) * PER_PAGE + 1;

        $danhSachChucVu = ChucVu::select('id', 'ten_chuc_vu')
            ->whereNull('deleted_at')
            ->orderBy('ten_chuc_vu', 'asc')
            ->get();
        $danhSachDonVi = DonVi::select('id', 'ten_don_vi')
            ->where('parent_id', DonVi::NO_PARENT_ID)
            ->orderBy('ten_don_vi', 'asc')
            ->whereNull('deleted_at')
            ->get();

        $viTriUuTien = User::max('uu_tien');

        return view('admin::nguoi-dung.index', compact('users', 'order',
            'danhSachDonVi', 'danhSachChucVu', 'viTriUuTien', 'danhSachPhongBan'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themNguoiDung());

        $roles = Role::all();
        $danhSachChucVu = ChucVu::orderBy('ten_chuc_vu', 'asc')->whereNull('deleted_at')->get();
        $danhSachDonVi = DonVi::orderBy('ten_don_vi', 'asc')->whereNull('deleted_at')->get();

        $permissions = Permission::whereIn('name', [AllPermission::thamMuu(), AllPermission::VanThuChuyenTrach()])->get();

        return view('admin::nguoi-dung.create',
            compact('roles', 'danhSachDonVi', 'danhSachChucVu', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        canPermission(AllPermission::themNguoiDung());

        $this->validate($request,
            [
                'username' => 'required|unique:users,username',
                'password' => 'required|min:6',
                'email' => 'required|unique:users,email'
            ],
            [
                'username.required' => 'Vui lòng nhập tài khoản.',
                'username.unique' => 'Tài khoản đã tồn tại vui lòng nhập tài khoản khác',
                'email.required' => 'Vui lòng nhập email.',
                'email.unique' => 'Email đã tồn tại vui lòng nhập email khác.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu tối thiểu 6 kí tự.',


            ]);
        $phongBanId = $request->get('phong_ban_id') ?? null;
        $donViId = $request->get('don_vi_id') ?? null;

        $data = $request->all();
        $data['don_vi_id'] = !empty($phongBanId) ? $phongBanId : $donViId;

        if (!empty($data['anh_dai_dien'])) {
            $inputFile = $data['anh_dai_dien'];
            $uploadPath = public_path(UPLOAD_USER);
            $folderUploads = UPLOAD_USER;

            $url = uploadFile($inputFile, $uploadPath, $folderUploads);

            $data['anh_dai_dien'] = $url;
        }

        if (!empty($data['chu_ky_chinh'])) {
            $inputFile = $data['chu_ky_chinh'];
            $uploadPath = public_path(UPLOAD_USER);
            $folderUploads = UPLOAD_USER;

            $url = uploadFile($inputFile, $uploadPath, $folderUploads);

            $data['chu_ky_chinh'] = $url;
        }

        if (!empty($data['chu_ky_nhay'])) {
            $inputFile = $data['chu_ky_nhay'];
            $uploadPath = public_path(UPLOAD_USER);
            $folderUploads = UPLOAD_USER;

            $url = uploadFile($inputFile, $uploadPath, $folderUploads);

            $data['chu_ky_nhay'] = $url;
        }


        $user = new User();
        $donVi = DonVi::where('id', $data['don_vi_id'])->whereNull('deleted_at')->first();
        if ($donVi) {
            $data['cap_xa'] = $donVi->cap_xa ?? null;
            $data['cap_chi_nhanh'] = $donVi->cap_chi_nhanh ?? null;

        }
        $user->fill($data);
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->pass = $data['password'];
        }

        $user->save();
        UserLogs::saveUserLogs('Tạo mới người dùng', $user);

        if (!empty($request->get('role_id'))) {
            $role = Role::findById($request->get('role_id'));
            $user->assignRole($role->name);
            $permissions = $role->permissions->pluck('name')->toArray();
            $user->syncPermissions($permissions);
        }

        if (isset($data['permission'])) {
            $user->syncPermissions($data['permission']);
        }

        return redirect()->route('nguoi-dung.index')->with('success', 'Thêm mới thành công .');

    }

    public function cauHinhEmailDonVi()
    {
        $user = auth::user();
        $donViId = null;
        if ($user->hasRole(VAN_THU_HUYEN)) {
            $lanhDaoSo = User::role([CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->first();

            $donViId = $lanhDaoSo->don_vi_id ?? null;
        } else {
            $donViId = $user->donVi->parent_id;
        }

        $donVi = DonVi::findOrFail($donViId);

        return view('admin::Don_vi.cau_hinh_email', compact('donVi'));
    }

    public function luuCauHinhEmailDonVi(Request $request)
    {
        $user = auth::user();
        $donViId = null;
        if ($user->hasRole(VAN_THU_HUYEN)) {
            $lanhDaoSo = User::role([CHU_TICH])
                ->whereHas('donVi', function ($query) {
                    return $query->whereNull('cap_xa');
                })->first();

            $donViId = $lanhDaoSo->don_vi_id ?? null;
        } else {
            $donViId = $user->donVi->parent_id;
        }

        $donVi = DonVi::findOrFail($donViId);
        if ($donVi) {
            $donVi->email = $request->email;
            if ($request->update_password) {
                $donVi->password = $request->password;
            }
            $donVi->status_email = $request->status_email;
            $donVi->save();

            return redirect()->back()->with('success', 'Lưu cấu hình thành công .');
        }

        return redirect()->back()->with('warning', 'Không tìm thấy dữ liệu vui lòng kiểm tra.');

    }

    public function guiXuLy(Request $request)
    {
        $nguoiDung = User::where('id', auth::user()->id)->first();
        $nguoiDung->password_email = Hash::make($request->passWord);
        $nguoiDung->save();
        return redirect('/')->with('success', 'Cập nhật thành công .');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
//        canPermission(AllPermission::suaNguoiDung());

        $user = User::findOrFail($id);
        $donVi = $user->donVi;
        $donViId = isset($donVi) && $donVi->parent_id != 0 ? $donVi->parent_id : $donVi->id ?? null;

        $roles = Role::all();
        $danhSachChucVu = ChucVu::select('id', 'ten_chuc_vu')->get();
        $danhSachDonVi = DonVi::select('id', 'ten_don_vi')->get();
        $viTriUuTien = User::max('uu_tien');

        $danhSachPhongBan = null;
        if (isset($donVi) && $donVi->parent_id != 0) {
            $danhSachPhongBan = DonVi::where('parent_id', $donVi->parent_id)->select('id', 'ten_don_vi')->get();
        }

        $permissions = Permission::whereIn('name', [AllPermission::thamMuu(), AllPermission::VanThuChuyenTrach()])->get();
        $permissionUser = $user->permissions;
        $arrPermissionId = $permissionUser->pluck('id')->toArray();


        return view('admin::nguoi-dung.edit', compact('user', 'donViId', 'permissions',
            'roles', 'danhSachChucVu', 'danhSachDonVi', 'viTriUuTien', 'danhSachPhongBan', 'donVi', 'arrPermissionId'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
//        canPermission(AllPermission::suaNguoiDung());

        $user = User::findOrFail($id);
        $phongBanId = $request->get('phong_ban_id') ?? null;
        $donViId = $request->get('don_vi_id') ?? null;
        $data = $request->all();
        $data['don_vi_id'] = !empty($phongBanId) ? $phongBanId : $donViId;

        if (!empty($data['anh_dai_dien'])) {
            $inputFile = $data['anh_dai_dien'];
            $uploadPath = public_path(UPLOAD_USER);
            $folderUploads = UPLOAD_USER;
            $urlFileInDB = $user->anh_dai_dien;

            $url = uploadFile($inputFile, $uploadPath, $folderUploads, $urlFileInDB);

            $data['anh_dai_dien'] = $url;
        }

        if (!empty($data['chu_ky_chinh'])) {
            $inputFile = $data['chu_ky_chinh'];
            $uploadPath = public_path(UPLOAD_USER);
            $folderUploads = UPLOAD_USER;
            $urlFileInDB = $user->chu_ky_chinh;

            $url = uploadFile($inputFile, $uploadPath, $folderUploads, $urlFileInDB);

            $data['chu_ky_chinh'] = $url;
        }

        if (!empty($data['chu_ky_nhay'])) {
            $inputFile = $data['chu_ky_nhay'];
            $uploadPath = public_path(UPLOAD_USER);
            $folderUploads = UPLOAD_USER;
            $urlFileInDB = $user->chu_ky_nhay;

            $url = uploadFile($inputFile, $uploadPath, $folderUploads, $urlFileInDB);

            $data['chu_ky_nhay'] = $url;
        }

        $donVi = DonVi::where('id', $data['don_vi_id'])->whereNull('deleted_at')->first();
        if ($donVi) {
            $data['cap_xa'] = $donVi->cap_xa ?? null;
        }

        $user->fill($data);
        $user->save();
        UserLogs::saveUserLogs('Cập nhật người dùng', $user);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->pass = $data['password'];
            $user->save();
        }

        if (!empty($request->get('role_id'))) {
            $role = Role::findById($request->get('role_id'));

            $permissions = $role->permissions->pluck('name')->toArray();

            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($role->name);
            $user->syncPermissions($permissions);
        }

        if (isset($data['permission'])) {
            $user->syncPermissions($data['permission']);
        }

        return redirect()->back()->with('success', 'Cập nhật thành công.');

    }

    public function password()
    {
        $pass = User::all();
        foreach ($pass as $data)
        {
            $user = User::where('id',$data->id)->first();
            $user->password = Hash::make('123456');
            $user->pass = '123456';
            $user->save();
        }

        dd('thành công');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        canPermission(AllPermission::xoaNguoiDung());

        $user = User::findOrFail($id);
        UserLogs::saveUserLogs('Xoá người dùng', $user);
        $user->delete();

        return redirect()->back()->with('success', 'Xoá thành công.');
    }

    public function getChucVu(Request $request, $id)
    {

        if ($id == 0) {
            $ds_chucvu = ChucVu::whereNull('deleted_at')->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $ds_chucvu,
                    'phongBan' => null
                ]);
            }
        } else {
            $ds_chucvu = [];
            $don_vi = DonVi::where('id', $id)->first();
            $nhom_don_vi = $don_vi->nhom_don_vi;
            $lay_chuc_vu = NhomDonVi_chucVu::where('id_nhom_don_vi', $nhom_don_vi)->get();
            foreach ($lay_chuc_vu as $data) {
                $chucvu = ChucVu::where('id', $data->id_chuc_vu)->first();
                array_push($ds_chucvu, $chucvu);
            }
            $phongBan = DonVi::where('parent_id', $id)->select('id', 'ten_don_vi', 'parent_id')->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $ds_chucvu,
                    'phongBan' => $phongBan
                ]);
            }
        }

    }

    public function getDonVi(Request $request, $id)
    {
        $nhom_don_vi = NhomDonVi::where('id', $id)->first();
        $lay_don_vi = DonVi::where('nhom_don_vi', $nhom_don_vi->id)->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $lay_don_vi
            ]);
        }
    }

    public function switchOtherUser(Request $request)
    {
        $id = $request->get('user_id');
        $new_user = User::find($id);
        Session::put( 'origin_user', Auth::id());
        Auth::login( $new_user );

        return redirect()->route('home');
    }

    public function stopSwitchUser(Request $request)
    {
        $id = Session::pull('origin_user');
        $orig_user = User::find( $id );
        Auth::login( $orig_user );
        $request->session()->forget('origin_user');

        return redirect()->route('home');
    }
}
