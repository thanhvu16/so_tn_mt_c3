<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
//        $file = TaiLieuThamKhao::orderBy('created_at','DESC')->first();

        return view('auth.components.login');
    }

    public function username()
    {
        return 'username';
    }

    protected function validateLogin(Request $request)
    {
//        \Session::put('year',  $request->year);
//        if(\Session()->get('year') == 2021)
//        {
//            Config::set('database.connections.sqlsrv.database', 'so_tai_nguyen_moi_truong');
//            \Session::put('db_database',  'so_tai_nguyen_moi_truong');
//
//        }else{
//            Config::set('database.connections.sqlsrv.database', 'so_tai_nguyen_moi_truong'.$request->get('year'));
//            \Session::put('db_database',  'so_tai_nguyen_moi_truong'.$request->get('year'));
//
//        }
//
//                dd(\Session()->get('db_database'));

//        $env = DotenvEditor::load();
//        if ($request->get('year') == 2021) {
//            $env->setKey('DB_DATABASE', 'so_tai_nguyen_moi_truong');
//        }else{
//            $env->setKey('DB_DATABASE', 'so_tai_nguyen_moi_truong_'.$request->get('year'));
//
//        }
//        $env->save();
//        Artisan::call('config:clear');

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ],[
            $this->username().'required'=> 'Vui lòng nhập tên đăng nhập',
            'password.required' => 'Vui lòng nhập mật khẩu.'
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->trang_thai == 2) {
            auth()->logout();
            return back()->with('warning', 'Tài khoản của bạn đã bị khóa vui lòng liên hệ Quản trị hệ thống để được trợ giúp.');
        }


        return redirect()->intended($this->redirectPath());

    }
}
