<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
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
    $user_id = Auth::user()->id;
    $cart = Cart::where('user_id', $user_id)->get();

    foreach ($cart as $cartItem) {
        $order = new Order;
        $order->user_id = $user_id;
        $order->name = $request->name;
        $order->rec_address = $request->address;  // Use the new column name
        $order->phone = $request->phone;
        $order->product_id = $cartItem->product_id;
        $order->save();
    }

    Cart::where('user_id', $user_id)->delete();

    toastr()->success('Order confirmed successfully');
    return redirect()->back();
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
}
