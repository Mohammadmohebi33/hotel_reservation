<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRequest;
use App\Http\Resources\HotelResource;
use App\Http\Resources\RoomResource;
use App\Repositories\HotelRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HotelController extends Controller
{

    protected $hotelRepository;


    public function __construct(HotelRepositoryInterface $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }


    public function index(){
        $hotels = $this->hotelRepository->getAllHotels();
        return HotelResource::collection($hotels);
    }

    public function store(StoreHotelRequest $request)
    {
        $hotel = $this->hotelRepository->createHotel($request->validated());
        return response()->json(['message' => 'Hotel created successfully', 'hotel' => new HotelResource($hotel)], 201);
    }


    public function getHotelById($id)
    {
        try {
            $hotel = $this->hotelRepository->getHotelById($id);
            return response()->json(['hotel' => new HotelResource($hotel)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
    }


    public function getAllRoomsByHotelId($hotelID)
    {
        try {
            $rooms = $this->hotelRepository->getAllRoomsByHotelId($hotelID);
            return RoomResource::collection($rooms);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
    }
}
