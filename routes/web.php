<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EtalaseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/etalase', [EtalaseController::class, 'index'])->name('etalase');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/create-transaction', [CheckoutController::class, 'createTransaction'])->name('checkout.createTransaction');
    Route::get('/order', [OrderController::class, 'index'])->name('order');

    Route::post('/checkout/add-quantity', [CheckoutController::class, 'addQuantity'])->name('checkout.addQuantity');
    Route::post('/checkout/reduce-quantity', [CheckoutController::class, 'reduceQuantity'])->name('checkout.reduceQuantity');
});

require __DIR__.'/auth.php';
