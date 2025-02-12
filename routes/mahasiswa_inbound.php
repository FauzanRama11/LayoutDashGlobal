<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\inbound\MStuInPesertaController;
use App\Http\Controllers\inbound\MStuInProgramController;

use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
    Route::get('/profile-mahasiswa', [MStuInPesertaController::class, 'profile'])->name('profile.inbound');
    Route::get('/program-mahasiswa/{params}', [MStuInPesertaController::class,'program'])->name('program.inbound');
    // Route::get('/detail-mahasiswa/{program}', [MStuInPesertaController::class,'detail_mahasiswa'])->name('detail_mahasiswa.inbound');
});

    
require __DIR__.'/auth.php';