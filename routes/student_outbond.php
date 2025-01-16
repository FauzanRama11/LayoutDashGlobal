<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\outbound\MStuOutprogramController;
use App\Http\Controllers\outbound\MStuOutTargetController;
use App\Http\Controllers\outbound\StudentOutboundController;
use App\Http\Controllers\outbound\VTStudenOutboundController;

// Student Outbound
Route::middleware(['auth', 'verified', 'role:fakultas|gmp'])->group(function () {
        Route::get('/program-age', [MStuOutprogramController::class, 'program_age'])->name('stuout_program_age');
        Route::get('/program-fak', [MStuOutprogramController::class, 'program_fak'])->name('stuout_program_fak');
        Route::get('/view-peserta', [VTStudenOutboundController::class, 'index'])->name('stuout_view_peserta');

        Route::get('/approval-dana', [StudentOutboundController::class, 'approval_dana'])->name('stuout_approval_dana');
        Route::get('/approval-pelaporan', [StudentOutboundController::class, 'approval_pelaporan'])->name('stuout_approval_pelaporan');
        Route::get('/pengajuan-setneg', [StudentOutboundController::class, 'pengajuan_setneg'])->name('stuout_pengajuan_setneg');
        Route::get('/target', [MStuOutTargetController::class, 'index'])->name('stuout_target');
    });
    

require __DIR__.'/auth.php';