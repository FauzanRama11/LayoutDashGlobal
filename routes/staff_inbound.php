<?php

// use Illuminate\Support\Facades\Route;

// Route::middleware(['auth', 'verified', 'role:fakultas'])->group(function () {

//     Route::prefix('internal')->group(function () {
//         Route::get('/materi-promosi', [::class, 'index'])->name('am_materi_promosi');
//         Route::get('/template-rps', [::class, 'index'])->name('am_template_rps');
//         Route::get('/pendaftar', [::class, 'pendaftar'])->name('am_pendaftar');
//         Route::get('/periode', [::class, 'periode'])->name('am_periode');
//         Route::get('/synced-data', [::class, 'synced_data'])->name('am_synced_data');
//         Route::get('/nominasi-matkul', [::class, 'nominasi_matkul'])->name('am_nominasi_matkul');
//     });

//     Route::prefix('eksternal')->group(function () {
//         Route::get('/materi-promosi', [::class, 'index'])->name('li_materi_promosi');
//         Route::get('/pendaftar', [::class, 'pendaftar'])->name('li_pendaftar');
//         Route::get('/periode', [::class, 'periode'])->name('li_periode');
//         Route::get('/template-rps', [::class, 'index'])->name('li_template_rps');
//     });
       
//         Route::get('/program-age', [::class, 'program_age'])->name('stuin_program_age');
//         Route::get('/program-fak', [::class, 'program_fak'])->name('stuin_program_fak');
//         Route::get('/view-peserta', [::class, 'index'])->name('stuin_view_peserta');

//         Route::get('/approval-dana', [::class, 'approval_dana'])->name('stuin_approval_dana');
//         Route::get('/approval-pelaporan', [::class, 'approval_pelaporan'])->name('stuin_approval_pelaporan');
//         Route::get('/target', [::class, 'index'])->name('stuin_target');
//     });
    
// require __DIR__.'/auth.php';