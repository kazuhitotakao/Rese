<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\RegisteredOwnerController;
use App\Http\Controllers\RegisteredShopController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
Route::get('/thanks', [RegisteredUserController::class, 'thanks']);
Route::get('/', [ShopController::class, 'index']);
Route::get('/search', [ShopController::class, 'search']);

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/detail/{shop_id}', [ShopController::class, 'detail'])->name('detail');
    Route::get('/my-page', [ShopController::class, 'myPage']);
    Route::post('/reserve', [ReservationController::class, 'reserve']);
    Route::post('/reserve/change', [ReservationController::class, 'reserveChange']);
    Route::post('/cancel', [ReservationController::class, 'cancel']);
    Route::post('/done-back', [ReservationController::class, 'doneBack']);
    Route::post('/favorite', [FavoriteController::class, 'favorite']);
    Route::get('/admin-page', [RegisteredOwnerController::class, 'adminPage']);
    Route::get('/register/owner', [RegisteredOwnerController::class, 'ownerRegister']);
    Route::post('/register/owner', [RegisteredOwnerController::class, 'ownerRegister']);
    Route::get('/owner/search', [RegisteredOwnerController::class, 'search']);
    Route::get('/owner-page', [RegisteredShopController::class, 'ownerPage']);
    Route::post('/shop/saveOrUpdate', [RegisteredShopController::class, 'saveOrUpdate']);
    Route::post('/image-upload', [ImageUploadController::class, 'imageUpload']);
});

Route::get('/test', [TestController::class, 'test']);