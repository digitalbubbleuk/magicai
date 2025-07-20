<?php

declare(strict_types=1);

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

// Clean version routes
Route::middleware('checkInstallation')
    ->group(static function () {
        Route::get('/clean', [IndexController::class, '__invoke'])
            ->name('index.clean');
    });
