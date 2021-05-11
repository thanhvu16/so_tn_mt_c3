<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Spatie\Permission\Models\Role;
use Validator;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(Request $request)
    {

        $user = auth::user();
        $user->fill($request->all());
        $user->save();

        return response()->json([
            'status' => SUCCESS,
            'message' => 'Cập nhập thành công.',
            'model' => $this->userRepository->getUserInfo($user)
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'anh_dai_dien' => 'required|mimes:jpeg,jpg,png'
        ], [
            'anh_dai_dien.required' => 'Ảnh đại diện không được để trống',
            'anh_dai_dien.mimes' => 'Ảnh đại diện phải là định dạng jpeg, jpg, png'
        ]);

        $data = $request->all();
        $user = auth::user();

        if (!empty($data['anh_dai_dien'])) {
            $inputFile = $data['anh_dai_dien'];
            $uploadPath = public_path(UPLOAD_USER);
            $folderUploads = UPLOAD_USER;
            $urlFileInDB = $user->anh_dai_dien;

            $url = uploadFile($inputFile, $uploadPath, $folderUploads, $urlFileInDB);

            $data['anh_dai_dien'] = $url;

            $user->fill($data);
            $user->save();

            return response()->json([
                'status' => SUCCESS,
                'message' => 'Cập nhập thành công.',
                'avatar' => $user->getAvatar()
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $currentPassword = auth::user()->password;

        $validate = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirm' => 'required|same:new_password'
        ], [
            'old_password.required' => 'Trường mật khẩu cũ không được để trống.',
            'new_password.required' => 'Trường mật khẩu mới không được để trống.',
            'new_password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'new_password_confirm.required' => 'Nhập lại mật khẩu không được để trống.',
            'new_password_confirm.same' => 'Nhập lại mật khẩu không trùng với mật khẩu mới.',
        ]);
        if ($validate->fails()) {

            return response()->json([
                'status' => ERROR_VALIDATE,
                'message' => 'Đối mật khẩu không thành công',
                'errors' => $validate->errors()
            ], ERROR_VALIDATE);
        }
        if (Hash::check($request->old_password, $currentPassword)) {
            $userId = Auth::user()->id;
            $user = User::find($userId);
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'status' => SUCCESS,
                'message' => 'Đổi mật khẩu thành công'
            ]);
        } else {
            return response()->json([
                'status' => -1,
                'message' => 'Mật khẩu cũ nhập không chính xác'
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Đăng xuất thành công',
        ]);
    }
}
