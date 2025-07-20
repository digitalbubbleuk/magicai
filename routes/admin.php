<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Creative Suite Admin Routes
|
*/

// Creative Suite Admin Routes
Route::middleware(['web', 'auth', 'admin'])
->prefix('dashboard/admin')
    ->name('dashboard.admin.')
    ->group(function () {
        
        // Creative Suite Template Management
        Route::prefix('creative-suite')->name('creative-suite.')->group(function () {
            Route::prefix('templates')->name('templates.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\CreativeSuiteTemplateController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\CreativeSuiteTemplateController::class, 'create'])->name('create');
                Route::post('/save', [\App\Http\Controllers\Admin\CreativeSuiteTemplateController::class, 'store'])->name('store');
                Route::get('/edit/{template}', [\App\Http\Controllers\Admin\CreativeSuiteTemplateController::class, 'edit'])->name('edit');
                Route::put('/update/{template}', [\App\Http\Controllers\Admin\CreativeSuiteTemplateController::class, 'update'])->name('update');
                Route::get('/delete/{template}', [\App\Http\Controllers\Admin\CreativeSuiteTemplateController::class, 'destroy'])->name('delete');
                Route::post('/duplicate/{template}', [\App\Http\Controllers\Admin\CreativeSuiteTemplateController::class, 'duplicate'])->name('duplicate');
            });
        });
    });
