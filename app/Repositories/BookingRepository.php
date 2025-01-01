<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository implements BookingRepositoryInterface
{
    public function getAllBookings($user)
    {
        if ($user->isAdmin()) {
            return Booking::with('room', 'user')->get();
        } else {
            return Booking::with('room', 'user')->where('user_id', $user->id)->get();
        }
    }

    public function createBooking(array $data)
    {
        return Booking::create($data);
    }

    public function findBookingById($id)
    {
        return Booking::find($id);
    }

    public function cancelBooking($booking)
    {
        $booking->canceled = true;
        $booking->save();
        return $booking;
    }

    public function checkConflictingBookings(array $data): bool
    {
        return Booking::where('room_id', $data['room_id'])
            ->where('canceled', false)
            ->where(function ($query) use ($data) {
                $query->whereBetween('from_date', [$data['from_date'], $data['till_date']])
                    ->orWhereBetween('till_date', [$data['from_date'], $data['till_date']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('from_date', '<=', $data['from_date'])
                            ->where('till_date', '>=', $data['till_date']);
                    });
            })
            ->exists();
    }
}
