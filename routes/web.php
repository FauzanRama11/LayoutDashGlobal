<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

Route::get('/', [AuthenticationController::class, "index"])->name('login');
Route::post('/', [AuthenticationController::class, "auth"]);

Route::middleware(['auth', 'verified', 'role:gmp'])->group(function () {
    Route::post('/logout', action: [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/MainDashboard', function () {
            return view('gmp.view_stubound');});
    });

require __DIR__.'/auth.php';
