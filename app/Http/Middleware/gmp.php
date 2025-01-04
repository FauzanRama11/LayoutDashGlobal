<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Gmp
{
    // public function handle(Request $request, Closure $next)
    // {
    //     // Ambil data user dari session
    //     // $user = $request->session()->get('user');
        
    //     if ($user && $user->username === 'gmp') {
    //         return $next($request); // Allow access if user fakultas
    //     }else{
    //         abort(403, 'Unauthorized action.');
    //     }
    // }
}
