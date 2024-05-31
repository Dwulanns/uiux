<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function show($id)
    {
        // Ambil data order detail dari database
        $orderDetail = OrderDetail::find($id);

        // Kirim data ke view
        return view('order_detail', ['data' => $orderDetail]);
    }
}
