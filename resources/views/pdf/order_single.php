<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
</head>
<body>
    <h1>Order Details</h1>
    <p>Order ID: {{ $order->id }}</p>
    <p>Customer Name: {{ $order->user_name }}</p>
    <p>Email: {{ $order->email }}</p>
    <p>Phone: {{ $order->mobile }}</p>
    <p>Delivery Status: {{ $order->status }}</p>
    <p>Amount: ${{ number_format($order->grand_total, 2) }}</p>
    <p>Date Purchased: {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</p>
    <p>Payment Status: {{ $order->payment_status }}</p>
</body>
</html>
