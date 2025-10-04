<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id','image','alt_text','is_primary','sort_order'
    ];
    public function product(){
        return $this->belongsTo(product::class);
    }
}
