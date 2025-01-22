<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\inbound\PendaftaranController;
use App\Http\Controllers\PendaftaranProgramController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticationController::class, "index"])->name('login');
    Route::post('/', [AuthenticationController::class, "auth"]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', action: [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/back-home', [AuthenticationController::class, 'backToHome'])->name('back.home');
    Route::get('/view-pdf/{fileName}', [DokumenController::class, 'viewPdf'])->name('view.pdf');
    Route::get('/view-pdf-naskah/{fileName}', [DokumenController::class, 'viewPdfNaskah'])->name('view_naskah.pdf');

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


Route::middleware(['auth', 'verified', 'role:kps'])->group(function () {
    Route::get('/kps/dashboard', function () {
       return view('homepage.home');
        })->name('kps.dashboard');
});

Route::middleware(['auth', 'verified', 'role:dirpen'])->group(function () {
    Route::get('/dirpen/dashboard', function () {
       return view('homepage.home');
        })->name('dirpen.dashboard');
});

Route::middleware(['auth', 'verified', 'role:pusba'])->group(function () {
    Route::get('/pusba/dashboard', function () {
       return view('homepage.home');
        })->name('pusba.dashboard');
});

Route::get('/404', function () { return view('admin.authentication.error404');});
Route::get('/500', function () { return view('admin.authentication.error500');});
Route::get('/registrasi', function () { return view('pendaftaran.registrasi');});

// Pendaftaran Amerta
Route::get('/registrasi-peserta-amerta', [PendaftaranController::class, 'amerta'])->name('amerta.registrasi');
Route::get('/registrasi-peserta-lingua', [PendaftaranController::class, 'lingua'])->name('lingua.registrasi');
Route::post('/registrasi-peserta-amerta', [PendaftaranController::class, 'storeRegistrationForm'])->name('simpan.registrasi');


// Pendaftaran Amerta
// Route::get('/registrasi-peserta-inbound', [PendaftaranProgramController::class, 'stuin'])->name('stuin.registrasi');
Route::get('/registrasi-peserta-inbound/{url_generate}', [PendaftaranProgramController::class, 'stuin'])->name('stuin.registrasi');
Route::get('/registrasi-peserta-lingua', [PendaftaranController::class, 'stuout'])->name('stuout.registrasi');


use App\Http\Controllers\outbound\MStuOutprogramController;
use App\Http\Controllers\outbound\MStuOutPesertaController;
use App\Http\Controllers\outbound\StudentOutboundController;
use App\Http\Controllers\agreement\AgreementController;
use App\Http\Controllers\TryController;

Route::get('/tambah-program-fakultas', [MStuOutprogramController::class, 'add_program_fak'])->name('stuout_fak.create');
Route::post('/store_program_outbound', [MStuOutprogramController::class, 'store_program'])->name('program_fakultas.store');
Route::delete('/Delete/{id}', [MStuOutprogramController::class, 'destroy_program_fak'])->name('prog_stuout.destroy');
Route::get('/tambah-program-age', [MStuOutprogramController::class, 'add_program_age']);
Route::get('/edit-program/{id}', [MStuOutprogramController::class, 'edit'])->name('program_stuout.edit');
Route::put('/update-program/{id}', [MStuOutprogramController::class, 'update'])->name('program_stuout.update');

Route::get('/edit-program/{ids}/tambah-peserta', [MStuOutPesertaController::class, 'add_peserta'])->name('tambah.peserta');
Route::post('/store-peserta', [MStuOutPesertaController::class, 'store_peserta'])->name('peserta.store');
Route::put('/approve-peserta/{id}', [StudentOutboundController::class, 'action_approve'])->name('stuout_peserta.approve');


use App\Http\Controllers\YourController;  // Import your controller

Route::get('/try', [TryController::class, 'showDatabase'])->name('your.name');



require __DIR__.'/auth.php';
