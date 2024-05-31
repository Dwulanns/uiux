<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'home']);

Route::get('/dashboard', [HomeController::class, 'login_home'])-> 
middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

route::get('admin/dashboard', [HomeController::class, 'index'])->
    middleware(['auth','admin']);

route::get('add_product', [AdminController::class, 'add_product'])->
    middleware(['auth','admin']);

route::post('upload_product', [AdminController::class, 'upload_product'])->
    middleware(['auth','admin']);

route::get('view_product', [AdminController::class, 'view_product'])->
    middleware(['auth','admin']);
    
route::get('delete_product/{id}', [AdminController::class, 'delete_product'])->
    middleware(['auth','admin']);

route::get('update_product/{id}', [AdminController::class, 'update_product'])->
    middleware(['auth','admin']);

route::post('edit_product/{id}', [AdminController::class, 'edit_product'])->
    middleware(['auth','admin']);
    
route::get('product_details/{id}', [HomeController::class, 'product_details']);
route::get('add_cart/{id}', [HomeController::class, 'add_cart'])->middleware(['auth', 'verified']);

 route::get('mycart', [HomeController::class, 'mycart'])->middleware(['auth', 'verified']);
// Route::get('delete-cart/{id}', [HomeController::class, 'delete_cart'])->middleware(['auth', 'verified']);
// Route::get('editCartItem/{id}', [HomeController::class, 'editCartItem'])->middleware(['auth', 'verified']);

Route::post('/update-cart', [HomeController::class, 'updateCart'])->name('update-cart');
Route::post('/confirm_order', [HomeController::class, 'confirm_order'])->name('confirm_order');
Route::get('/delete-cart/{id}', [HomeController::class, 'delete_cart'])->name('delete-cart');
Route::post('/edit-cart/{id}', [HomeController::class, 'editCartItem'])->name('edit-cart');


Route::post('confirm_order', [HomeController::class, 'confirm_order'])->middleware(['auth', 'verified']);

Route::get('view_order', [AdminController::class, 'view_order'])->middleware(['auth', 'admin']);

Route::get('on_the_way/{id}', [AdminController::class, 'on_the_way'])->middleware(['auth', 'admin']);
Route::get('delivered/{id}', [AdminController::class, 'delivered'])->middleware(['auth', 'admin']);

