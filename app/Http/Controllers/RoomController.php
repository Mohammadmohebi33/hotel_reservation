<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function all()
    {
        return Room::all();
    }


    public function storeRoom(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'hotel_id' => 'required',
        ]);

        $hotel = Hotel::find($request->input('hotel_id'));
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        $room = Room::create([
            'hotel_id' => $request->input('hotel_id'),
            'size' => $request->input('size'),
            'price' => $request->input('price'),
        ]);

        return response()->json(['message' => 'Room created successfully', 'room' => $room], 201);
    }


    public function getRoomById($hotelId)
    {
      $room = Room::query()->find($hotelId);
      return response()->json(["room" => $room] , 200);
    }
}
