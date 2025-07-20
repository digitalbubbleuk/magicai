<?php

use Illuminate\Support\Facades\Route;

// Simple test route
Route::get('/test-route', function () {
    return 'Test route works!';
})->name('test.route');

// Test admin route
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/test-admin-route', function () {
        return 'Admin test route works!';
    })->name('test');
});
