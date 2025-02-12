<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AuthenticationController extends Controller
{
 public function index(){
    return view("admin.authentication.login-bs-tt-validation");
 }

 public function auth(Request $request){

    $request->validate([
        'usernameLog' => 'required|string',
        'passwordLog' => 'required|string',
    ]);

    if (Auth::attempt(credentials: ['username' => $request->usernameLog, 'password' => $request->passwordLog])) {
        $user = Auth::user();

        if ($user->is_active == "False") {
            Auth::logout(); 
            return back()->withErrors(['login' => 'Your account is inactive.']);
        }

        $routes = [
            'gmp' => 'gmp.dashboard',
            'fakultas' => 'fakultas.dashboard',

            'gpc' => 'gpc.dashboard',
            'wadek3' => 'wadek3.dashboard',

            'kps' => 'kps.dashboard',
            'dirpen' => 'dirpen.dashboard',
            'pusba' => 'pusba.dashboard',
            'mahasiswa' => 'mahasiswa.dashboard',

            // Tambahkan role lainnya
        ];

        // if ($user->hasRole('mahasiswa')) {
        //     $encryptedUsername = Crypt::encryptString($user->username);
        //     return redirect()->route('mahasiswa.dashboard', ["username" => $encryptedUsername]);
        // }

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
        'gpc' => 'gpc.dashboard',
        'wadek3' => 'wadek3.dashboard',
        'mahasiswa' => 'mahasiswa.dashboard'
        // Tambahkan role lainnya
    ];

    // if ($user->hasRole('mahasiswa')) {
    //     $encryptedUsername = Crypt::encryptString($user->username);
    //     return redirect()->route('mahasiswa.dashboard', ["username" => $encryptedUsername]);
    // }

    foreach ($routes as $role => $routeName) {
        if ($user->hasRole($role)) {
            logger()->info("User has role: {$role}, redirecting to: {$routeName}" );
            return redirect()->route(route: $routeName); // Redirect dan hentikan proses
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

