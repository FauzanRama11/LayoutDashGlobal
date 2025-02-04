<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\inbound\AmertaController;
use App\Http\Controllers\inbound\AmertaPromotionController;
use App\Http\Controllers\inbound\AmertaRpsController;

Route::middleware(['auth', 'verified', 'role:kps|dirpen|pusbamulya'])->group(function () {

        // Route::get('/materi-promosi', [AmertaPromotionController::class, 'index'])->name('am_materi_promosi');
        // Route::get('/template-rps', [AmertaRpsController::class, 'index'])->name('am_template_rps');
        // Route::get('/nominasi-matkul', [AmertaController::class, 'nominasi_matkul'])->name('am_nominasi_matkul');

        // // form RPS
        // Route::get('/form-rps', [AmertaRpsController::class, 'form_rps'])->name('am_form_rps.create');
        // Route::get('/form-rps/{id}', [AmertaRpsController::class, 'form_rps'])->name('am_form_rps.edit');
        // Route::post('/tambah-rps', [AmertaRpsController::class, 'tambah_rps'])->name('am_tambah_rps');
        // Route::put('/tambah-rps/{id}', [AmertaRpsController::class, 'tambah_rps'])->name('am_update_rps');
        // Route::delete('/hapus-rps/{id}', [AmertaRpsController::class, 'hapus_rps'])->name('am_hapus_rps');

        // // form Mata Kuliah
        // Route::get('/form-matkul', [AmertaController::class, 'form_matkul'])->name('am_form_matkul.create');
        // Route::get('/form-matkul/{id}', [AmertaController::class, 'form_matkul'])->name('am_form_matkul.edit');
        // Route::post('/tambah-matkul', [AmertaController::class, 'tambah_matkul'])->name('am_tambah_matkul');
        // Route::put('/tambah-matkul/{id}', [AmertaController::class, 'tambah_matkul'])->name('am_update_matkul');
        // Route::delete('/hapus-matkul/{id}', [AmertaController::class, 'hapus_matkul'])->name('am_hapus_matkul');

    });