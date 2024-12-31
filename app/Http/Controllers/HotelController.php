<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRequest;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HotelController extends Controller
{

    public function index(){
        return Hotel::all();
    }

    public function store(StoreHotelRequest $request)
    {
        $hotel = auth()->user()->hotels()->create($request->validated());
        return response()->json(['message' => 'Hotel created successfully', 'hotel' => $hotel], 201);
    }


    public function getHotelById($id)
    {
        try {
            $hotel = Hotel::with('user')->findOrFail($id);
            return response()->json(['hotel' => $hotel,],200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hotel not found',], 404);
        }
    }


    public function getAllRoomsByHotelId($hotelID)
    {
        $hotel = Hotel::with('rooms')->findOrFail($hotelID);

        return response()->json([
            'hotel' => $hotel->only(['id', 'name', 'location', 'rating']),
            'rooms' => $hotel->rooms,
        ], 200);
    }
}
