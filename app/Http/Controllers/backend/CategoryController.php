<?php

namespace App\Http\Controllers\backend;

use App\DataTables\CategoriesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->render('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->file('image')) {
            $image = $request->file('image');
            $image_name = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_path = "backend/assets/images/category/" . $image_name;
            $image->move(public_path("backend/assets/images/category/"), $image_name);
            $validatedData["image"] = $image_path;
        }
        Category::create($validatedData);
        return response()->json(["status" => "success", "message" => "Updated successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCategories = Category::getCategories();
        $category = Category::findOrFail($id);
        $category->image = asset($category->image);
        $html = view('admin.category.partial.edit-parent-select', compact('category', 'getCategories'))->render();

        return response()->json(["status" => "success", "data" => $category, 'html' => $html]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();
          if ($request->file('image')) {
            if($category->image){
                $path = public_path($category->image);
                if(is_file($path)){
                    unlink($path);
                }
            }
            $image = $request->file('image');
            $image_name = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_path = "backend/assets/images/category/" . $image_name;
            $image->move(public_path("backend/assets/images/category/"), $image_name);
            $validatedData["image"] = $image_path;
        }
        $category->update($validatedData);
        return response()->json(["status" => "success", "message" => "Updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            $category = Category::findOrFail($id);
            $path = public_path($category->image);
            if (is_file($path)) {
                unlink($path);
            }
            $category->delete();
            return response()->json(["status" => "success", "message" => "Deleted successfully"]);
        }
    }
}
