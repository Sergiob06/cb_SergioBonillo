<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoSolicitudController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $estadoSeleccionado = $request->get('estado');

        $solicitudes = $product->solicitudes()
            ->when(in_array($estadoSeleccionado, ProductoSolicitud::ESTADOS, true), function ($query) use ($estadoSeleccionado) {
                $query->where('estado', $estadoSeleccionado);
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $conteos = [
            'total' => $product->solicitudes()->count(),
            'pendientes' => $product->solicitudes()->where('estado', ProductoSolicitud::ESTADO_PENDIENTE)->count(),
        ];

        return view('admin.productos.solicitudes.index', compact('product', 'solicitudes', 'conteos', 'estadoSeleccionado'));
    }

    public function show(ProductoSolicitud $solicitud)
    {
        $solicitud->load('product');

        return view('admin.productos.solicitudes.show', compact('solicitud'));
    }

    public function updateEstado(Request $request, ProductoSolicitud $solicitud)
    {
        $datosValidados = $request->validate([
            'estado' => ['required', Rule::in(ProductoSolicitud::ESTADOS)],
        ], [
            'estado.required' => 'Selecciona un estado para la solicitud.',
            'estado.in' => 'El estado seleccionado no es valido.',
        ]);

        $solicitud->update([
            'estado' => $datosValidados['estado'],
        ]);

        return redirect()
            ->back()
            ->with('mensaje', 'Estado de la solicitud actualizado correctamente.');
    }
}
