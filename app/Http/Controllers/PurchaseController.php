<?php

namespace App\Http\Controllers;

use App\Mail\ProductPurchaseInquiry;
use App\Models\Product;
use App\Models\ProductoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'telefono' => 'nullable|string|max:30',
            'message' => 'nullable|string|max:2000',
        ], [
            'name.required' => 'Tu nombre es obligatorio.',
            'email.required' => 'Tu email es obligatorio.',
            'email.email' => 'Debes introducir un email valido.',
        ]);

        ProductoSolicitud::create([
            'product_id' => $product->id,
            'nombre' => $datosValidados['name'],
            'email' => $datosValidados['email'],
            'telefono' => $datosValidados['telefono'] ?? null,
            'mensaje' => $datosValidados['message'] ?? null,
            'estado' => ProductoSolicitud::ESTADO_PENDIENTE,
        ]);

        try {
            Mail::to(config('mail.club_address'))
                ->send(new ProductPurchaseInquiry($product, $datosValidados));
        } catch (\Throwable $exception) {
            Log::warning('No se pudo enviar el email de solicitud de producto.', [
                'product_id' => $product->id,
                'email' => $datosValidados['email'],
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect()
            ->route('basket.merchandising')
            ->with('mensaje', 'Solicitud enviada correctamente. Nos pondremos en contacto contigo.');
    }
}
