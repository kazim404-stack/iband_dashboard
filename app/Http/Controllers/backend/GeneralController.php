<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function setCurrency(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|max:10|exists:currencies,code'
        ]);

        session(['currency_code' => strtoupper($request->currency_code)]);

        return response()->json([
            'status' => 'success',
            'currency_code' => session('currency_code')
        ]);
    }
}
