<?php

namespace App\Http\Controllers;

use App\Mail\PaymentMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class TransactionController extends Controller
{

    private function getVivaAccessToken()
    {
        $clientId = env('VIVA_CLIENT_ID');
        $clientSecret = env('VIVA_CLIENT_SECRET');
        Log::info("Retrieving token");

        $response = Http::withBasicAuth($clientId, $clientSecret)->asForm()->post('https://accounts.vivapayments.com/connect/token', [
            'grant_type' => 'client_credentials',
        ]);

        if ($response->successful()) {
            Log::info($response);
            return $response->json()['access_token'];
        }

        return null;
    }

    private function getTransactionDetails($transactionId)
    {
        $accessToken = $this->getVivaAccessToken();
        Log::info("Retrieving details");

        if ($accessToken) {
            $response = Http::withToken($accessToken)->get("https://api.vivapayments.com/checkout/v2/transactions/{$transactionId}");

            if ($response->successful()) {
                return $response->json();
            }
        }

        return null;
    }

    public function sendTransactionEmail(Request $request)
    {
        Log::info("Here");
        $transactionId = $request->query('t', 'N/A');
        // $orderCode = $request->query('s', 'N/A');
        // $lang = $request->query('lang', 'N/A');
        // $eventId = $request->query('eventId', 'N/A');
        // $eci = $request->query('eci', 'N/A');
        Log::info($transactionId);

        // $transactionDetails = [
        //     'transactionId' => $transactionId,
        //     'orderCode' => $orderCode,
        //     'lang' => $lang,
        //     'eventId' => $eventId,
        //     'eci' => $eci,
        //     'email' => "ngethenan768@gmail.com",
        // ];
        $transactionDetails = $this->getTransactionDetails($transactionId);
        Log::info($transactionDetails);

        Mail::to("ngethenan768@gmail.com")->send(new PaymentMailable($transactionDetails));

        return response()->json(['message' => 'Email sent successfully']);
    }
}
