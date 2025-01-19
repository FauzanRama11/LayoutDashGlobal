<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\agreement\AgreementController;
use App\Http\Controllers\agreement\UniversityScoringController;

Route::middleware(['auth', 'verified', 'role:gpc|wadek3'])->group(function () {
    Route::get('/review-agreement', [AgreementController::class, 'review_agreement'])->name('review_agreement');
    Route::get('/completed-agreement', [AgreementController::class, 'completed_agreement'])->name('completed_agreement');


    Route::get('/form-pelaporan', [AgreementController::class, 'tambah_pelaporan'])->name('tambah_pelaporan');
    Route::post('/store-pelaporan', [AgreementController::class, 'store_pelaporan'])->name('pelaporan.store');
    Route::get('/view-pelaporan', [AgreementController::class, 'view_pelaporan'])->name('view_pelaporan');
    Route::get('/edit-pelaporan/{id}', [AgreementController::class, 'tambah_pelaporan'])->name('pelaporan.edit');
    Route::put('/update-pelaporan/{id}', [AgreementController::class, 'store_pelaporan'])->name('pelaporan.update');
    Route::delete('/delete-pelaporan/{id}', [AgreementController::class, 'destroy_pelaporan'])->name('pelaporan.destroy');

    Route::get('/form-master-database', [AgreementController::class, 'tambah_master_database'])->name('master_database.tambah');
    Route::post('/store-master-database', [AgreementController::class, 'store_master_database'])->name('master_database.store');
    Route::get('/edit-master-database/{id}', action: [AgreementController::class, 'tambah_master_database'])->name('master_database.edit');
    Route::get('/upload-bukti/{id}', action: [AgreementController::class, 'upload_bukti'])->name('bukti.upload');
    Route::put('/update-master-database/{id}', [AgreementController::class, 'store_master_database'])->name('master_database.update');
    Route::put('/update-bukti/{id}', [AgreementController::class, 'store_bukti'])->name('bukti.update');
    Route::get('/view-database', [AgreementController::class, 'database_agreement'])->name('view_database');
    Route::delete('/delete-database-agreement/{id}', [AgreementController::class, 'destroy_database_agreement'])->name('database_agreement.destroy');
    
    
    Route::get('/university-score', [UniversityScoringController::class, 'univ_score'])->name('univ_score');
    Route::get('/email', [AgreementController::class, 'email_list'])->name('email_list');

    Route::put('/approve-pelaporan/{id}', [AgreementController::class, 'approve_pelaporan'])->name('pelaporan.approve');
    Route::put('/reject-pelaporan/{id}', [AgreementController::class, 'reject_pelaporan'])->name('pelaporan.reject');
    Route::put('/revise-pelaporan/{id}', [AgreementController::class, 'revise_pelaporan'])->name('pelaporan.revise');
    
});

    
require __DIR__.'/auth.php';