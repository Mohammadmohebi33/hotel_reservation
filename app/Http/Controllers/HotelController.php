<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRequest;
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
        return response()->json($hotels, 200);
    }

    public function store(StoreHotelRequest $request)
    {
        $hotel = $this->hotelRepository->createHotel($request->validated());
        return response()->json(['message' => 'Hotel created successfully', 'hotel' => $hotel], 201);
    }


    public function getHotelById($id)
    {
        try {
            $hotel = $this->hotelRepository->getHotelById($id);
            return response()->json(['hotel' => $hotel], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
    }


    public function getAllRoomsByHotelId($hotelID)
    {
        try {
            $data = $this->hotelRepository->getAllRoomsByHotelId($hotelID);
            return response()->json($data, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
    }
}
