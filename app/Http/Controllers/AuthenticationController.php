<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
 public function index(){
    return view("admin.authentication.login-bs-tt-validation");
 }

 public function auth(Request $request){

    if (Auth::attempt(['username' => $request->usernameLog, 'password' => $request->passwordLog])) 
    {

        $request->session()->put('user', Auth::user()); 
        
            // Redirect berdasarkan role
            if (substr(Auth::user()->username, 0, 2)  === 'fa') {
                return redirect('fa/dashboard'); // Ke dashboard FA
            } elseif (Auth::user()->username === 'gmp') {
                return redirect('gmp/dashboard'); // Ke dashboard GMP
            }

            return redirect('/')->with('error', 'Role tidak dikenali.');
        }

        // Jika login gagal
        return redirect('/')->withErrors(['login' => 'Username atau password salah']);
    }

 public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); 
        $request->session()->regenerateToken();
        return redirect('/'); 
    }
}
