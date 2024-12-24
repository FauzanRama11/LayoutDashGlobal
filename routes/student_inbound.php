<?php


use Illuminate\Support\Facades\Route;

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

// Student Inbound
Route::middleware(['auth', 'verified', 'role:fakultas|gmp'])->group(function () {

    Route::prefix('amerta')->group(function () {
        Route::get('/materi-promosi', [AmertaPromotionController::class, 'index'])->name('am_materi_promosi');
        Route::get('/template-rps', [AmertaRpsController::class, 'index'])->name('am_template_rps');
        Route::get('/pendaftar', [AmertaController::class, 'pendaftar'])->name('am_pendaftar');
        Route::get('/periode', [AmertaController::class, 'periode'])->name('am_periode');
        Route::get('/synced-data', [AmertaController::class, 'synced_data'])->name('am_synced_data');
        Route::get('/nominasi-matkul', [AmertaController::class, 'nominasi_matkul'])->name('am_nominasi_matkul');
    });

    Route::prefix('lingua')->group(function () {
        Route::get('/materi-promosi', [LinguaPromotionController::class, 'index'])->name('li_materi_promosi');
        Route::get('/pendaftar', [LinguaController::class, 'pendaftar'])->name('li_pendaftar');
        Route::get('/periode', [LinguaController::class, 'periode'])->name('li_periode');
        Route::get('/template-rps', [LinguaRpsController::class, 'index'])->name('li_template_rps');
    });
       
        Route::get('/program-age', [MStuInprogramController::class, 'program_age'])->name('stuin_program_age');
        Route::get('/program-fak', [MStuInprogramController::class, 'program_fak'])->name('stuin_program_fak');
        Route::get('/view-peserta', [VTStudenInboundController::class, 'index'])->name('stuin_view_peserta');

        Route::get('/approval-dana', [StudentInboundController::class, 'approval_dana'])->name('stuin_approval_dana');
        Route::get('/approval-pelaporan', [StudentInboundController::class, 'approval_pelaporan'])->name('stuin_approval_pelaporan');
        Route::get('/target', [MStuInTargetController::class, 'index'])->name('stuin_target');
    });

    
require __DIR__.'/auth.php';