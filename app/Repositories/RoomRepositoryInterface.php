<?php

namespace App\Repositories;

interface RoomRepositoryInterface
{
    public function getAllRooms();
    public function createRoom(array $data);
    public function getRoomById($id);
}
