<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class LoadParentCategoryController extends Controller
{
    public function loadParentSelect()
    {
        $getCategories = Category::getCategories();
        $html = view('admin.category.partial.create-parent-select', compact('getCategories'))->render();

        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }
}
