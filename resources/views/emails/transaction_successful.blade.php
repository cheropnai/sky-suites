<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Successful</title>
</head>

<body>
    <h1>Transaction Made</h1>
    <p>Amount: {{ $transactionDetails['amount'] }}</p>
    <p>Order Code: {{ $transactionDetails['orderCode'] }}</p>
    <p>Card Issuing Bank: {{ $transactionDetails['cardIssuingBank'] }}</p>
    <p>Full Name: {{ $transactionDetails['fullName'] }}</p>
    <p>Email: {{ $transactionDetails['email'] }}</p>
    <p>You're Welcome!</p>
</body>

</html>