<?php

return [
    'client_id' => env('VIVA_CLIENT_ID'),
    'client_secret' => env('VIVA_CLIENT_SECRET'),
    'api_url' => env('VIVA_API_URL', 'https://api.vivapayments.com'),
    'payment_url' => env('VIVA_PAYMENT_URL', 'https://vivapayments.com/checkout'),
];
