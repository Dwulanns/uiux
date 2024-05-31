<!DOCTYPE html>
<html>

<head>
    @include('home.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style type="text/css">
        .div_deg {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 60px;
        }

        table {
            border: 2px solid black;
            text-align: center;
            width: 800px;
        }

        th {
            border: 2px solid black;
            text-align: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            background-color: black;
        }

        td {
            border: 1px solid skyblue;
        }

        .cart_value {
            text-align: center;
            margin-bottom: 70px;
            padding: 18px;
        }

        .order_deg {
            padding-right: 100px;
            margin-top: -10px;
        }

        label {
            display: inline-block;
            width: 150px;
        }

        .div_gap {
            padding: 20px;
        }

        .quantity-input {
            width: 40px;
        }
    </style>
</head>

<body>
    <div class="hero_area">
        @include('home.header')
    </div>

    <div class="div_deg">
        <div class="order_deg">
            <form action="{{ url('confirm_order') }}" method="POST">
                @csrf
                <div class="div_gap">
                    <label>Receiver Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}">
                </div>
                <div class="div_gap">
                    <label>Receiver Address</label>
                    <textarea name="address">{{ Auth::user()->address }}</textarea>
                </div>
                <div class="div_gap">
                    <label>Receiver Phone</label>
                    <input type="text" name="phone" value="{{ Auth::user()->phone }}">
                </div>
                <div class="div_gap">
                    <input class="btn btn-primary" type="submit" value="Place Order">
                </div>
            </form>
        </div>
    </div>

    <table>
        <tr>
            <th>Select</th>
            <th>Product Title</th>
            <th>Price</th>
            <th>Image</th>
            <th>Quantity</th>
            <th>Edit</th>
        </tr>
        <?php $value = 0; ?>
        @foreach($cart as $item)
        <tr data-title="{{ $item->product->title }}">
            <td>
                <input type="checkbox" class="product-checkbox" data-price="{{ $item->product->price }}" data-id="{{ $item->product->id }}">
            </td>
            <td>{{ $item->product->title }}</td>
            <td>{{ $item->product->price }}</td>
            <td>
                <img width="150" src="/products/{{ $item->product->image }}">
            </td>
            <td>
                <input class="quantity-input" type="number" min="1" value="{{ $item->quantity }}" data-price="{{ $item->product->price }}" name="quantity[{{ $item->product->id }}]">
            </td>
            <td>
                <a class="btn btn-danger" href="{{ url('delete-cart', $item->id) }}">Remove</a>
                <button class="btn btn-warning update-btn" data-id="{{ $item->id }}">Update</button>
            </td>
        </tr>
        <?php $value += $item->product->price * $item->quantity; ?>
        @endforeach
    </table>

    <div class="cart_value">
        <h3>Total Value of Cart is: Rp<span id="cartTotal">{{ $value }}</span>k</h3>
    </div>

    @include('home.footer')

    <script>
        $(document).ready(function() {
            $('.update-btn').click(function(e) {
                e.preventDefault();
                var itemId = $(this).data('id');
                var quantity = $(this).closest('tr').find('.quantity-input').val();
                var $this = $(this); // Save the current button
                $.ajax({
                    url: '{{ url("update-cart") }}',
                    method: 'POST',
                    data: {
                        itemId: itemId,
                        quantity: quantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $this.closest('tr').find('.quantity-input').val(response.quantity);
                            updateTotalValue();
                            toastr.success('Quantity updated successfully');
                        } else {
                            toastr.error('Failed to update quantity');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        toastr.error('An error occurred while updating quantity');
                    }
                });
            });

            function updateTotalValue() {
                let totalValue = 0;
                $('.product-checkbox:checked').each(function() {
                    const price = parseInt($(this).data('price'));
                    const quantity = parseInt($(this).closest('tr').find('.quantity-input').val());
                    totalValue += price * quantity;
                });
                $('#cartTotal').text(totalValue);
            }

            $('.product-checkbox').change(updateTotalValue);
            $('.quantity-input').on('input', updateTotalValue);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>

</html>
