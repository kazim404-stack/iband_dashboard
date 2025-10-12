<?php

namespace App\Http\Controllers\fronend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    public function setLocal($lang)
    {
        if (in_array($lang, array_keys(config('languages')))) {
            session(['locale' => $lang]);
        }
        return redirect()->back();
    }
}
