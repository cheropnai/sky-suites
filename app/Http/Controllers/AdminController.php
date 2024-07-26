<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Apartment;
use App\Models\Booking;

class AdminController extends Controller
{
    // Example method to get all users
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Example method to get all apartments
    public function getAllApartments()
    {
        $apartments = Apartment::all();
        return response()->json($apartments);
    }

    // Example method to get all bookings
    public function getAllBookings()
    {
        $bookings = Booking::all();
        return response()->json($bookings);
    }

    // Example method to delete a user
    public function deleteUser($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    // Example method to delete an apartment
    public function deleteApartment($id)
    {
        $apartment = Apartment::find($id);

        if ($apartment) {
            $apartment->delete();
            return response()->json(['message' => 'Apartment deleted successfully']);
        }

        return response()->json(['message' => 'Apartment not found'], 404);
    }

    // Example method to delete a booking
    public function deleteBooking($id)
    {
        $booking = Booking::find($id);

        if ($booking) {
            $booking->delete();
            return response()->json(['message' => 'Booking deleted successfully']);
        }

        return response()->json(['message' => 'Booking not found'], 404);
    }
}
