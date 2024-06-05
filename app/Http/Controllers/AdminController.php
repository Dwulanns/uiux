<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function add_product()
    {
        return view('admin.add_product');
    }

    public function upload_product(Request $request)
    {
        // Create a new Product instance
        $product = new Product();
        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->quantity = $request->input('quantity');
        $product->stock = $request->input('stock');

        // Handle the product image upload
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }

        // Save the product to the database
        $product->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Product added successfully!');
    }

    public function view_product()
    {
        $product = Product::paginate(10);
        return view('admin.view_product', compact('product'));
    }

    public function delete_product($id)
    {
        $data = Product::find($id);

        $image_path = public_path('products/'. $data->image);

        if (file_exists($image_path)) {
            unlink($image_path);
        }

        $data->delete();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Deleted Successfully');

        return redirect()->back(); 
    }

    public function update_product($id)
    {
        $data = Product::find($id);
        return view('admin.update_page', compact('data'));
    }

    public function edit_product(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->quantity = $request->input('quantity');
        $product->stock = $request->input('stock');

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && file_exists(public_path('products/'.$product->image))) {
                unlink(public_path('products/'.$product->image));
            }

            // Upload new image
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Updated Successfully');

        return redirect('/view_product');
    }

//     public function view_order()
// {
//     // Fetch all orders and group by user_id
//     $orders = Order::with('product')
//         ->get()
//         ->groupBy('user_id'); // Group orders by user_id

//     return view('admin.order', compact('orders'));
// }
public function view_order()
{
    $orders = Order::with(['user', 'orderItems.product'])->get()->groupBy('user_id');
    return view('admin.order', compact('orders'));
}



    public function on_the_way($id)
    {
        $order = Order::find($id);
        $order->status = 'On the way';
        $order->save();
        return redirect('/view_order');
    }

    public function delivered($id)
    {
        $order = Order::find($id);
        $order->status = 'Delivered';
        $order->save();
        return redirect('/view_order');
    }
}
