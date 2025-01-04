<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

// Login
Route::get('/', [AuthenticationController::class, "index"])->name('login');
Route::post('/', [AuthenticationController::class, "auth"]);
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');


// Route dengan middleware 'auth' dan 'gmp', prefix 'gmp'
Route::prefix('gmp')->group(function () {
    Route::get('/dashboard', [AuthenticationController::class, "profak"])->name('profak');
});

// Route dengan middleware 'auth' dan 'fa', prefix 'fa'
Route::prefix('fa')->group(function () {
    Route::get('/dashboard', function () {
        return view('fa.index');
    });
});
