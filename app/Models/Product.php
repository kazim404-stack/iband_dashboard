<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasTranslations;
    protected $fillable = [
        'name',
        'category_id',
        'sku',
        'short_description',
        'long_description',
        'type',
        'weight',
        'length',
        'width',
        'height',
        'status',
    ];
    public $translatable = ['name', 'short_description', 'long_description'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
