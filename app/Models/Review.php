<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_variant_id',
        'title',
        'body',
        'rating',
        'approved'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }
}
