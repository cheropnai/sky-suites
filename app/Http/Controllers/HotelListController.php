<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HotelListController extends Controller
{
    public function search(Request $request)
    {
        Log::info('Received Request:', $request->all());

        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'arrival_date' => 'required',
            'departure_date' => 'required',
        ]);

        $hotelList = $this->fetchHotelList($validatedData);
        return response()->json($hotelList);
    }

    protected function fetchHotelList(array $data)
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            'https://apidojo-booking-v1.p.rapidapi.com/properties/v2/list',
            [
                'headers' => [
                    'x-rapidapi-host' => 'apidojo-booking-v1.p.rapidapi.com',
                    'x-rapidapi-key' => '5dc5147df2mshd9eb87171990188p10832djsnda9f4ac31954',
                ],
                'query' => [
                    'arrival_date' => $data['arrival_date'],
                    'departure_date' => $data['departure_date'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'guest_qty' => 1,
                    'room_qty' => 3,
                    'search_type' => 'latlong',
                    'price_filter_currencycode' => 'EUR',
                    'languagecode' => 'en-us',
                    'units' => 'metric',
                    'categories_filter' => 'property_type::201',
                    'order_by' => 'class_descending',
                ],

            ]
        );

        $responseData = json_decode($response->getBody()->getContents(), true);

        return $this->filterAndTransformResults($responseData['result']);
    }

    protected function filterAndTransformResults(array $results)
    {
        $filteredResults = array_map(function ($item) {
            if (isset($item['hotel_id'])) {
                // $strikethroughPrice = isset($item['composite_price_breakdown']['strikethrough_amount_per_night'])
                //     ? round($item['composite_price_breakdown']['strikethrough_amount_per_night']['value'])
                //     : null;
                if (stripos($item['hotel_name'], 'Hotel') !== false) {
                    return null;
                }

                $gross_price_per_night = round($item['composite_price_breakdown']['gross_amount_per_night']['value'] * 1.3);

                $photoUrl = $item['main_photo_url'];
                $photoUrl = preg_replace('/\/hotel\/[^\/]+\//', '/hotel/max1024x768/', $photoUrl);
                $reviewScore = $item['review_score'] / 2;

                $label = isset($item['unit_configuration_label'])
                    ? preg_replace('/<b>.*?<\/b>:\s*/', '', $item['unit_configuration_label'])
                    : "";

                return [
                    'hotel_id' => $item['hotel_id'],
                    'price_per_night' => $gross_price_per_night,
                    'main_photo_url' => $photoUrl,
                    'hotel_name' => $item['hotel_name_trans'],
                    'city' => $item['city'],
                    'rating' => $reviewScore,
                    'label' => $label,
                ];
            }
            return null;
        }, $results);

        $filteredResults = array_values(array_filter($filteredResults));

        usort($filteredResults, function ($a, $b) {
            return $b['rating'] <=> $a['rating'];
        });

        return $filteredResults;
    }
}
