<?php

namespace App\Http\Controllers\backend;

use App\DataTables\CurrenciesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CurrenciesDataTable $dataTable)
    {
        return $dataTable->render('admin.currency.index');
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
    public function store(StoreCurrencyRequest $request)
    {
        $validatedData = $request->validated();
        Currency::create($validatedData);
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
        $currency = Currency::findOrFail($id);
        return response(['status' => 'success', 'data' => $currency]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $validatedData = $request->validated();
        $currency->update($validatedData);
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            Currency::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
        }
    }
}
