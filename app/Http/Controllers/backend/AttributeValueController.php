<?php

namespace App\Http\Controllers\backend;

use App\DataTables\AttributeValuesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeValueRequest;
use App\Http\Requests\UpdateAttributeValueRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AttributeValuesDataTable $dataTable)
    {
        $attributes = Attribute::all();
        return $dataTable->render('admin.attributeValue.index', compact('attributes'));
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
    public function store(StoreAttributeValueRequest $request)
    {
        $validatedData = $request->validated();
        AttributeValue::create($validatedData);
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
        $attributes = Attribute::all();
        $attributeValue = AttributeValue::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $attributeValue, 'attributes' => $attributes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeValueRequest $request, AttributeValue $attributeValue)
    {
        $validatedData = $request->validated();
        $attributeValue->update($validatedData);
        return response()->json(['status' => 'success','message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            AttributeValue::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
        }
    }
}
