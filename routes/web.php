<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyseController;

// Accueil et analyse — accessibles même sans connexion (redirige vers login si protection voulue)
Route::middleware('auth')->group(function () {

    Route::get('/', [AnalyseController::class, 'index'])->name('home');

    Route::post('/analyse', [AnalyseController::class, 'analyser'])->name('analyse');

    Route::get('/analyse/{id}', [AnalyseController::class, 'show'])->name('analyse.show');

    Route::get('/dashboard', [AnalyseController::class, 'dashboard'])->name('dashboard');

    Route::get('/analyse/{id}/pdf', [AnalyseController::class, 'exportPdf'])->name('analyse.pdf');

});

require __DIR__.'/auth.php';
