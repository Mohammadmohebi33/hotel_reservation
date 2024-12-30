<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{

    public function index(){
        return Hotel::all();
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'rating' => 'required|numeric|between:0,5',
        ]);

        $hotel = Hotel::create([
            'name' => $request->input('name'),
            'location' => $request->input('location'),
            'rating' => $request->input('rating'),
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Hotel created successfully', 'hotel' => $hotel], 201);
    }


    public function getHotelById($id)
    {
        $hotel = Hotel::with('user')->find($id);

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        return response()->json(['hotel' => $hotel], 200);
    }


    public function getAllRoomsByHotelId($hotelID)
    {
        $hotel = Hotel::with('rooms')->find($hotelID);

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        return response()->json([
            'hotel' => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'location' => $hotel->location,
                'rating' => $hotel->rating,
            ],
            'rooms' => $hotel->rooms,
        ], 200);
    }
}
