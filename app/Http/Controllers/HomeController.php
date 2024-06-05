<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function about()
    {
        return view('home.about');
    }

    public function shop()
    {
        $product = Product::all();
        return view('home.shop', compact('product'));
    }

    public function index()
    {
        return view('admin.index');
    }

    public function home()
    {
        $product = Product::all();
        $count = Auth::id() ? Cart::where('user_id', Auth::id())->count() : '';
        return view('home.index', compact('product', 'count'));
    }

    public function login_home()
    {
        $product = Product::all();
        $count = Auth::id() ? Cart::where('user_id', Auth::id())->count() : '';
        return view('home.index', compact('product', 'count'));
    }

    public function product_details($id)
    {
        $data = Product::find($id);
        $count = Auth::id() ? Cart::where('user_id', Auth::id())->count() : '';
        return view('home.product_details', compact('data', 'count'));
    }

    public function add_cart($id)
    {
        $product_id = $id;
        $user_id = Auth::user()->id;

        $cartItem = Cart::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($cartItem) {
            // Produk sudah ada di keranjang, update jumlahnya
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            // Produk belum ada di keranjang, buat entri baru
            $data = new Cart;
            $data->user_id = $user_id;
            $data->product_id = $product_id;
            $data->quantity = 1; // Default quantity
            $data->save();
        }

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Added to the Cart Successfully');
        return redirect()->back();
    }

    public function mycart()
    {
        if (Auth::id()) {
            $user_id = Auth::user()->id;
            $count = Cart::where('user_id', $user_id)->count();
            $cart = Cart::where('user_id', $user_id)->with('product')->get();
            $value = $cart->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            return view('home.mycart', compact('count', 'cart', 'value'));
        }
        return redirect('login');
    }

    public function delete_cart($id)
    {
        $cartItem = Cart::find($id);

        if (!$cartItem) {
            toastr()->error('Cart item not found');
            return redirect()->back();
        }

        $cartItem->delete();

        toastr()->success('Cart Item Deleted Successfully');

        return redirect()->back();
    }

    public function confirm_order(Request $request)
    {
        // Ambil data cart items dari form
        $cartItems = $request->input('cart');
        $rec_address = $request->input('address');
        $phone = $request->input('phone');

//        dd($cartItems);

        // Validasi data cart items
        if (empty($cartItems)) {
            Toastr::error('Your cart is empty');
            return redirect()->back();
        }

        // Buat order baru
        $order = new Order();
        $order->user_id = Auth::id();
        $order->status = 'In Progress'; // Perbaiki penamaan status
        $order->rec_address = $rec_address;
        $order->phone = $phone;
        $order->save();

        // Loop melalui data cart items untuk membuat order items dan menyesuaikan stok
        foreach ($cartItems as $itemId => $cartItem) {
            $productId = $cartItem['product_id'];
            $quantity = $cartItem['quantity'];

            // Validasi product ID
            if (empty($productId)) {
                Toastr::error('Invalid product ID');
                return redirect()->back();
            }

            $product = Product::find($productId);

            // Validasi product
            if (!$product) {
                Toastr::error('Product not found');
                return redirect()->back();
            }

            // Validasi stok
            if ($product->stock < $quantity) {
                Toastr::error('Not enough stock available for ' . $product->title);
                return redirect()->back();
            }

            // Buat order item baru
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $productId;

            $orderItem->quantity = $quantity;
            $orderItem->save();

            // Kurangi stok produk
            $product->stock -= $quantity;
            $product->save();

            // Hapus produk dari keranjang
            $cartItem = Cart::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->first();
            if ($cartItem) {
                $cartItem->delete();
            }
        }
    toastr()->success('Order confirmed successfully!');
        // Redirect ke halaman detail order atau halaman lain
        //
        return redirect()->route('order.detail', ['id' => $order->id]);
    }

    public function orderDetail($id)
    {
        $order = Order::with('orderItems.product')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            toastr()->error('Order not found');
            return redirect()->back();
        }

        return view('home.orderdetail', ['order' => $order]);
    }




    public function updateCart(Request $request)
    {
        $itemId = $request->input('itemId');
        $quantity = $request->input('quantity');

        $cartItem = Cart::find($itemId);
        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->quantity = $quantity;
            $cartItem->save();

            return response()->json(['success' => true, 'quantity' => $cartItem->quantity]);
        }

        return response()->json(['success' => false]);
    }

    public function editCartItem(Request $request, $id)
    {
        $cartItem = Cart::find($id);

        if (!$cartItem || $cartItem->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Cart item updated successfully.');
    }

    // public function showOrderDetail (){
    //     return view ()
    // }


}
