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


use App\Http\Controllers\outbound\MStuOutprogramController;
use App\Http\Controllers\outbound\MStuOutPesertaController;
use App\Http\Controllers\outbound\StudentOutboundController;

Route::get('/tambah-program-fakultas', action: [MStuOutprogramController::class, 'add_program_fak']);
Route::post('/store_program_outbound', [MStuOutprogramController::class, 'store_program'])->name('program_fakultas.store');
Route::delete('/Delete/{id}', [MStuOutprogramController::class, 'destroy_program_fak'])->name('prog_stuout.destroy');
Route::get('/tambah-program-age', [MStuOutprogramController::class, 'add_program_age']);
Route::get('/edit-program/{id}', [MStuOutprogramController::class, 'edit'])->name('program_stuout.edit');
Route::put('/update-program/{id}', [MStuOutprogramController::class, 'update'])->name('program_stuout.update');

Route::get('/edit-program/{ids}/tambah-peserta', [MStuOutPesertaController::class, 'add_peserta'])->name('tambah.peserta');
Route::post('/store-peserta', [MStuOutPesertaController::class, 'store_peserta'])->name('peserta.store');
Route::put('/approve-peserta/{id}', [StudentOutboundController::class, 'action_approve'])->name('stuout_peserta.approve');

// Route::get('/tambah-peserta', [MStuOutPesertaController::class, 'add_peserta']);


require __DIR__.'/auth.php';
