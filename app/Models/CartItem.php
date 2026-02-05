<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    /** @use HasFactory<\Database\Factories\CartItemFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'session_id', 'product_id', 'quantity', 'price', 'currency_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🔹 Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 Relationship to Currency (optional)
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
