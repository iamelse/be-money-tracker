<?php

use App\Http\Controllers\Web\App\AccountController;
use App\Http\Controllers\Web\App\DashboardController;
use App\Http\Controllers\Web\App\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('costum.auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('web.app.dashboard');

    Route::get('/accounts', [AccountController::class, 'index'])->name('web.app.accounts.index');
    Route::get('/accounts/create', [AccountController::class, 'create'])->name('web.app.accounts.create');
    Route::post('/accounts', [AccountController::class, 'store'])->name('web.app.accounts.store');
    Route::get('/accounts/{account}/edit', [AccountController::class, 'edit'])->name('web.app.accounts.edit');
    Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('web.app.accounts.update');
    Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('web.app.accounts.destroy');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('web.app.transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('web.app.transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('web.app.transactions.store');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('web.app.transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('web.app.transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('web.app.transactions.destroy');

});