<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\inbound\AmertaController;
use App\Http\Controllers\inbound\AmertaPromotionController;
use App\Http\Controllers\inbound\AmertaRpsController;
use App\Http\Controllers\inbound\LinguaController;
use App\Http\Controllers\inbound\LinguaPromotionController;
use App\Http\Controllers\inbound\LinguaRpsController;
use App\Http\Controllers\inbound\MStuInProgramController;
use App\Http\Controllers\inbound\MStuInTargetController;
use App\Http\Controllers\inbound\VTStudenInboundController;
use App\Http\Controllers\inbound\StudentInboundController;
use App\Http\Controllers\inbound\MStuInPesertaController;
use App\Http\Controllers\inbound\PesertaInboundAGEController;

// Student Inbound
Route::middleware(['auth', 'verified', 'role:fakultas|gmp|kps'])->group(function () {

    Route::prefix('amerta')->group(function () {
        Route::get('/materi-promosi', [AmertaPromotionController::class, 'index'])->name('am_materi_promosi');
        Route::get('/template-rps', [AmertaRpsController::class, 'index'])->name('am_template_rps');
        Route::get('/pendaftar', [AmertaController::class, 'pendaftar'])->name('am_pendaftar');
        Route::get('/periode', [AmertaController::class, 'periode'])->name('am_periode');
        Route::get('/synced-data', [AmertaController::class, 'synced_data'])->name('am_synced_data');
        Route::get('/nominasi-matkul', [AmertaController::class, 'nominasi_matkul'])->name('am_nominasi_matkul');

        // form period
        Route::get('/form-periode', [AmertaController::class, 'form_periode'])->name('am_form_periode.create');
        Route::get('/form-periode/{id}', [AmertaController::class, 'form_periode'])->name('am_form_periode.edit');
        Route::post('/tambah-periode', [AmertaController::class, 'tambah_periode'])->name('am_tambah_periode');
        Route::put('/tambah-periode/{id}', [AmertaController::class, 'tambah_periode'])->name('am_update_periode');
        Route::delete('/hapus-periode/{id}', [AmertaController::class, 'hapus_periode'])->name('am_hapus_periode');

        // form RPS
        Route::get('/form-rps', [AmertaRpsController::class, 'form_rps'])->name('am_form_rps.create');
        Route::get('/form-rps/{id}', [AmertaRpsController::class, 'form_rps'])->name('am_form_rps.edit');
        Route::post('/tambah-rps', [AmertaRpsController::class, 'tambah_rps'])->name('am_tambah_rps');
        Route::put('/tambah-rps/{id}', [AmertaRpsController::class, 'tambah_rps'])->name('am_update_rps');
        Route::delete('/hapus-rps/{id}', [AmertaRpsController::class, 'hapus_rps'])->name('am_hapus_rps');

        // form Mata Kuliah
        Route::get('/form-matkul', [AmertaController::class, 'form_matkul'])->name('am_form_matkul.create');
        Route::get('/form-matkul/{id}', [AmertaController::class, 'form_matkul'])->name('am_form_matkul.edit');
        Route::post('/tambah-matkul', [AmertaController::class, 'tambah_matkul'])->name('am_tambah_matkul');
        Route::put('/tambah-matkul/{id}', [AmertaController::class, 'tambah_matkul'])->name('am_update_matkul');
        Route::delete('/hapus-matkul/{id}', [AmertaController::class, 'hapus_matkul'])->name('am_hapus_matkul');

    });

    Route::prefix('lingua')->group(function () {
        Route::get('/materi-promosi', [LinguaPromotionController::class, 'index'])->name('li_materi_promosi');
        Route::get('/pendaftar', [LinguaController::class, 'pendaftar'])->name('li_pendaftar');
        Route::get('/periode', [LinguaController::class, 'periode'])->name('li_periode');
        Route::get('/template-rps', [LinguaRpsController::class, 'index'])->name('li_template_rps');

          // form period
          Route::get('/form-periode', [LinguaController::class, 'form_periode'])->name('li_form_periode.create');
          Route::get('/form-periode/{id}', [LinguaController::class, 'form_periode'])->name('li_form_periode.edit');
          Route::post('/tambah-periode', [LinguaController::class, 'tambah_periode'])->name('li_tambah_periode');
          Route::put('/tambah-periode/{id}', [LinguaController::class, 'tambah_periode'])->name('li_update_periode');
          Route::delete('/hapus-periode/{id}', [LinguaController::class, 'hapus_periode'])->name('li_hapus_periode');
  
          // form RPS
          Route::get('/form-rps', [LinguaRpsController::class, 'form_rps'])->name('li_form_rps.create');
          Route::get('/form-rps/{id}', [LinguaRpsController::class, 'form_rps'])->name('li_form_rps.edit');
          Route::post('/tambah-rps', [LinguaRpsController::class, 'tambah_rps'])->name('li_tambah_rps');
          Route::put('/tambah-rps/{id}', [LinguaRpsController::class, 'tambah_rps'])->name('li_update_rps');
          Route::delete('/hapus-rps/{id}', [LinguaRpsController::class, 'hapus_rps'])->name('li_hapus_rps');
  
          // form Mata Kuliah
          Route::get('/form-matkul', [LinguaController::class, 'form_matkul'])->name('li_form_matkul.create');
          Route::get('/form-matkul/{id}', [LinguaController::class, 'form_matkul'])->name('li_form_matkul.edit');
          Route::post('/tambah-matkul', [LinguaController::class, 'tambah_matkul'])->name('li_tambah_matkul');
          Route::put('/tambah-matkul/{id}', [LinguaController::class, 'tambah_matkul'])->name('li_update_matkul');
          Route::delete('/hapus-matkul/{id}', [LinguaController::class, 'hapus_matkul'])->name('li_hapus_matkul');
    });
        
        Route::get('/edit-peserta/{id}', [PesertaInboundAGEController::class, 'edit'])->name('edit_peserta_inbound');
        Route::put('/approval-peserta/{id}', [PesertaInboundAGEController::class, 'approve'])->name('approve_peserta_inbound');
        Route::put('/rejection-peserta/{id}', [PesertaInboundAGEController::class, 'reject'])->name('reject_peserta_inbound');

        Route::get('/program-age', [MStuInProgramController::class, 'program_age'])->name('stuin_program_age');
        Route::get('/program-fak', [MStuInProgramController::class, 'program_fak'])->name('stuin_program_fak');
        Route::get('/view-peserta', [VTStudenInboundController::class, 'index'])->name('stuin_view_peserta');

        Route::get('/approval-dana', [StudentInboundController::class, 'approval_dana'])->name('stuin_approval_dana');
        Route::get('/approval-pelaporan', [StudentInboundController::class, 'approval_pelaporan'])->name('stuin_approval_pelaporan');

        Route::get('/target', [MStuInTargetController::class, 'index'])->name('stuin_target');
        
        // form target
        Route::get('/form-target', [MStuInTargetController::class, 'form_target'])->name('form_target.create');
        Route::get('/form-target/{id}', [MStuInTargetController::class, 'form_target'])->name('form_target.edit');
        Route::post('/tambah-target', [MStuInTargetController::class, 'tambah_target'])->name('tambah_target');
        Route::put('/tambah-target/{id}', [MStuInTargetController::class, 'tambah_target'])->name('update_target');
        Route::delete('/hapus-target/{id}', [MStuInTargetController::class, 'hapus_target'])->name('hapus_target');

        // form
        Route::get('/tambah-program-fakultas', [MStuInProgramController::class, 'add_program_fak'])->name('stuin_fak.create');
        Route::get('/tambah-program-age', [MStuInProgramController::class, 'add_program_age'])->name('stuin_age.create');
        Route::get('/edit-program/{id}', [MStuInProgramController::class, 'edit'])->name('program_stuin.edit');
        Route::post('/store_program_inbound', [MStuInProgramController::class, 'store_program'])->name('program_stuin.store');
        Route::put('/update-program/{id}', [MStuInProgramController::class, 'update'])->name('program_stuin.update');
        Route::delete('/Delete/{id}', [MStuInProgramController::class, 'destroy_program_fak'])->name('program_stuin.destroy');

        Route::get('/edit-program/{prog_id}/tambah-peserta', [MStuInPesertaController::class, 'peserta'])->name('stuin_peserta.create');
        Route::get('/edit-program/{prog_id}/edit-peserta/{item_id}', [MStuInPesertaController::class, 'peserta'])->name('stuin_peserta.edit');
        Route::post('/store-peserta', [MStuInPesertaController::class, 'store_peserta'])->name('stuin_peserta.store');
        Route::put('/update-peserta', [MStuInPesertaController::class, 'update_peserta'])->name('stuin_peserta.update');
        Route::put('/approve-peserta/{id}', [StudentInboundController::class, 'action_approve'])->name('stuin_peserta.approve');

        

    });



    
require __DIR__.'/auth.php';