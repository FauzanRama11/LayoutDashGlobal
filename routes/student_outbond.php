<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\outbound\MStuOutprogramController;
use App\Http\Controllers\outbound\MStuOutTargetController;
use App\Http\Controllers\outbound\StudentOutboundController;
use App\Http\Controllers\outbound\MStuOutPesertaController;
use App\Http\Controllers\outbound\VTStudenOutboundController;

// Route::get('/tambah-peserta', [MStuOutPesertaController::class, 'add_peserta']);


// Student Outbound
Route::middleware(['auth', 'verified', 'role:fakultas|gmp'])->group(function () {
        Route::get('/tambah-program-fakultas', [MStuOutprogramController::class, 'add_program_fak'])->name('stuout_fak.create');
        Route::post('/store_program_outbound', [MStuOutprogramController::class, 'store_program'])->name('program_stuout.store');
        Route::delete('/Delete/{id}', [MStuOutprogramController::class, 'destroy_program_fak'])->name('prog_stuout.destroy');
        Route::get('/tambah-program-age', [MStuOutprogramController::class, 'add_program_age'])->name('prog_age.create');
        Route::get('/edit-program/{id}', [MStuOutprogramController::class, 'edit'])->name('program_stuout.edit');
        Route::put('/update-program/{id}', [MStuOutprogramController::class, 'update'])->name('program_stuout.update');

        // Route::get('/edit-program/{ids}/tambah-peserta', [MStuOutPesertaController::class, 'add_peserta'])->name('tambah.peserta');
        Route::get('/edit-program/{prog_id}/tambah-peserta', [MStuOutPesertaController::class, 'peserta'])->name('stuout_peserta.create');
        Route::get('/edit-program/{prog_id}/edit-peserta/{item_id}', [MStuOutPesertaController::class, 'peserta'])->name('stuout_peserta.edit');
        Route::post('/store-peserta', [MStuOutPesertaController::class, 'store_peserta'])->name('stuout_peserta.store');
        Route::put('/update-peserta', [MStuOutPesertaController::class, 'update_peserta'])->name('stuout_peserta.update');
        Route::put('/approve-peserta/{id}', [StudentOutboundController::class, 'action_approve'])->name('stuout_peserta.approve');
        Route::put('/unapprove-peserta/{id}', [StudentOutboundController::class, 'action_unapprove'])->name('stuout_peserta.unapprove');
        Route::put('/revise-peserta/{id}', [StudentOutboundController::class, 'action_revise'])->name('stuout_peserta.revise');

        Route::get('/target', [MStuOutTargetController::class, 'index'])->name('stuout_target');
        Route::get('/form-target-out', [MStuOutTargetController::class, 'form_target'])->name('form_target_out.create');
        Route::get('/form-target-out/{id}', [MStuOutTargetController::class, 'form_target'])->name('form_target_out.edit');
        Route::post('/tambah-target-out', [MStuOutTargetController::class, 'tambah_target'])->name('tambah_target_out');
        Route::put('/tambah-target-out/{id}', [MStuOutTargetController::class, 'tambah_target'])->name('update_target_out');
        Route::delete('/hapus-target-out/{id}', [MStuOutTargetController::class, 'hapus_target'])->name('hapus_target_out');

        Route::get('/program-age', [MStuOutprogramController::class, 'program_age'])->name('stuout_program_age');
        Route::get('/program-fak', [MStuOutprogramController::class, 'program_fak'])->name('stuout_program_fak');
        Route::get('/view-peserta', [VTStudenOutboundController::class, 'index'])->name('stuout_view_peserta');

        
        // Route::get('/approval-pelaporan', [StudentInboundController::class, 'approval_pelaporan'])->name('stuin_approval_pelaporan');
        Route::post('/approve-out/{id}', [StudentOutboundController::class, 'actionPesertaApprove'])->name('stuout.approve');
        Route::post('/reject-out/{id}', [StudentOutboundController::class, 'actionPesertaReject'])->name('stuout.reject');
        Route::post('/unapprove-out/{id}', [StudentOutboundController::class, 'actionPesertaUnapprove'])->name('stuout.unapprove');

        Route::get('/approval-pelaporan', [StudentOutboundController::class, 'approval_pelaporan'])->name('stuout_approval_pelaporan');
        Route::get('/pengajuan-setneg', [StudentOutboundController::class, 'pengajuan_setneg'])->name('stuout_pengajuan_setneg');
        
        Route::get('/approval-dana-out', [StudentOutboundController::class, 'approval_dana'])->name('stuout_approval_dana');
        Route::post('/ajukan-bantuan-dana-out', [MStuOutPesertaController::class, 'BantuanDana'])->name('ajukan.bantuan.dana.out');
        Route::post('/approve-dana-out/{id}', [StudentOutboundController::class, 'approveDana'])->name('stuout.approve.dana');
        Route::post('/unapprove-dana-out/{id}', [StudentOutboundController::class, 'unapproveDana'])->name('stuout.unapprove.dana');
        Route::get('/pdf-pengajuan-dana-out/{id}/{tipe}', [StudentOutboundController::class, 'pdfPengajuanOutbound'])->name('stuout.pengajuan.dana');
    });
    

require __DIR__.'/auth.php';