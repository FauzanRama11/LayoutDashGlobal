<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthenticationController extends Controller
{
   public function index(){
      return view("admin.authentication.login-bs-tt-validation");
   }

   public function auth(Request $request){

    // if (Auth::attempt(['username' => $request->usernameLog, 'password' => $request->passwordLog])) 
    // {
        
    //         // // Redirect berdasarkan role
    //         // if (substr(Auth::user()->username, 0, 2)  === 'fa') {
    //         //     return redirect('fa/dashboard'); // Ke dashboard FA
    //         // } elseif (Auth::user()->username === 'gmp') {
    //         //     return redirect('gmp/dashboard'); // Ke dashboard GMP
    //         // }

    //         // return redirect('/')->with('error', 'Role tidak dikenali.');
    //     }

    //     // Jika login gagal
    //     return redirect('/')->withErrors(['login' => 'Username atau password salah']);
    }

   public function logout(Request $request){
        Auth::logout();
        return redirect('/'); 
    }

    public function profak()
    {
        $user = Auth::user();

        
         $data = DB::table('m_stu_in_programs')
         ->select( 'name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
         ->where("is_program_age", "N")
         ->limit(500)
         ->orderBy('created_time', 'desc')
         ->get();

        return view('gmp.index', compact('data'));
    }
    
}
