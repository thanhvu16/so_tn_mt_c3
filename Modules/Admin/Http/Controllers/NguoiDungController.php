<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Hash;

class NguoiDungController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $users = User::where('trang_thai', ACTIVE)
            ->whereNull('deleted_at')->orderBy('id', 'DESC')
            ->paginate(PER_PAGE);

        $order  = ($users->currentPage() - 1) * PER_PAGE + 1;

        return view('admin::nguoi-dung.index', compact('users', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::nguoi-dung.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
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

        return redirect()->route('nguoi-dung.index')->with('success','Thêm mới thành công .');

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
        $user = User::findOrFail($id);

        return view('admin::nguoi-dung.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
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

        return redirect()->back()->with('success', 'Cập nhật thành công.');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back()->with('success', 'Xoá thành công.');
    }
}
