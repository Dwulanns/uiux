<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public function orderDetail()
{
    return $this->belongsTo(OrderDetail::class, 'id_order_detail');
}

public function user()
{
    return $this->belongsTo(User::class, 'id_user');
}

}
