<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class AuthAdminController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/dashboard';

    function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    public function showLoginForm()
    {
        return view("auth.admin.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            $this->username() => 'required',
            'password' => 'required|min:5',
        ]);

        if (auth()->guard('admin')->attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return redirect()->route("dashboard");
        } else {
            $this->incrementLoginAttempts($request);

            return redirect()
                ->back()
                ->withInput()
                ->with('error','Incorrect admin credentials!');
        }
        
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        session()->flush();

        return redirect()->route('logout_admin');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function username()
    {
        return 'username';
    }
}
