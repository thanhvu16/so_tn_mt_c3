<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserLogs;
use App\Repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use http\Client\Response;
use Hash;
use Modules\Admin\Entities\DonVi;
use Spatie\Permission\Models\Role;
use Validator;
use Illuminate\Http\Request;
use Auth, DB;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $this->validate($request,
            [
                'username' => 'required|unique:users,username',
                'password' => 'required|min:6',
                'email' => 'required|unique:users,email',
                'don_vi_id' => 'required',
                'chuc_vu_id' => 'required',
                'role_id' => 'required',
                'ho_ten' => 'required'
            ],
            [
                'username.required' => 'Vui lòng nhập tài khoản.',
                'username.unique' => 'Tài khoản đã tồn tại vui lòng nhập tài khoản khác',
                'email.required' => 'Vui lòng nhập email.',
                'email.unique' => 'Email đã tồn tại vui lòng nhập email khác.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu tối thiểu 6 kí tự.',
                'don_vi_id.required' => 'Vui lòng chọn đơn vị.',
                'chuc_vu_id.required' => 'Vui lòng chọn chức vụ.',
                'role_id.required' => 'Vui lòng chọn quyền hạn.',
                'ho_ten.required' => 'Vui lòng nhập họ tên.'
            ]);

        try {
            DB::beginTransaction();
            $data = $request->all();
            $donVi = DonVi::where('id', $data['don_vi_id'])->whereNull('deleted_at')->first();
            if ($donVi) {
                $data['cap_xa'] = $donVi->cap_xa ?? null;
            }
            $user = new User();
            $user->fill($data);

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();

            Auth::login($user);

            if (!empty($request->get('role_id'))) {
                $role = Role::findById($request->get('role_id'));
                $user->assignRole($role->name);
                $permissions = $role->permissions->pluck('name')->toArray();
                $user->syncPermissions($permissions);
            }

            UserLogs::saveUserLogs('Tạo mới người dùng', $user);

            $token = $this->createToken($user);
            $userInfo = $this->userRepository->getUserInfo($user);

            DB::commit();

            return response()->json([
                'status' => SUCCESS,
                'message' => 'Đăng ký thành công.',
                'model' => $userInfo,
                'token' => $token->accessToken,
                'token_type' => TOKEN_TYPE,
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'password.required' => 'Vui lòng nhập mật khẩu.'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => ERROR_VALIDATE,
                'message' => 'Đăng nhập không thành công',
                'errors' => $validate->errors()
            ]);
        }

        $credentials = request(['username', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => SERVER_ERROR,
                'message' => 'Unauthorized'
            ]);
        }

        $user = User::where('username', $request->username)->first();

        if ($user->trang_thai != ACTIVE) {
            return response()->json([
                'status' => SERVER_ERROR,
                'message' => 'Tài khoản chưa được kích hoạt.'
            ]);
        }

        // check pass
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => SERVER_ERROR,
                'message' => 'Thông tin đăng nhập không chính xác.'
            ]);
        }

        $token = $this->createToken($user);
        $userInfo = $this->userRepository->getUserInfo($user);

        return response()->json([
            'status' => SUCCESS,
            'message' => 'Đăng nhập thành công.',
            'token' => $token->accessToken,
            'token_type' => TOKEN_TYPE,
            'model' => $userInfo,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString(),
        ]);

    }

    public function createToken($user)
    {
        $tokenResult = $user->createToken(USER_TOKEN);
        $tokenResult->token->expires_at = Carbon::now()->addDays(90);
        $tokenResult->token->save();

        return $tokenResult;
    }
}
