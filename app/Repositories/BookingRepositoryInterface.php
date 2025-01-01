<?php

namespace App\Repositories;

interface BookingRepositoryInterface
{
    public function getAllBookings($user);
    public function createBooking(array $data);
    public function findBookingById($id);
    public function cancelBooking($booking);
    public function checkConflictingBookings(array $data): bool;
}
