<?php

use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\MailingController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\inbound\MStuInPesertaController;
use App\Http\Controllers\inbound\PendaftaranController;
use App\Http\Controllers\PendaftaranProgramController;
use App\Http\Controllers\staff_inbound\PendaftaranInboundController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticationController::class, "index"])->name('login');
    Route::post('/', [AuthenticationController::class, "auth"]);
    Route::get('/email-text-pass/{encryptedUsername}/{program_id}', [MailingController::class, "password_verification"])->name('pass.verify');
    Route::get('/confirm-user-pass/{encryptedUsername}', [PasswordController::class, 'verifyBase'])->name('verify.codebase');
    Route::post('/verify-code/{encryptedUsername}', [PasswordController::class, 'verifyCode'])->name('verify.code');
    Route::post('/set-password/{encryptedUsername}', [PasswordController::class, 'setPassword'])->name('set.password');

    Route::get('/email-text/{encryptedUsername}/{encryptedEmail}', [MailingController::class, "email_verification"])->name('send.verify');
    Route::post('/email-verify/{encryptedUsername}/{encryptedEmail}', [MailingController::class, "verify"])->name('email.verify');

    Route::get('/confirm-email', function () {
        return view('auth.forgot-password-email');
    });
    Route::post('/check-email/', [PasswordController::class, "checking_email"])->name('check.email');
    Route::get('/email-forget-pass/{encryptedUsername}', [MailingController::class, "forget_password"])->name('forget_pass.verify');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', action: [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/back-home', [AuthenticationController::class, 'backToHome'])->name('back.home');
    Route::get('/view-pdf/{fileName}', [DokumenController::class, 'viewPdf'])->name('view.pdf');
    Route::get('/view-pdf-naskah/{fileName}', [DokumenController::class, 'viewPdfNaskah'])->name('view_naskah.pdf');
    Route::get('/change-password/{username}', [PasswordController::class,'changepass'])->name('change.pass');
    Route::post('/store-change-password/{username}', [PasswordController::class,'store_changepass'])->name('store.change.pass');

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

Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MStuInPesertaController::class, 'profile'])->name('mahasiswa.dashboard');
});

Route::get('/404', function () { return view('admin.authentication.error404');});
Route::get('/500', function () { return view('admin.authentication.error500');});

// Form
Route::get('/{type}-registration-form', [PendaftaranController::class, 'view_regist'])->name('registrasi');
Route::post('/save-selected-program',  [PendaftaranController::class, 'fetch_program']);
Route::post('/{type}-student-registration-submitted', [PendaftaranController::class, 'storeRegistrationForm'])->name('simpan.registrasi');
Route::get('/{type}-registration-submitted', [PendaftaranController::class, 'result'])->name('result');

// Pendaftaran Inbound/Outbound
Route::get('/registrasi-peserta-inbound/{url_generate}', [PendaftaranProgramController::class, 'stuin'])->name('stuin.registrasi');
Route::get('/registrasi-peserta-outbound/{url_generate}', [PendaftaranProgramController::class, 'stuout'])->name('stuout.registrasi');

// Route::get('/try', [AgreementController::class, 'view_pelaporan2']);

Route::post('/registrasi-peserta-outbound', [PendaftaranProgramController::class, 'Simpan_stuout'])->name('simpan.stuout');
Route::post('/registrasi-peserta-inbound', [PendaftaranProgramController::class, 'Simpan_stuin'])->name('simpan.stuin');
Route::get('/registrasi-peserta-outbound/{url_generate}', [PendaftaranProgramController::class, 'stuout'])->name('stuout.registrasi');

Route::get('/inbound-staff-registration-form', action: [PendaftaranInboundController::class, 'external_sta_in'])->name('stain.registrasi');

// Route jika ada folder
Route::get('/repo/{folder}/{fileName?}', [DokumenController::class, 'view'])->name('view.dokumen')->where('folder', '.*');

Route::get('/kirimemail', [MailingController::class, "index"]);
	
// Route::get('/confirm-user-pass', function () {
//     return view('auth.make-password');
// });



require __DIR__.'/auth.php';
