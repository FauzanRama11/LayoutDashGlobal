<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        $user = Auth::user();

        // Jika pengguna tidak terautentikasi, teruskan ke rute berikutnya
        if (!$user) {
            return $next($request);
        }

        // Jika pengguna mencoba mengakses halaman login atau '/', arahkan ke dashboard
        if ($request->is('/') || $request->route()->named('login')) {
            $routes = [
                'gmp' => 'gmp.dashboard', // Role 'a' diarahkan ke rute 'a.dashboard'
                'fakultas' => 'fakultas.dashboard', // Role 'b' diarahkan ke rute 'b.dashboard'
            ];

            foreach ($routes as $role => $routeName) {
                if ($user->hasRole($role)) {
                    logger()->info("User has role: {$role}, redirecting to: {$routeName}");
                    return redirect()->route($routeName);
                }
            }

            // Jika role tidak dikenali, arahkan ke halaman default
            return redirect('/');
        }

        // Jika permintaan bukan untuk halaman login, teruskan permintaan
        // return $next($request);
        return redirect('/');
    }
}