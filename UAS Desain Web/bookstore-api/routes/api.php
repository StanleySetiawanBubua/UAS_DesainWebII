<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShopController;



Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::group(['middleware' => 'cors'], function () {
        Route::post('logout',[AuthController::class,'logout']);
        Route::post('shipping', [ShopController::class, 'shipping']);
        Route::post('services', [ShopController::class, 'services']);
        Route::post('payment', [ShopController::class, 'payment']);
        Route::get('my-order',[ShopController::class, 'myOrder']);
        
    });
    Route::get('books', [BaookController::class, 'index']);
    Route::get('book/{id}', [BookController::class, 'view'])->where('id', '[0-9]+');
    Route::get('books/slug/{slug}', [BookController::class, 'slug']);
    Route::get('categories/random/{count}', [CategoryController::class, 'random']);
    Route::get('books/top/{count}', [BookController::class, 'top']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/slug/{slug}', [CategoryController::class, 'slug']);
    Route::get('books/search/{keyword}', [BookController::class, 'search']);
    Route::get('provinces', [ShopController::class, 'provinces']);
    Route::get('cities', [ShopController::class, 'cities']);
    Route::get('couriers', [ShopController::class, 'couriers']);
});
