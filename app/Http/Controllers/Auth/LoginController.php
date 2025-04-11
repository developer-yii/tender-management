<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

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
    // protected $redirectTo = '/home';
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $get_user = User::where('email', $request->email)->first();
        $credentials['email'] = $request->email;
        $credentials['password'] = $request->password;

        $remember = $request->has('remember');
        Config::set('session.expire_on_close', !$remember);
        if (isset($get_user) && $get_user != NULL) {

            if (($get_user->role == '1')) {
                if (Auth::attempt($credentials, $remember)) {
                    return redirect()->intended(route('tender.index'));
                } else {
                    return redirect('login')->withErrors(['password' => 'Das Passwort ist falsch.'])->withInput();
                }
            }
            if ($get_user->role == '2') {
                if (Auth::attempt($credentials, $remember)){
                    return redirect()->route('employee.tenders');
                } else {
                    return redirect('login')->withErrors(['password' => 'Das Passwort ist falsch.'])->withInput();
                }
            }
        } else {
            return redirect('login')->withErrors(['approve' => 'Die angegebenen Zugangsdaten stimmen nicht mit unseren Unterlagen überein.'])->withInput();
        }
    }
}
