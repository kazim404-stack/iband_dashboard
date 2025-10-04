<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId)
    {
        $products = Product::all();
        $productImages = ProductImage::where('product_id', $productId)->get();

        return view('admin.productImage.index', compact('productId', 'products', 'productImages'));
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
    public function store(StoreProductImageRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'backend/assets/images/productImage/' . $imageName;
                $image->move(public_path('backend/assets/images/productImage/'), $imageName);
                $validatedData['image'] = $imagePath;
                ProductImage::create([
                    'product_id' => $validatedData['product_id'],
                    'image' => $validatedData['image'],
                    'alt_text' => $validatedData['alt_text'] ?? '',
                    'is_primary' => $validatedData['is_primary'],
                    'sort_order' => $validatedData['sort_order'] ?? null,
                ]);
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Added successfully']);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductImage $image)
    {
        if ($image->image) {
            $path = public_path($image->image);
            if (is_file($path)) {
                unlink($path);
            }
            $image->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
    }
}
