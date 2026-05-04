<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.productos.index', compact('products'));
    }

    public function create()
    {
        return view('admin.productos.create');
    }

    public function store(Request $request)
    {
        $datosValidados = $this->validarProducto($request, true);
        $datosProducto = $this->prepararDatosProducto($datosValidados, $request);

        Product::create($datosProducto);

        return redirect()->route('productos.index')->with('mensaje', 'Producto creado correctamente');
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return view('admin.productos.show', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        return view('admin.productos.edit', compact('product'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $datosValidados = $this->validarProducto($request, false);
        $datosProducto = $this->prepararDatosProducto($datosValidados, $request, $product);

        $product->update($datosProducto);

        return redirect()->route('productos.index')->with('mensaje', 'Producto actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            $this->eliminarImagenSiExiste($product->image);
        }

        $product->delete();

        return redirect()->route('productos.index')->with('mensaje', 'Producto eliminado correctamente');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        $products = Product::query()
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $products->appends(['search' => $search]);

        return view('admin.productos.index', compact('products', 'search'));
    }

    private function validarProducto(Request $request, bool $imagenObligatoria): array
    {
        $reglaImagen = $imagenObligatoria
            ? 'required|image|mimes:jpg,jpeg,png|max:2048'
            : 'nullable|image|mimes:jpg,jpeg,png|max:2048';

        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'image' => $reglaImagen,
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'image.required' => 'Debes subir una imagen del producto.',
            'image.max' => 'La imagen no puede superar 2MB.',
        ]);
    }

    private function prepararDatosProducto(array $datosValidados, Request $request, ?Product $product = null): array
    {
        $datosProducto = [
            'name' => $datosValidados['name'],
            'description' => $datosValidados['description'] ?? null,
            'price' => $datosValidados['price'],
        ];

        if ($request->hasFile('image')) {
            if ($product?->image) {
                $this->eliminarImagenSiExiste($product->image);
            }

            $datosProducto['image'] = $this->normalizarRutaImagen(
                $request->file('image')->store('products', 'public')
            );
        }

        return $datosProducto;
    }

    private function eliminarImagenSiExiste(?string $rutaImagen): void
    {
        $rutaNormalizada = $this->normalizarRutaImagen($rutaImagen);

        if ($rutaNormalizada && !Str::startsWith($rutaNormalizada, ['http://', 'https://'])) {
            Storage::disk('public')->delete($rutaNormalizada);
        }
    }

    private function normalizarRutaImagen(?string $rutaImagen): ?string
    {
        if (!$rutaImagen) {
            return null;
        }

        $rutaNormalizada = trim(str_replace('\\', '/', $rutaImagen));

        if ($rutaNormalizada === '') {
            return null;
        }

        if (Str::startsWith($rutaNormalizada, ['http://', 'https://'])) {
            return $rutaNormalizada;
        }

        $rutaNormalizada = preg_replace('#^/?storage/#', '', $rutaNormalizada);
        $rutaNormalizada = preg_replace('#^/?public/#', '', $rutaNormalizada);

        return ltrim($rutaNormalizada, '/');
    }
}
