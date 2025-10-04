<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    protected $fillable = [
        'parent_id',
        'name',
        'image',
        'slug',
        'description',
        'sort_order',
        'status',
    ];
    public $translatable = ['description', 'name'];
    public function parentCategory()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id')->where('status', 1);
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('status', 1);
    }

    public static function getCategories($type = null)
    {
        $query = Category::with(['subCategories'])
            ->where('parent_id', 0)
            ->where('status', 1);

        if ($type) {
            if (is_array($type)) {
                $query->whereIn('type', $type);
            } else {
                $query->where('type', $type);
            }
        }

        return $query->get();
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
