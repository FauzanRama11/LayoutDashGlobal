<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticationController::class, "index"])->name('login');
    Route::post('/', [AuthenticationController::class, "auth"]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', action: [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/back-home', [AuthenticationController::class, 'backToHome'])->name('back.home');
});

Route::middleware(['auth', 'verified', 'role:gmp'])->group(function () {
    Route::get('/gmp/dashboard', function () {
        return view('homepage.home');
        })->name('gmp.dashboard');
});
    
Route::middleware(['auth', 'verified', 'role:fakultas'])->group(function () {
    Route::get('/fakultas/dashboard', function () {
       return view('homepage.berita');
        })->name('fakultas.dashboard');
});


Route::get('/404', function () { return view('admin.authentication.error404');});
Route::get('/500', function () { return view('admin.authentication.error500');});


require __DIR__.'/auth.php';
