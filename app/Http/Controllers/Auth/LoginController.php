<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

   

    /**
     * Where to redirect users after login.
     *
     * 
     */
    protected $redirectTo = '/home';

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
    public function login(Request $request)
    {
        
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $usuario = User::where('email', $request->email)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
            Auth::login($usuario);
            return redirect()->route('home')->with('success', 'Bienvenido de nuevo.');
        }

        return back()->withErrors(['password' => 'Las credenciales son incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Has cerrado sesión correctamente.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
}
