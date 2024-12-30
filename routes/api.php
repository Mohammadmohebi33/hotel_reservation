<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
});

Route::middleware("auth:api")->prefix("/hotel")->group(function (){
    Route::get("/all" , [HotelController::class,  "index"]);
    Route::post("/store" , [HotelController::class , "store"]);
    Route::get("/{id}" , [HotelController::class, "getHotelById"]);
    Route::get("/{id}/rooms" , [HotelController::class , "getAllRoomsByHotelId"]);
});


Route::prefix('rooms')->middleware("auth:api")->group(function () {
    Route::get("/all" , [RoomController::class , "all"]);
    Route::post('/store', [RoomController::class, 'storeRoom']);
    Route::get('/{id}', [RoomController::class, 'getRoomById']);
});

Route::post('/booking', [BookingController::class, 'storeBooking'])->middleware("auth:api");
Route::patch('/booking/{id}/cancel', [BookingController::class, 'cancelBooking']);
