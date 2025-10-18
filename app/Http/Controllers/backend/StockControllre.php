<?php

namespace App\Http\Controllers\backend;

use App\DataTables\StocksDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Currency;
use App\Models\ProductVariant;
use App\Models\Stock;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class StockControllre extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StocksDataTable $dataTable)
    {
        $productVariants  = ProductVariant::all();
        $currencies = Currency::all();
        return $dataTable->render('admin.stock.index', compact('productVariants','currencies'));
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
    public function store(StoreStockRequest $request)
    {
        $validatedData = $request->validated();
        Stock::create($validatedData);
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
        $stock = Stock::findOrFail($id);
        $productVariants = ProductVariant::with('product')->get();
        $currencies = Currency::all();
        return response()->json(['status' => 'success', 'data' => $stock, 'productVariants' => $productVariants, '
        currency' => $currencies]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $validatedData = $request->validated();
        $stock->update($validatedData);
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            Stock::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
        }
    }
}
