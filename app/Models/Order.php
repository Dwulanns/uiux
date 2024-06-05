<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'quantity', 'status', 'rec_address', 'phone'
    ];
//    public function user()
//    {
//     return $this->hasOne('App\Models\User', 'id', 'user_id');
//    }

//    public function product()
//    {
//     return $this->hasOne('App\Models\Product', 'id', 'product_id');
//    }

public function user()
{
    return $this->belongsTo(User::class);
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

   public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

public function orderDetails()
{
    return $this->hasMany(OrderDetail::class);
}

public function product()
{
    return $this->belongsTo(Product::class);
}

}

