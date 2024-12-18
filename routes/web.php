<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\MitraAkademikController;
use App\Http\Controllers\StudentInboundController;

Route::get('/', [AuthenticationController::class, "index"])->name('login');
Route::post('/', [AuthenticationController::class, "auth"]);

Route::get('/404', function () { return view('admin.authentication.error404');});
Route::get('/500', function () { return view('admin.authentication.error500');});

Route::middleware(['auth', 'verified', 'role:gmp'])->group(function () {
    Route::post('/logout', action: [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/gmp/dashboard', function () {
            return view('gmp.view_dashboard');});
    })->name('home_gmp');

Route::middleware(['auth', 'verified', 'role:fakultas'])->group(function () {
    Route::post('/logout', action: [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/fakultas/dashboard', action: function () {
            return view('fakultas.view_dashboard');})->name('home_fakultas');
    });

// Mitra Akademik
Route::middleware(['auth', 'verified', 'role:fakultas'])->prefix('mitra-akademik')->group(function () {
        Route::get('/index', [MitraAkademikController::class, 'daftarmitra'])->name('daftarmitra');
        Route::get('/form-submit', [MitraAkademikController::class, 'submitmitra'])->name('submitmitra');
    });

// Student Inbound
Route::middleware(['auth', 'verified', 'role:fakultas|gmp'])->prefix('student-inbound')->group(function () {
        Route::get('/program_age', [StudentInboundController::class, 'program_age'])->name('program_age');
        Route::get('/program_fak', [StudentInboundController::class, 'program_fak'])->name('program_fak');
    });

require __DIR__.'/auth.php';
