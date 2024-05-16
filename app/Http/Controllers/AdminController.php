<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class AdminController extends Controller
{
    public function add_product()
    {
        return view('admin.add_product');
    }
 
    public function upload_product(Request $request)
    {
        $data = new Product;
        $data-> title = $request->title;
        $data-> description = $request->description;
        $data-> price = $request->price;
        $data-> quantity = $request->qty;
        $image = $request->image;
        if($image)
        {
            $imagename = time().'.'.$image->
                getClientOriginalExtension();

            $request->image->move('products', $imagename);
            
            $data->image = $imagename;
        }
        $data->save();
        
        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Added Successfully');
        
        return redirect()->back(); 
        
    }

    public function view_product()
    {
        $product = Product::paginate(3);
        return view('admin.view_product', compact('product'));
    }

    public function delete_product($id)
    {
        $data = Product::find($id);

        $image_path = public_path('products/'. $data->image);

        if(file_exists($image_path))
        {
            unlink($image_path);
        }
        
        $data->delete();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Deleted Successfully');

        return redirect()-> back(); 
    }

    public function update_product($id)
    {
        $data = Product::find($id);
        return view('admin.update_page', compact('data'));
    }

    public function edit_product(Request $request, $id)
    {
        $data = Product::find($id);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->quantity = $request->quantity;
        $image = $request->image;
        if($image)
        {
            $imagename = time().'.'.$image-> getClientOriginalExtension(); 
            
            $request->image->move('products', $imagename);
            $data->image = $imagename;
        }

        $data->save();
        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Updated Successfully');

        return redirect('/view_product');  
    }   
} 