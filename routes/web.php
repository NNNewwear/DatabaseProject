<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('cards', CardController::class)->only(['index','store','destroy']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('orders', OrderController::class)->only(['index','show','store']);
    Route::resource('orderdetails', OrderDetailController::class)->only(['index','destroy']);
    Route::resource('products', ProductController::class);
    Route::resource('wishlist', WishlistController::class)->only(['index','store','destroy']);
});

require __DIR__.'/auth.php';
