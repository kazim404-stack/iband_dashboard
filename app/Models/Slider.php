<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class Slider extends Model
{
    use HasTranslations;
    protected $fillable = [
        'title',
        'description',
        'status'
    ];
    public $translatable = ['title', 'description'];
    public function sliderImages(){
        return $this->hasMany(SliderImage::class);
    }
}
