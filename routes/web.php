<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FollowupController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===========================
    // MARKETING ROUTES
    // ===========================
    Route::middleware('role:marketing')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'marketing'])->name('dashboard');

        // Prospek Customer
        Route::get('/prospek', [CustomerController::class, 'prospekIndex'])->name('prospek.index');
        Route::post('/prospek', [CustomerController::class, 'prospekStore'])->name('prospek.store');
        Route::put('/prospek/{customer}', [CustomerController::class, 'prospekUpdate'])->name('prospek.update');
        Route::delete('/prospek/{customer}', [CustomerController::class, 'prospekDestroy'])->name('prospek.destroy');

        // Status Perjalanan Customer
        Route::get('/status-perjalanan', [CustomerController::class, 'statusPerjalanan'])->name('status.index');
        Route::post('/status-perjalanan/{customer}/update', [FollowupController::class, 'store'])->name('status.update');

        // Customer Aktif
        Route::get('/customer-aktif', [CustomerController::class, 'customerAktif'])->name('aktif.index');

        // Documents
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::get('/customers/{customer}/download-zip', [DocumentController::class, 'downloadZip'])->name('customers.download-zip');
    });

    // ===========================
    // MANAGER ROUTES
    // ===========================
    Route::middleware('role:manager')->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'manager'])->name('dashboard');

        // API for charts
        Route::get('/api/prospek-chart', [DashboardController::class, 'prospekChartData'])->name('api.prospek-chart');

        // Laporan Marketing
        Route::get('/laporan-marketing', [DashboardController::class, 'laporanMarketing'])->name('laporan');

        // Drill-down (hidden pages)
        Route::get('/marketing/{user}/prospek', [CustomerController::class, 'managerProspek'])->name('marketing.prospek');
        Route::get('/marketing/{user}/aktif', [CustomerController::class, 'managerAktif'])->name('marketing.aktif');

        // ZIP Download
        Route::get('/customers/{customer}/download-zip', [DocumentController::class, 'downloadZip'])->name('customers.download-zip');

        // Manajemen User
        Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show']);
    });

});

require __DIR__.'/auth.php';
