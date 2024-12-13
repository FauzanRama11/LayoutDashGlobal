<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

Route::get('/', [AuthenticationController::class, "index"])->name('login');
Route::post('/', [AuthenticationController::class, "auth"]);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/MainDashboard', function () {
        return view('Layouts.master');
    });
});

