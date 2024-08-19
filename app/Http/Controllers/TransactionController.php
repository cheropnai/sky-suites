<?php

namespace App\Http\Controllers;

use App\Mail\PaymentMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class TransactionController extends Controller
{
    public function sendTransactionEmail(Request $request)
    {
        Log::info("Here");
        $transactionId = $request->query('t', 'N/A');
        $orderCode = $request->query('s', 'N/A');
        $lang = $request->query('lang', 'N/A');
        $eventId = $request->query('eventId', 'N/A');
        $eci = $request->query('eci', 'N/A');
        Log::info($transactionId);

        $transactionDetails = [
            'transactionId' => $transactionId,
            'orderCode' => $orderCode,
            'lang' => $lang,
            'eventId' => $eventId,
            'eci' => $eci,
            'email' => "ngethenan768@gmail.com",
        ];

        Mail::to($transactionDetails['email'])->send(new PaymentMailable($transactionDetails));

        return response()->json(['message' => 'Email sent successfully']);
    }
}
