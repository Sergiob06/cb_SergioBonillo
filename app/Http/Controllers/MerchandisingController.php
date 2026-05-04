<?php

namespace App\Http\Controllers;

use App\Models\Product;

class MerchandisingController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('basket.merchandising', compact('products'));
    }
}
