<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use Inertia\Inertia;
use App\Http\Controllers\CheckoutController;

Route::get('/', [ProductController::class, 'index'])->name('home');


Route::get('dashboard', function () {
    return Inertia::render('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::put('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');


Route::middleware('auth')
    ->prefix('checkout')
    ->as('checkout.')
    ->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'process'])->name('process');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
    });

Route::get('/payment/callback', [CheckoutController::class, 'paymentCallback'])->name('payment.callback');

require __DIR__ . '/settings.php';
