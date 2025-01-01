<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Models\Hotel;
use App\Repositories\RoomRepositoryInterface;

class RoomController extends Controller
{

    protected $roomRepository;

    public function __construct(RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }


    public function index()
    {
        $rooms = $this->roomRepository->getAllRooms();
        return response()->json($rooms, 200);
    }


    public function storeRoom(StoreRoomRequest $request)
    {
        $hotel = Hotel::find($request->input('hotel_id'));
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        $room = $this->roomRepository->createRoom($request->validated());
        return response()->json(['message' => 'Room created successfully', 'room' => $room], 201);
    }


    public function getRoomById($hotelId)
    {
        $room = $this->roomRepository->getRoomById($hotelId);

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }
        return response()->json(['room' => $room], 200);
    }
}
