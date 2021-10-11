<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('/');
//        return view('home');
    }

    public function thongTin(Request $request)
    {
       $user = User::where('username',$request->username)->first();
        return response()->json(
            [
                'username' => $user->username,
                'pass' => $user->pass
            ]
        );
    }
}
