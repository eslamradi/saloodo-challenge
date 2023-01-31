<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BikersController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ParcelsController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->prefix('{guard}')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
})->whereIn('guard', Role::all());


Route::middleware('auth:biker,customer')->controller(ParcelsController::class)->prefix('parcel')->group(function () {
    Route::get('', 'list');
    Route::get('{id}', 'show');
});

Route::middleware('auth:customer')->controller(CustomersController::class)->prefix('customer')->group(function () {
    Route::post('parcel', 'createParcel');
});

Route::middleware('auth:biker')->controller(BikersController::class)->prefix('biker')->group(function () {
    Route::put('parcel/{id}/reserve', 'reserveParcel')->name('parcel.reserve');
    Route::put('parcel/{id}/pickup', 'pickupParcel')->name('parcel.pickup');
    Route::put('parcel/{id}/deliver', 'deliverParcel')->name('parcel.deliver');
});
