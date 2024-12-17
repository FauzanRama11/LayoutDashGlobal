<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Fa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Mengambil nilai dari Auth/session untuk data user
        $user = $request->session()->get('user'); 

        if ($user && substr($user->username, 0, 2) === 'fa') {
            return $next($request); // Allow access if user fakultas
        }else{
            abort(403, 'Unauthorized action.');
        }
    }
}
