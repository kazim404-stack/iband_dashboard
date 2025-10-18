<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_variant_id',
        'currency_id',
        'qty',
        'stock_status'
    ];
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
