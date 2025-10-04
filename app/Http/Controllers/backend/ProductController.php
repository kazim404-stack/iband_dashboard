<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ProductsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsDataTable $dataTable)
    {
        
        return $dataTable->render('admin.product.index');
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
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        Product::create($validatedData);
        return response()->json(["status" => "success", "message" => "Added successfully"]);
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
        $product = Product::findOrFail($id);
        $getCategories = Category::getCategories();
        $category = Category::findOrFail($product->category_id);
        $html = view('admin.product.partials.edit_category',compact('getCategories','category'))->render();
        return response()->json(["status" => "success","data" => $product,'html' => $html]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        $product->update($validatedData);
        return response()->json(["status" => "success","message" => "Updated successfully"]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            Product::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
        }
    }
}
