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
        // $hotelImages = $this->fetchHotelImages($validatedData['hotel_id']);

        return response()->json($hotelDetails);
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

        $responseData = json_decode($response->getBody()->getContents(), true);
        return $this->filterAndTransformResults($responseData);
    }

    protected function filterAndTransformResults(array $results)
    {
        return array_values(array_filter(array_map(function ($item) {
            if (stripos($item['hotel_name'], 'Hotel') !== false) {
                return null;
            }

            $pricePerNight = round($item['composite_price_breakdown']['gross_amount_per_night']['value'] * 1.3);
            $hotelAddressLine = isset($item['hotel_address_line'])
                ? $item['hotel_address_line']
                : null;

            $nameWithoutPolicy = isset($item['name_without_policy'])
                ? $item['name_without_policy']
                : null;

            $hotelId = $item['hotel_id'];
            $roomKey = array_key_first($item['rooms']);

            $description = null;
            $facilities = [];
            $photos = [];
            
            if (isset($item['rooms'][$roomKey])) {
                $roomData = $item['rooms'][$roomKey];

                $photos = array_map(function ($photo) {
                    return [
                        'url' => $photo['url_original'],
                    ];
                }, $roomData['photos']);

                $facilities = array_map(function ($facility) {
                    return [
                        'name' => $facility['name'],
                        'facility_name' => $facility['facilitytype_name'],
                    ];
                }, $roomData['facilities']);

                $description = $roomData['description'];
            }

            return [
                'hotel_id' => $item['hotel_id'],
                'hotel_name' => $item['hotel_name'],
                'price_per_night' => $pricePerNight,
                'arrival_date' => $item['arrival_date'],
                'departure_date' => $item['departure_date'],
                'city' => $item['city'],
                'country' => $item['country_trans'],
                'hotel_address_line' => $hotelAddressLine,
                'address' => $item['address'],
                'short_name' => $nameWithoutPolicy,
                'source_url' => $item['url'],
                'description' => $description,
                'top_ufi_benefits' => $item['top_ufi_benefits'],
                'house_rules' => $item['booking_home']['house_rules'],
                'facilities' => $facilities,
                'photos' => $photos,
                'main_photo_url' => $photos[1]['url'],

            ];
        }, $results)));
    }

    //might remove dunno, it's kinda useless
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
