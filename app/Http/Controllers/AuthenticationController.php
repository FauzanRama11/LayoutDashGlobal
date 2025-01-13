<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
 public function index(){
    return view("admin.authentication.login-bs-tt-validation");
 }

 public function auth(Request $request){

    if (Auth::attempt(credentials: ['username' => $request->usernameLog, 'password' => $request->passwordLog])) {
        $user = Auth::user();

        $routes = [
            'gmp' => 'gmp.dashboard',
            'fakultas' => 'fakultas.dashboard',
            'kps' => 'kps.dashboard',
            'dirpen' => 'dirpen.dashboard',
            'pusba' => 'pusba.dashboard',
            // Tambahkan role lainnya
        ];

        foreach ($routes as $role => $routeName) {
            if ($user->hasRole($role)) {
                return redirect()->route($routeName); // Redirect dan hentikan proses
            }
        }
        return redirect()->route('/'); // Default redirect
        }
    return redirect('/'); 
 }

 public function backToHome(){
    $user = Auth::user();

    $routes = [
        'gmp' => 'gmp.dashboard',
        'fakultas' => 'fakultas.dashboard',
        // Tambahkan role lainnya
    ];

    foreach ($routes as $role => $routeName) {
        if ($user->hasRole($role)) {
            logger()->info("User has role: {$role}, redirecting to: {$routeName}");
            return redirect()->route($routeName); // Redirect dan hentikan proses
        }
    }
    return redirect('/'); 
}

 public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); 
        $request->session()->regenerateToken();
        return redirect('/'); 
    }
}

