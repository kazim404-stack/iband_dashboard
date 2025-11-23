<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'coupon_id', 'total_price', 'status', 'delivered_at'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }
}
