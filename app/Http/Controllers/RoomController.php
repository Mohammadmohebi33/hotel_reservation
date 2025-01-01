<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Models\Hotel;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        return Room::all();
    }


    public function storeRoom(StoreRoomRequest $request)
    {
        $hotel = Hotel::find($request->input('hotel_id'));
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
        $room = Room::create($request->validated());
        return response()->json(['message' => 'Room created successfully', 'room' => $room], 201);
    }


    public function getRoomById($hotelId)
    {
      $room = Room::query()->find($hotelId);
      return response()->json(["room" => $room] , 200);
    }
}
