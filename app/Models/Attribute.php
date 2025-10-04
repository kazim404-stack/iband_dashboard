<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasTranslations;
    protected $fillable = [
        'name'
    ];
    public $translatable = [
        "name"
    ];
    public function attributeValues(){
        return $this->hasMany(AttributeValue::class);
    }
}
