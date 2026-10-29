<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturnLogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');

    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('items', ItemController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');

        Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');

        Route::get('/returns', [ReturnLogController::class, 'index'])->name('returns.index');
        Route::get('/loans/{loan}/return', [ReturnLogController::class, 'create'])->name('returns.create');
        Route::post('/loans/{loan}/return', [ReturnLogController::class, 'store'])->name('returns.store');

        Route::get('/archive/{type}', [ArchiveController::class, 'index'])
            ->whereIn('type', ['items', 'categories', 'users'])->name('archive.index');
        Route::post('/archive/{type}/{id}/restore', [ArchiveController::class, 'restore'])
            ->whereIn('type', ['items', 'categories', 'users'])->name('archive.restore');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
        Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
    });
});

require __DIR__.'/auth.php';