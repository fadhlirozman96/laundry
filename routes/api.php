<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// POS API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pos/products', [POSController::class, 'getProducts']);
    Route::get('/pos/products/{id}', [POSController::class, 'getProduct']);
    Route::post('/pos/checkout', [POSController::class, 'checkout']);
    Route::get('/pos/orders', [POSController::class, 'getOrders']);
    Route::get('/pos/orders/{id}', [POSController::class, 'getOrder']);
});

// POS Routes without auth for web (you can add auth middleware if needed)
Route::prefix('pos')->group(function () {
    Route::get('/products', [POSController::class, 'getProducts'])->name('pos.products');
    Route::get('/products/{id}', [POSController::class, 'getProduct'])->name('pos.product');
    Route::post('/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    Route::get('/orders', [POSController::class, 'getOrders'])->name('pos.orders');
    Route::get('/orders/{id}', [POSController::class, 'getOrder'])->name('pos.order');
});
