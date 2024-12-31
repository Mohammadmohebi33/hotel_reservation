<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function all()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $bookings = Booking::with('room', 'user')->get();
        } else {
            $bookings = Booking::with('room', 'user')->where('user_id', $user->id)->get();
        }

        return response()->json([
            'message' => 'Bookings retrieved successfully',
            'bookings' => $bookings,
        ], 200);
    }


    public function storeBooking(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'num_person' => 'required|integer|min:1',
            'from_date' => 'required|date|after_or_equal:today',
            'till_date' => 'required|date|after:from_date',
        ]);

        $conflictingBookings = Booking::where('room_id', $request->room_id)
            ->where('canceled', false)
            ->where(function ($query) use ($request) {
                $query->whereBetween('from_date', [$request->from_date, $request->till_date])
                    ->orWhereBetween('till_date', [$request->from_date, $request->till_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('from_date', '<=', $request->from_date)
                            ->where('till_date', '>=', $request->till_date);
                    });
            })
            ->exists();

        if ($conflictingBookings) {
            return response()->json(['message' => 'Room is already booked for the selected dates'], 400);
        }


        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'num_person' => $request->num_person,
            'from_date' => $request->from_date,
            'till_date' => $request->till_date,
            'canceled' => false,
        ]);

        return response()->json(['message' => 'Room booked successfully', 'booking' => $booking], 201);
    }


    public function cancelBooking($bookingId)
    {
        $booking = Booking::find($bookingId);


        if ($booking->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->canceled) {
            return response()->json(['message' => 'Booking is already canceled'], 400);
        }

        $booking->canceled = true;
        $booking->save();

        return response()->json(['message' => 'Booking canceled successfully', 'booking' => $booking], 200);
    }
}
