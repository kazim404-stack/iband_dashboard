<?php

namespace App\Http\Controllers\backend;

use App\DataTables\productVariantsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(productVariantsDataTable $dataTable)
    {
        $products = Product::all();
        $attributeValues = AttributeValue::all();
        return $dataTable->render('admin.productVariant.index', compact('products', 'attributeValues'));
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
    public function store(StoreProductVariantRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['min_order_qty'] = $request->min_order_qty ?? 1;
        $productVariant = ProductVariant::create($validatedData);
        foreach ($validatedData['attribute_value_ids'] as $valudId) {
            $value = AttributeValue::find($valudId);
            $productVariant->attributeValues()->attach($value, [
                'attribute_id' => $value->attribute_id
            ]);
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
        $products = Product::all();
        $productVariant = ProductVariant::findOrFail($id);
        $attributeValues = AttributeValue::all();
        $attributeValue = view('admin.productVariant.partials.edit_attribute_value', compact('productVariant', 'attributeValues'))->render();
        return response()->json(['status' => 'success', 'data' => $productVariant, 'products' => $products, 'html' => $attributeValue]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductVariantRequest $request, ProductVariant $product_variant)
    {
        $validatedData = $request->validated();

        // Ensure default if not provided
        $validatedData['min_order_qty'] = $request->min_order_qty ?? 1;

        // Update the main variant fields
        $product_variant->update($validatedData);

        // 🔄 Sync attribute values (pivot table)
        if (!empty($validatedData['attribute_value_ids'])) {
            $syncData = [];

            foreach ($validatedData['attribute_value_ids'] as $valueId) {
                $value = AttributeValue::find($valueId);
                if ($value) {
                    $syncData[$valueId] = [
                        'attribute_id' => $value->attribute_id,
                    ];
                }
            }

            // Use sync() instead of attach() — it updates the pivot cleanly
            $product_variant->attributeValues()->sync($syncData);
        } else {
            // If no attributes sent, detach all
            $product_variant->attributeValues()->detach();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            ProductVariant::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
        }
    }
}
