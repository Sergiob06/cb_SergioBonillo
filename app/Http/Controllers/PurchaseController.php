<?php

namespace App\Http\Controllers;

use App\Mail\ProductPurchaseInquiry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PurchaseController extends Controller
{
    public function create(Product $product)
    {
        return view('basket.purchase', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $datosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:2000',
        ], [
            'name.required' => 'Tu nombre es obligatorio.',
            'email.required' => 'Tu email es obligatorio.',
            'email.email' => 'Debes introducir un email valido.',
        ]);

        Mail::to(config('mail.club_address'))
            ->send(new ProductPurchaseInquiry($product, $datosValidados));

        return redirect()
            ->route('basket.merchandising.buy', $product)
            ->with('mensaje', 'Tu solicitud se ha enviado correctamente. El club contactara contigo pronto.');
    }
}
