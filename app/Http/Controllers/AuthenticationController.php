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

    if (Auth::attempt(['name' => $request->usernameLog, 'password' => $request->passwordLog])) {
        return redirect("/MainDashboard");

    } else {
        return redirect("/");
    }
 }
 public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); 
        $request->session()->regenerateToken();
        return redirect('/'); 
    }
}
