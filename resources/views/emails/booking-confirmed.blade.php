<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
<h1>Booking Confirmation</h1>
{{--<p>Dear {{ auth()->user()->name }},</p>--}}
<p>Your booking details are as follows:</p>
<ul>
    <li>Room ID: {{ $booking['room_id'] }}</li>
    <li>From Date: {{ $booking['from_date'] }}</li>
    <li>Till Date: {{ $booking['till_date'] }}</li>
</ul>
<p>Thank you for booking with us!</p>
</body>
</html>
