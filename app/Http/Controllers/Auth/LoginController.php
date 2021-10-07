<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web')->except("logout");
        $this->middleware("guest:admin")->except("adminLogout");
    }
    public function login(Request $request)
    {
        $request->validate([
            $this->username() => 'required',
            'password' => 'required|min:5',
        ]);

        if (auth()->guard('admin')->attempt(['username' => $request->username , 'password'=>$request->password , 'status'=> "admin"])) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return redirect()->route("dashboard");
        } 
        elseif(auth()->guard('web')->attempt(['username' => $request->username , 'password'=>$request->password , 'block'=> null])){
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return redirect()->route("index");
        }
        else {
            $this->incrementLoginAttempts($request);

            return redirect()
                ->back()
                ->withInput()
                ->with('error','Error , Cannot login please check your credential!');
        }
        
    }
    
    public function username()
    {
        return 'username';
    }

    public function adminLogout()
    {
        auth()->guard('admin')->logout();
        session()->flush();

        return redirect()->route('login');
    }
}
