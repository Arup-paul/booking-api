<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\Public\PropertyController as PublicPropertyController;
use App\Http\Controllers\Public\ApartmentController;
use App\Http\Controllers\Public\PropertySearchController;
use App\Http\Controllers\User\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function() {
    Route::get('owner/properties',
        [PropertyController::class, 'index']);
    Route::post('owner/properties',
        [PropertyController::class, 'store']);
    Route::get('user/bookings',
        [BookingController::class, 'index']);
});
Route::get('search',PropertySearchController::class);
Route::get('properties/{property}',PublicPropertyController::class);
Route::get('apartments/{apartment}',ApartmentController::class);

Route::post('auth/register', RegisterController::class);
