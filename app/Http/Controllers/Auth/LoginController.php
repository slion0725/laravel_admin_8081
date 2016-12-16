<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function showLoginForm()
    {
        return view('admin.login');
    }
    public function login(Request $request)
    {
        $status = false;
        $message = 'System error';

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $status = true;
            $message = 'Login';
        } else {
            $status = false;
            $message = 'Email or password is incorrect';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'status' => true,
            'message' => 'Logout',
        ]);
    }
}
