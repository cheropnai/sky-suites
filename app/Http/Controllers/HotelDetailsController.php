<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HotelDetailsController extends Controller
{
    public function show(Request $request)
    {
        $validatedData = $request->validate([
            'hotel_id' => 'required|numeric',
            'arrival_date' => 'required',
            'departure_date' => 'required',
        ]);

        $hotelDetails = $this->fetchHotelDetails($validatedData);
        $hotelImages = $this->fetchHotelImages($validatedData['hotel_id']);

        return response()->json([
            'details' => $hotelDetails,
            'images' => $hotelImages,
        ]);
    }

    protected function fetchHotelDetails(array $data)
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            'https://apidojo-booking-v1.p.rapidapi.com/properties/detail',
            [
                'headers' => [
                    'x-rapidapi-host' => 'apidojo-booking-v1.p.rapidapi.com',
                    'x-rapidapi-key' => '5dc5147df2mshd9eb87171990188p10832djsnda9f4ac31954',
                ],
                'query' => [
                    'hotel_id' => $data['hotel_id'],
                    'arrival_date' => $data['arrival_date'],
                    'departure_date' => $data['departure_date'],
                    'rec_guest_qty' => 1,
                    'rec_room_qty' => 3,
                    'languagecode' => 'en-us',
                    'currency_code' => 'EUR',
                    'units' => 'metric',
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function fetchHotelImages(int $hotelId)
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            'https://apidojo-booking-v1.p.rapidapi.com/properties/get-hotel-photos',
            [
                'headers' => [
                    'x-rapidapi-host' => 'apidojo-booking-v1.p.rapidapi.com',
                    'x-rapidapi-key' => '5dc5147df2mshd9eb87171990188p10832djsnda9f4ac31954',
                ],
                'query' => [
                    'hotel_ids' => $hotelId,
                    'languagecode' => 'en-us',
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}
