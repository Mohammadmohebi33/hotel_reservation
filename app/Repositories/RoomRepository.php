<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository implements RoomRepositoryInterface
{
    public function getAllRooms()
    {
        return Room::all();
    }

    public function createRoom(array $data)
    {
        return Room::create($data);
    }

    public function getRoomById($id)
    {
        return Room::find($id);
    }
}
