<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ShopController;
use App\Models\Reservation;
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

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/thanks', [RegisteredUserController::class, 'thanks']);
Route::get('/test', [RegisteredUserController::class, 'test']);

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', [ShopController::class, 'index']);
    Route::get('/search', [ShopController::class, 'search']);
    Route::post('/favorite', [FavoriteController::class, 'favorite']);
    Route::post('/detail/{shop_id}', [ReservationController::class, 'detail'])->name('detail');
    Route::post('/reserve', [ReservationController::class, 'reserve']);
    Route::get('/done', [ReservationController::class, 'done']);
    Route::get('/detail/back', [ShopController::class, 'detailBack']);
    Route::get('/done/back', [ShopController::class, 'doneBack']);
});
