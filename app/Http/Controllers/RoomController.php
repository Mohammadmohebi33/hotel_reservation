<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Resources\RoomResource;
use App\Repositories\HotelRepositoryInterface;
use App\Repositories\RoomRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class RoomController extends Controller
{

    protected $roomRepository;
    protected $hotelRepository;

    public function __construct(RoomRepositoryInterface $roomRepository, HotelRepositoryInterface $hotelRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->hotelRepository = $hotelRepository;
    }


    public function index()
    {
        $rooms = $this->roomRepository->getAllRooms();
        return RoomResource::collection($rooms);
    }


    public function storeRoom(StoreRoomRequest $request)
    {
        $hotel = $this->hotelRepository->findHotelByID($request->input('hotel_id'));

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
        // Check if the user is authorized to add a room
        if (!Gate::allows('createRoom', $hotel)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $room = $this->roomRepository->createRoom($request->validated());
        return response()->json(['message' => 'Room created successfully', 'room' => new RoomResource($room)], 201);
    }


    public function getRoomById($hotelId)
    {
        $room = $this->roomRepository->getRoomById($hotelId);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }
        return response()->json(['room' => new RoomResource($room)], 200);
    }
}
