<?php

use App\Http\Controllers\agreement\TelaahNaskah;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\agreement\AgreementController;
use App\Http\Controllers\agreement\UniversityScoringController;
use App\Http\Controllers\agreement\MasterController;
use App\Http\Controllers\agreement\TelaahNaskahController;

Route::middleware(['auth', 'verified', 'role:gpc|wadek3'])->group(function () {
    Route::get('/review-agreement', [TelaahNaskahController::class, 'view_telaah_general'])->name('review_agreement');
    Route::get('/review-agreement-riset', [TelaahNaskahController::class, 'view_telaah_riset'])->name('review_agreement_riset');
    // Route::get('/review-agreement/{current_role}', [TelaahNaskahController::class, 'view_telaah'])->name('review_agreement_role');
    Route::get('/completed-agreement', [AgreementController::class, 'completed_agreement'])->name('completed_agreement');

    Route::get('/form-pelaporan', [AgreementController::class, 'tambah_pelaporan'])->name('tambah_pelaporan');
    Route::post('/store-pelaporan', [AgreementController::class, 'store_pelaporan'])->name('pelaporan.store');
    Route::get('/view-pelaporan', [AgreementController::class, 'view_pelaporan'])->name('view_pelaporan');
    Route::get('/edit-pelaporan/{id}', [AgreementController::class, 'tambah_pelaporan'])->name('pelaporan.edit');
    Route::put('/update-pelaporan/{id}', [AgreementController::class, 'store_pelaporan'])->name('pelaporan.update');
    Route::delete('/delete-pelaporan/{id}', [AgreementController::class, 'destroy_pelaporan'])->name('pelaporan.destroy');
    Route::put('/approve-pelaporan/{id}', [AgreementController::class, 'approve_pelaporan'])->name('pelaporan.approve');
    Route::put('/reject-pelaporan/{id}', [AgreementController::class, 'reject_pelaporan'])->name('pelaporan.reject');
    Route::put('/revise-pelaporan/{id}', [AgreementController::class, 'revise_pelaporan'])->name('pelaporan.revise');

    Route::get('/form-master-database', [MasterController::class, 'tambah_master_database'])->name('master_database.tambah');
    Route::post('/store-master-database', [MasterController::class, 'store_master_database'])->name('master_database.store');
    Route::get('/edit-master-database/{id}', action: [MasterController::class, 'tambah_master_database'])->name('master_database.edit');
    Route::put('/update-master-database/{id}', [MasterController::class, 'store_master_database'])->name('master_database.update');
    Route::delete('/delete-database-agreement/{id}', [MasterController::class, 'destroy_database_agreement'])->name('database_agreement.destroy');
    Route::get('/view-database', [MasterController::class, 'database_agreement'])->name('view_database');
    Route::get('/upload-bukti/{id}', action: [MasterController::class, 'upload_bukti'])->name('bukti.upload');
    Route::put('/update-bukti/{id}', [MasterController::class, 'store_bukti'])->name('bukti.update');
    
    Route::get('/university-score', [UniversityScoringController::class, 'univ_score'])->name('univ_score');
    Route::get('/email', [AgreementController::class, 'email_list'])->name('email_list');
 

});

    
require __DIR__.'/auth.php';