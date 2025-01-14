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

Route::middleware(['auth', 'verified', 'role:gpc'])->group(function () {
    Route::get('/gpc/dashboard', function () {
       return view('homepage.home');
        })->name('gpc.dashboard');
});

Route::middleware(['auth', 'verified', 'role:wadek3'])->group(function () {
    Route::get('/wadek3/dashboard', function () {
       return view('homepage.home');
        })->name('wadek3.dashboard');
});


Route::get('/404', function () { return view('admin.authentication.error404');});
Route::get('/500', function () { return view('admin.authentication.error500');});


use App\Http\Controllers\outbound\MStuOutprogramController;
use App\Http\Controllers\outbound\MStuOutPesertaController;
use App\Http\Controllers\outbound\StudentOutboundController;
use App\Http\Controllers\agreement\AgreementController;

Route::get('/tambah-program-fakultas', action: [MStuOutprogramController::class, 'add_program_fak']);
Route::post('/store_program_outbound', [MStuOutprogramController::class, 'store_program'])->name('program_fakultas.store');
Route::delete('/Delete/{id}', [MStuOutprogramController::class, 'destroy_program_fak'])->name('prog_stuout.destroy');
Route::get('/tambah-program-age', [MStuOutprogramController::class, 'add_program_age']);
Route::get('/edit-program/{id}', [MStuOutprogramController::class, 'edit'])->name('program_stuout.edit');
Route::put('/update-program/{id}', [MStuOutprogramController::class, 'update'])->name('program_stuout.update');

Route::get('/edit-program/{ids}/tambah-peserta', [MStuOutPesertaController::class, 'add_peserta'])->name('tambah.peserta');
Route::post('/store-peserta', [MStuOutPesertaController::class, 'store_peserta'])->name('peserta.store');
Route::put('/approve-peserta/{id}', [StudentOutboundController::class, 'action_approve'])->name('stuout_peserta.approve');

Route::get('/tambah-pelaporan-no', [AgreementController::class, 'tambah_pelaporan'])->name('tambah_pelaporan');
Route::post('/store-pelaporan', [AgreementController::class, 'store_pelaporan'])->name('pelaporan.store');
Route::get('/view-pelaporan', [AgreementController::class, 'view_pelaporan'])->name('view_pelaporan');
Route::get('/edit-pelaporan/{id}', [AgreementController::class, 'tambah_pelaporan'])->name('pelaporan.edit');
Route::put('/update-pelaporan/{id}', [AgreementController::class, 'store_pelaporan'])->name('pelaporan.update');
Route::delete('/delete-pelaporan/{id}', [AgreementController::class, 'destroy_pelaporan'])->name('pelaporan.destroy');

Route::put('/approve-pelaporan/{id}', [AgreementController::class, 'approve_pelaporan'])->name('pelaporan.approve');
Route::put('/reject-pelaporan/{id}', [AgreementController::class, 'reject_pelaporan'])->name('pelaporan.reject');
Route::put('/revise-pelaporan/{id}', [AgreementController::class, 'revise_pelaporan'])->name('pelaporan.revise');


require __DIR__.'/auth.php';
