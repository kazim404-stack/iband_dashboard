<?php

namespace App\Http\Controllers\backend;

use App\DataTables\CouponsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CouponsDataTable $dataTable)
    {
        return $dataTable->render('admin.coupon.index');
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
    public function store(StoreCouponRequest $request)
    {
        $validatedData = $request->validated();
        Coupon::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Coupon has been added successfully'
        ]);
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
        $coupon = Coupon::findOrFail($id);
        return response()->json(['data' => $coupon, 'status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $validatedData = $request->validated();
        $coupon->update($validatedData);
        return response()->json(['status' => 'success', 'message' => 'Coupon has been updated successfully']);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            Coupon::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Coupon has been deleted successfully']);
        }
    }
}
