<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'price',
        'compare_price',
        'cost_price',
        'min_order_qty',
        'max_order_qty',
        'is_track_stock',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values')
            ->withPivot('attribute_id')
            ->withTimestamps();
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_variant_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)
            ->with('user')
            ->where('approved', 1)
            ->latest();
    }
}
