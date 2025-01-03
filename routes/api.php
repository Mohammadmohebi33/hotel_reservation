<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
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

Route::prefix("/hotel")->group(function (){
    Route::get("/all" , [HotelController::class,  "index"]);
    Route::post("/store" , [HotelController::class , "store"])->middleware("auth:api");
    Route::get("/{id}" , [HotelController::class, "getHotelById"]);
    Route::get("/{id}/rooms" , [HotelController::class , "getAllRoomsByHotelId"]);
});


Route::prefix('rooms')->group(function () {
    Route::get("/all" , [RoomController::class , "index"]);
    Route::post('/store', [RoomController::class, 'storeRoom'])->middleware("auth:api");
    Route::get('/{id}', [RoomController::class, 'getRoomById']);
});


Route::prefix("booking")->middleware("auth:api")->group(function (){
    Route::get("/all" , [BookingController::class, "index"]);
    Route::post('/', [BookingController::class, 'storeBooking']);
    Route::patch('/{id}/cancel', [BookingController::class, 'cancelBooking']);
});
