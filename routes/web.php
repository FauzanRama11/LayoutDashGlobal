<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\MitraAkademikController;
use App\Http\Controllers\inbound\AmertaController;
use App\Http\Controllers\inbound\AmertaPromotionController;
use App\Http\Controllers\inbound\AmertaRpsController;
use App\Http\Controllers\inbound\LinguaController;
use App\Http\Controllers\inbound\LinguaPromotionController;
use App\Http\Controllers\inbound\LinguaRpsController;
use App\Http\Controllers\inbound\MStuInprogramController;
use App\Http\Controllers\inbound\MStuInTargetController;
use App\Http\Controllers\inbound\VTStudenInboundController;
use App\Http\Controllers\inbound\StudentInboundController;
use App\Http\Controllers\outbound\MStuOutprogramController;
use App\Http\Controllers\outbound\MStuOutTargetController;
use App\Http\Controllers\outbound\StudentOutboundController;
use App\Http\Controllers\outbound\VTStudenOutboundController;

Route::get('/', [AuthenticationController::class, "index"])->name('login');
Route::post('/', [AuthenticationController::class, "auth"]);

Route::get('/404', function () { return view('admin.authentication.error404');});
Route::get('/500', function () { return view('admin.authentication.error500');});

Route::middleware(['auth', 'verified', 'role:gmp'])->group(function () {
    Route::post('/logout', action: [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/gmp/dashboard', function () {
            return view('dashboard.view_dashboard');});
    })->name('home_gmp');

Route::middleware(['auth', 'verified', 'role:fakultas'])->group(function () {
    Route::post('/logout', action: [AuthenticationController::class, 'logout'])->name('logout');
    Route::get('/fakultas/dashboard', action: function () {
            return view('dashboard.view_dashboard');})->name('home_fakultas');
    });

// Mitra Akademik
Route::middleware(['auth', 'verified', 'role:fakultas'])->prefix('mitra-akademik')->group(function () {
        Route::get('/index', [MitraAkademikController::class, 'daftarmitra'])->name('daftarmitra');
        Route::get('/form-submit', [MitraAkademikController::class, 'submitmitra'])->name('submitmitra');
    });

// Student Inbound
Route::middleware(['auth', 'verified', 'role:fakultas|gmp'])->prefix('student-inbound')->group(function () {

    Route::prefix('amerta')->group(function () {
        Route::get('/materi_promosi', [AmertaPromotionController::class, 'index'])->name('am_materi_promosi');
        Route::get('/template_rps', [AmertaRpsController::class, 'index'])->name('am_template_rps');
        Route::get('/pendaftar', [AmertaController::class, 'pendaftar'])->name('am_pendaftar');
        Route::get('/periode', [AmertaController::class, 'periode'])->name('am_periode');
        Route::get('/synced_data', [AmertaController::class, 'synced_data'])->name('am_synced_data');
        Route::get('/nominasi_matkul', [AmertaController::class, 'nominasi_matkul'])->name('am_nominasi_matkul');
    });

    Route::prefix('lingua')->group(function () {
        Route::get('/materi_promosi', [LinguaPromotionController::class, 'index'])->name('li_materi_promosi');
        Route::get('/pendaftar', [LinguaController::class, 'pendaftar'])->name('li_pendaftar');
        Route::get('/periode', [LinguaController::class, 'periode'])->name('li_periode');
        Route::get('/template_rps', [LinguaRpsController::class, 'index'])->name('li_template_rps');
    });
       
        Route::get('/program_age', [MStuInprogramController::class, 'program_age'])->name('stuin_program_age');
        Route::get('/program_fak', [MStuInprogramController::class, 'program_fak'])->name('stuin_program_fak');
        Route::get('/view_peserta', [VTStudenInboundController::class, 'index'])->name('stuin_view_peserta');

        Route::get('/approval_dana', [StudentInboundController::class, 'approval_dana'])->name('stuin_approval_dana');
        Route::get('/approval_pelaporan', [StudentInboundController::class, 'approval_pelaporan'])->name('stuin_approval_pelaporan');
        Route::get('/target', [MStuInTargetController::class, 'index'])->name('stuin_target');
    });

// Student Outbound
Route::middleware(['auth', 'verified', 'role:fakultas|gmp'])->prefix('student-outbound')->group(function () {
       
        Route::get('/program_age', [MStuOutprogramController::class, 'program_age'])->name('stuout_program_age');
        Route::get('/program_fak', [MStuOutprogramController::class, 'program_fak'])->name('stuout_program_fak');
        Route::get('/view_peserta', [VTStudenOutboundController::class, 'index'])->name('stuout_view_peserta');

        Route::get('/approval_dana', [StudentOutboundController::class, 'approval_dana'])->name('stuout_approval_dana');
        Route::get('/approval_pelaporan', [StudentOutboundController::class, 'approval_pelaporan'])->name('stuout_approval_pelaporan');
        Route::get('/pengajuan_setneg', [StudentOutboundController::class, 'pengajuan_setneg'])->name('stuout_pengajuan_setneg');
        Route::get('/target', [MStuOutTargetController::class, 'index'])->name('stuout_target');
    });

   

require __DIR__.'/auth.php';
