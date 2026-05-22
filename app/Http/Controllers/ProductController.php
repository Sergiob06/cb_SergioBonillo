<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductoSolicitud;
use App\Support\ImagePath;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = $this->consultaProductosConSolicitudes()
            ->paginate(10);

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
        $product = Product::withCount([
                'solicitudes',
                'solicitudes as solicitudes_pendientes_count' => fn ($query) => $query->where('estado', ProductoSolicitud::ESTADO_PENDIENTE),
            ])
            ->findOrFail($id);

        $solicitudesRecientes = $product->solicitudes()
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.productos.show', compact('product', 'solicitudesRecientes'));
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

        $products = $this->consultaProductosConSolicitudes()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        $products->appends(['search' => $search]);

        return view('admin.productos.index', compact('products', 'search'));
    }

    private function validarProducto(Request $request, bool $imagenObligatoria): array
    {
        $reglaImagen = $imagenObligatoria
            ? 'required|file|mimes:jpg,jpeg,png,webp,svg|max:4096'
            : 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:4096';

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
            'image.mimes' => 'La imagen debe ser JPG, PNG, WEBP o SVG.',
            'image.max' => 'La imagen no puede superar 4MB.',
        ]);
    }

    private function consultaProductosConSolicitudes(): Builder
    {
        return Product::query()
            ->withCount([
                'solicitudes',
                'solicitudes as solicitudes_pendientes_count' => fn ($query) => $query->where('estado', ProductoSolicitud::ESTADO_PENDIENTE),
            ])
            ->withMax([
                'solicitudes as ultima_solicitud_pendiente_at' => fn ($query) => $query->where('estado', ProductoSolicitud::ESTADO_PENDIENTE),
            ], 'created_at')
            ->orderByDesc('solicitudes_pendientes_count')
            ->orderByDesc('ultima_solicitud_pendiente_at')
            ->orderByDesc('created_at');
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

            $datosProducto['image'] = $this->guardarImagenProducto($request->file('image'));
        }

        return $datosProducto;
    }

    private function eliminarImagenSiExiste(?string $rutaImagen): void
    {
        $rutaNormalizada = $this->normalizarRutaImagen($rutaImagen);

        if ($rutaNormalizada && Str::startsWith($rutaNormalizada, 'productos/producto_')) {
            $rutaPublica = public_path($rutaNormalizada);

            if (is_file($rutaPublica)) {
                @unlink($rutaPublica);
                return;
            }
        }

        ImagePath::deleteFromDirectories($rutaImagen, ['productos', 'products']);
    }

    private function normalizarRutaImagen(?string $rutaImagen): ?string
    {
        return ImagePath::normalizeFromDirectories($rutaImagen, ['productos', 'products']);
    }

    private function guardarImagenProducto(UploadedFile $imagen): string
    {
        $directorio = public_path('productos');

        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }

        $extension = strtolower($imagen->getClientOriginalExtension() ?: $imagen->extension());
        $nombreArchivo = 'producto_' . now()->format('YmdHis') . '_' . Str::random(8) . '.' . $extension;

        $imagen->move($directorio, $nombreArchivo);

        return 'productos/' . $nombreArchivo;
    }
}
