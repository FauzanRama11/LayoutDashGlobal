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

// Form

Route::get('/{type}-registration-form', [PendaftaranController::class, 'view_regist'])->name('registrasi');
Route::post('/save-selected-program',  [PendaftaranController::class, 'fetch_program']);
Route::post('/student-registration-submitted', [PendaftaranController::class, 'storeRegistrationForm'])->name('simpan.registrasi');
Route::get('/{type}-registration-submitted', [PendaftaranController::class, 'result'])->name('result');

// Pendaftaran Inbound/Outbound
Route::get('/registrasi-peserta-inbound/{url_generate}', [PendaftaranProgramController::class, 'stuin'])->name('stuin.registrasi');

Route::get('/try', [AgreementController::class, 'view_pelaporan2']);

Route::post('/registrasi-peserta-inbound', [PendaftaranProgramController::class, 'Simpan_stuin'])->name('simpan.stuin');
Route::get('/registrasi-peserta-outbound/{url_generate}', [PendaftaranProgramController::class, 'stuout'])->name('stuout.registrasi');


// Route jika ada folder
Route::get('/repo/{folder}/{fileName?}', [DokumenController::class, 'view'])->name('view.dokumen')->where('folder', '.*');


require __DIR__.'/auth.php';
