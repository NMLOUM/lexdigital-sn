<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyseController;

// Page d'accueil publique
Route::get('/', [AnalyseController::class, 'landing'])->name('landing');

// Routes protégées
Route::middleware('auth')->group(function () {

    Route::get('/nouvelle-analyse', [AnalyseController::class, 'index'])->name('home');

    Route::post('/analyse', [AnalyseController::class, 'analyser'])->name('analyse');

    Route::get('/analyse/{id}', [AnalyseController::class, 'show'])->name('analyse.show');

    Route::get('/dashboard', [AnalyseController::class, 'dashboard'])->name('dashboard');

    Route::get('/analyse/{id}/pdf', [AnalyseController::class, 'exportPdf'])->name('analyse.pdf');

});

require __DIR__.'/auth.php';
