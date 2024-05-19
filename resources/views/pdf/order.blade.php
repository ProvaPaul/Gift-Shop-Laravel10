
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
</head>
<body>
    <table class="table table-hover text-nowrap">
        <thead>
            <tr>
                <th width="60">Order#</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Phone</th>
                <th> Delivery Status</th>
                <th >Amount</th>
                <th >Date Purchased</th>
                <th >Payment Status</th>
    
            </tr>
        </thead>
        <tbody>
            @if($orders->isNotEmpty())
            @foreach($orders as $order)
            <tr>
                <td><a href="{{ route('orders.detail',[$order->id]) }}">{{$order->id}}</a></td>
                <td>{{$order->name}}</td>
                <td>{{$order->email}}</td>
                <td>{{$order->mobile}}</td>
                <td>
                    @if ($order->status =='pending')
                    <span class="badge bg-danger">Pending</span>
                    @elseif ($order->status =='shipped')
                    <span class="badge bg-info">Shipped</span>
                    @elseif ($order->status =='delivered')
                    <span class="badge bg-success">Delivered</span>
                    @else
                    <span class="badge bg-danger">Cancelled</span>
                    @endif
                </td>
                <td>${{ number_format($order->grand_total,2)}}</td>
    
                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M,Y') }}</td>
                <td>
                    @if ($order->status =='delivered')
                    <span class="badge bg-success">Paid</span>
                    @elseif ($order->payment_status =='not paid')
                    <span class="badge bg-danger">Not Paid</span>
                
                    @else
                    <span class="badge bg-success">Paid</span>
                    @endif
                    </td>
            
    
            </tr>
            @endforeach
    
            @else
            <tr>
                <td colspan="5">Records Not Found</td>
    </tr>
            @endif
            
        </tbody>
    </table>
</body>
</html>



