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

    <!-- Sidebar Navigation -->
    @include('admin.sidebar')

    <!-- Page Content -->
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
                            <th>Price</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Change Status</th>
                        </tr>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->rec_address }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>{{ $order->product->title }}</td>
                            <td>{{ $order->product->price }}</td>
                            <td>
                                <img width="150" src="products/{{ $order->product->image }}">
                            </td>
                            <td>
                                @if($order->status == 'in progress')
                                <span style="color: red">{{$order->status}}</span>
                                @elseif($order->status == 'on the way')
                                @else
                                <span style="color: skyblue">{{$order->status}}</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-primary" href="{{url('on_the_way', $order->id)}}">On the way</a>
                                <a class="btn btn-success" href="{{url('delivered', $order->id)}}">Delivered</a>
                            </td>                            
                        </tr>
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript files-->
    @include('admin.js')
</body>

</html>
