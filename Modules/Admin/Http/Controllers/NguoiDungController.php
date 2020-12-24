<?php

namespace Modules\Admin\Http\Controllers;

use App\Common\AllPermission;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Hash, DB, Auth;
use Modules\Admin\Entities\ChucVu;
use Modules\Admin\Entities\DonVi;
use Modules\VanBanDen\Entities\VanBanDenDonVi;
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
        $username = $request->get('username') ?? null;

        $users = User::with('chucVu', 'donVi')
            ->where('trang_thai', ACTIVE)
            ->where(function ($query) use ($donViId) {
                if (!empty($donViId)) {
                    return $query->where('don_vi_id', $donViId);
                }
            })
            ->where(function ($query) use ($chucVuId) {
                if (!empty($chucVuId)) {
                    return $query->where('chuc_vu_id', $chucVuId);
                }
            })
            ->where(function ($query) use ($hoTen) {
                if (!empty($hoTen)) {
                    return $query->where('ho_ten', $hoTen);
                }
            })

            ->where(function ($query) use ($username) {
                if (!empty($username)) {
                    return $query->where('username', $username);
                }
            })
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->paginate(PER_PAGE);

        $order = ($users->currentPage() - 1) * PER_PAGE + 1;

        $danhSachChucVu = ChucVu::orderBy('ten_chuc_vu','asc')->get();
        $danhSachDonVi = DonVi::orderBy('ten_don_vi','asc')->get();
//        $danhSachDonVi = DonVi::where('id',3)->first();
//        dd($danhSachDonVi);

        return view('admin::nguoi-dung.index', compact('users', 'order',
            'danhSachDonVi', 'danhSachChucVu'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        canPermission(AllPermission::themNguoiDung());

        $roles = Role::all();
        $danhSachChucVu = ChucVu::orderBy('ten_chuc_vu','asc')->get();
        $danhSachDonVi = DonVi::orderBy('ten_don_vi','asc')->get();

        return view('admin::nguoi-dung.create', compact('roles', 'danhSachDonVi', 'danhSachChucVu'));
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

        $data = $request->all();

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
        $user->fill($data);
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        if (!empty($request->get('role_id'))) {
            $role = Role::findById($request->get('role_id'));
            $user->assignRole($role->name);
        }

        return redirect()->route('nguoi-dung.index')->with('success', 'Thêm mới thành công .');

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
        $roles = Role::all();
        $danhSachChucVu = ChucVu::all();
        $danhSachDonVi = DonVi::all();

        return view('admin::nguoi-dung.edit', compact('user',
            'roles', 'danhSachChucVu', 'danhSachDonVi'));
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

        $data = $request->all();

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


        $user->fill($data);
        $user->save();

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->save();
        }

        if (!empty($request->get('role_id'))) {
            $role = Role::findById($request->get('role_id'));

            $permissions = $role->permissions->pluck('name')->toArray();

            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($role->name);
            $user->syncPermissions($permissions);
        }


        return redirect()->back()->with('success', 'Cập nhật thành công.');

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

        $user->delete();

        return redirect()->back()->with('success', 'Xoá thành công.');
    }
}
