<!DOCTYPE html>
<html>

<head>
    @include('admin.css')
    <style>
        .nota-container {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            background: #fff;
        }

        .nota-header, .nota-footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .nota-header h2, .nota-footer p {
            margin: 0;
            padding: 0;
        }

        .nota-body {
            margin-bottom: 20px;
        }

        .nota-table {
            width: 100%;
            border-collapse: collapse;
        }

        .nota-table th, .nota-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .nota-table th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
        }
    </style>
</head>

<body>
    @include('admin.header')
    @include('admin.sidebar')

    <div class="page-content">
        <div class="nota-container">
            <div class="nota-header">
                <h2>Order Nota</h2>
                <p>Order ID: {{ $order->id }}</p>
                <p>Date: {{ $order->created_at->format('d M, Y') }}</p>
            </div>

            <div class="nota-body">
                <h3>Customer Details</h3>
                <p>Name: {{ $order->user->name }}</p>
                <p>Address: {{ $order->rec_address }}</p>
                <p>Phone: {{ $order->phone }}</p>

                <h3>Order Details</h3>
                <table class="nota-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($order->orderItems as $orderItem)
                        @php
                            $total = $orderItem->quantity * $orderItem->product->price;
                        @endphp
                        <tr>
                            <td>{{ $orderItem->product->title }}</td>
                            <td>{{ $orderItem->quantity }}</td>
                            <td>{{ $orderItem->product->price }}</td>
                            <td>{{ $total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="total">
                    <h3>Total: {{ $order->orderItems->sum(function($orderItem) {
        return $orderItem->quantity * $orderItem->product->price;
    }) }}</h3>
                </div>
            </div>

            <div class="nota-footer">
                <p>Thank you for your purchase!</p>
            </div>
        </div>
    </div>

    @include('admin.js')
</body>

</html>
