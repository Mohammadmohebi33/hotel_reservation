<?php

namespace App\Repositories;

use App\Models\Hotel;

class HotelRepository implements HotelRepositoryInterface
{
    public function getAllHotels()
    {
        return Hotel::query()->paginate(10);
    }

    public function createHotel(array $data)
    {
        return auth()->user()->hotels()->create($data);
    }

    public function getHotelById($id)
    {
        return Hotel::with('user')->findOrFail($id);
    }

    public function getAllRoomsByHotelId($hotelID)
    {
        $hotel = Hotel::with('rooms')->findOrFail($hotelID);
        return $hotel->rooms;
    }

    public function findHotelByID($id){
        return Hotel::query()->find($id);
    }

}
