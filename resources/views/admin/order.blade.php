<!DOCTYPE html>
<html>

<head>
    @include('admin.css')
    <style>
        table {
            border: 2px solid skyblue;
            text-align: center;
        }

        th {
            background-color: skyblue;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .table_center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        td {
            color: white;
            padding: 10px;
            border: 1px solid skyblue;
        }
    </style>
</head>

<body>
    @include('admin.header')
    @include('admin.sidebar')

    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="table_center">
                    @if($orders->isEmpty())
                    <p>No orders available.</p>
                    @else
                    <table>
                        <tr>
                            <th>Customer Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Product Title</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Change Status</th>
                        </tr>
                        @foreach($orders as $userId => $userOrders)
                            @php
                                $totalItems = $userOrders->sum(fn($order) => $order->orderItems->count());
                                $itemCounter = 0;
                            @endphp
                            @foreach($userOrders as $order)
                                @foreach($order->orderItems as $orderItem)
                                    <tr>
                                        @if($itemCounter == 0)
                                            <td rowspan="{{ $totalItems }}">{{ $order->user->name }}</td>
                                            <td rowspan="{{ $totalItems }}">{{ $order->rec_address }}</td>
                                            <td rowspan="{{ $totalItems }}">{{ $order->phone }}</td>
                                        @endif
                                        <td>{{ $orderItem->product->title ?? 'Product not found' }}</td>
                                        <td>{{ $orderItem->quantity }}</td>
                                        <td>{{ $orderItem->product->price ?? '-' }}</td>
                                        <td>
                                            @if($orderItem->product)
                                                <img height="120" width="120" src="{{ asset('products/' . $orderItem->product->image) }}" alt="Product Image">
                                            @else
                                                Image not available
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->status == 'in progress')
                                                <span style="color: red">{{ $order->status }}</span>
                                            @elseif($order->status == 'on the way')
                                                <span style="color: orange">{{ $order->status }}</span>
                                            @else
                                                <span style="color: skyblue">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-primary" href="{{ url('on_the_way', $order->id) }}">On the way</a>
                                            <a class="btn btn-success" href="{{ url('delivered', $order->id) }}">Delivered</a>
                                        </td>
                                    </tr>
                                    @php $itemCounter++; @endphp
                                @endforeach
                            @endforeach
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('admin.js')
</body>

</html>
