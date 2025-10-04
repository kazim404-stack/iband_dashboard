<?php

namespace App\Http\Controllers\backend;

use App\DataTables\AttributesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Models\Attribute;

use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AttributesDataTable $dataTable)
    {
        return $dataTable->render('admin.attribute.index');
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
    public function store(StoreAttributeRequest $request)
    {
        $validatedData = $request->validated();
        Attribute::create($validatedData);
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
        if ($id) {
            $attribute = Attribute::findOrFail($id);
            return response()->json(['status' => 'success','data' => $attribute]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        $validatedData = $request->validated();
        $attribute->update($validatedData);
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            Attribute::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
        }
    }
}
