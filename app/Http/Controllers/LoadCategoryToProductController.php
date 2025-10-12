<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class LoadCategoryToProductController extends Controller
{
    public function loasCategory()
    {
        $getCategories = Category::getCategories();
        $html = view('admin.product.partials.create_category', compact('getCategories'))->render();
        return response()->json(["status" => "success", 'html' => $html]);
    }
    public function productDetails()
    {
        $products = Product::with([
            'category',
            'productImages',
            'productVariants.attributeValues.attribute'
        ])->get();
        dd($products);die;
    }
}
