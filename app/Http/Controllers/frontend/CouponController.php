<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::whereName($request->name)->first();
        if ($coupon && $coupon->checkIfValid()) {
            return response(['status' => 'success', 'message' => "Coupon applied successfully"]);
        } else {
            return response()->json([
                "error" => "Invalid or expired coupon"
            ]);
        }
    }
}
