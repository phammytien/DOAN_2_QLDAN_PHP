<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\JewelryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Định nghĩa route API đơn giản
Route::get('/hello', function () {
    return response()->json(['message' => 'Xin chào từ API!']);
});

// Review API routes
Route::get('/reviews/{jewelry_id}', [ReviewController::class, 'getReviews']);
Route::middleware('web')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store']);
});

Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
});

// Cart API routes
Route::middleware('web')->group(function () {
    Route::post('/cart/add', [CartController::class, 'add'])->name('api.cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('api.cart.remove');
    Route::post('/cart/update', [CartController::class, 'update'])->name('api.cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('api.cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('api.cart.count');
    Route::get('/cart/data', [CartController::class, 'getCartData'])->name('api.cart.data');
});

// Quản lý ảnh sản phẩm
Route::get('/jewelries/{id}/images', [JewelryController::class, 'getImages'])->name('jewelries.images');
Route::post('/jewelries/{id}/set-main-image', [JewelryController::class, 'setMainImage'])->name('jewelries.setMainImage');
Route::post('/jewelries/{id}/add-image', [JewelryController::class, 'addImage'])->name('jewelries.addImage');
Route::delete('/jewelries/image/{jewelryFileId}', [JewelryController::class, 'deleteImage'])->name('jewelries.deleteImage');
Route::post('/jewelries/image/{jewelryFileId}/update', [JewelryController::class, 'updateImage'])->name('jewelries.updateImage');

Route::post('/orders/bulk-approve', [OrderController::class, 'bulkApprove'])->name('api.orders.bulkApprove');