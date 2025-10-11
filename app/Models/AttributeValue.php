<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Models\Attribute;

class AttributeValue extends Model
{
    use HasTranslations;
    protected $fillable = [
        'attribute_id',
        'slug',
        'value',
        'sort_order'
    ];
    public $translatable = [
        'value'
    ];
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_attribute_values')
            ->withPivot('attribute_id')
            ->withTimestamps();
    }
}
