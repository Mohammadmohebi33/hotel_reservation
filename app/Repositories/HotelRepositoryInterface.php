<?php

namespace App\Repositories;


interface HotelRepositoryInterface
{
    public function getAllHotels();
    public function createHotel(array $data);
    public function getHotelById($id);
    public function getAllRoomsByHotelId($hotelID);
    public function findHotelByID($id);
}
