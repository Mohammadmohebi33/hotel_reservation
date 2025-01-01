<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Repositories\BookingRepositoryInterface;

class BookingController extends Controller
{

    protected $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function index()
    {
        $user = auth()->user();
        $bookings = $this->bookingRepository->getAllBookings($user);

        return response()->json([
            'message' => 'Bookings retrieved successfully',
            'bookings' => $bookings,
        ], 200);
    }


    public function storeBooking(StoreBookingRequest $request)
    {
        $data = [
            'room_id' => $request->room_id,
            'from_date' => $request->from_date,
            'till_date' => $request->till_date,
        ];

        $conflictingBookings = $this->bookingRepository->checkConflictingBookings($data);

        if ($conflictingBookings) {
            return response()->json(['message' => 'Room is already booked for the selected dates'], 400);
        }

        $booking = $this->bookingRepository->createBooking([
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
        $booking = $this->bookingRepository->findBookingById($bookingId);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->canceled) {
            return response()->json(['message' => 'Booking is already canceled'], 400);
        }

        $updatedBooking = $this->bookingRepository->cancelBooking($booking);
        return response()->json(['message' => 'Booking canceled successfully', 'booking' => $updatedBooking], 200);
    }
}
