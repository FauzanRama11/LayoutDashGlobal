<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MitraAkademikController;

// Mitra Akademik
Route::middleware(['auth', 'verified', 'role:fakultas'])->group(function () {
        Route::get('/index', [MitraAkademikController::class, 'daftarmitra'])->name('daftarmitra');
        Route::get('/form-submit', [MitraAkademikController::class, 'submitmitra'])->name('submitmitra');
    });

    
require __DIR__.'/auth.php';