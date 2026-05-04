<?php

namespace App\Http\Controllers;

use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GaleriaController extends Controller
{
    public function index()
    {
        $galerias = Galeria::orderBy('fecha_imagen', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.galerias.index', compact('galerias'));
    }

    public function create()
    {
        return view('admin.galerias.create');
    }

    public function store(Request $request)
    {
        $datosValidados = $this->validarGaleria($request, true);
        $nombreImagen = $this->guardarImagen($request);

        Galeria::create([
            'titulo' => $datosValidados['titulo'],
            'descripcion' => $datosValidados['descripcion'],
            'categoria' => $datosValidados['categoria'],
            'fecha_imagen' => $datosValidados['fecha_imagen'] ?? null,
            'imagen' => $nombreImagen,
        ]);

        return redirect()->route('galerias.index')->with('mensaje', 'Foto añadida a la galería correctamente');
    }

    public function show($id)
    {
        $galeria = Galeria::findOrFail($id);

        return view('admin.galerias.show', compact('galeria'));
    }

    public function edit($id)
    {
        $galeria = Galeria::findOrFail($id);

        return view('admin.galerias.edit', compact('galeria'));
    }

    public function update(Request $request, $id)
    {
        $galeria = Galeria::findOrFail($id);
        $datosValidados = $this->validarGaleria($request, false);

        if ($request->hasFile('imagen')) {
            $this->borrarImagenSiExiste($galeria->imagen);
            $galeria->imagen = $this->guardarImagen($request);
        }

        $galeria->titulo = $datosValidados['titulo'];
        $galeria->descripcion = $datosValidados['descripcion'];
        $galeria->categoria = $datosValidados['categoria'];
        $galeria->fecha_imagen = $datosValidados['fecha_imagen'] ?? null;
        $galeria->save();

        return redirect()->route('galerias.index')->with('mensaje', 'Foto actualizada correctamente');
    }

    public function destroy($id)
    {
        $galeria = Galeria::findOrFail($id);
        $this->borrarImagenSiExiste($galeria->imagen);
        $galeria->delete();

        return redirect()->route('galerias.index')->with('mensaje', 'Foto eliminada correctamente');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        $galerias = Galeria::where('titulo', 'like', '%' . $search . '%')
            ->orWhere('descripcion', 'like', '%' . $search . '%')
            ->orWhere('categoria', 'like', '%' . $search . '%')
            ->orderBy('fecha_imagen', 'desc')
            ->paginate(12);

        $galerias->appends(['search' => $search]);

        return view('admin.galerias.index', compact('galerias', 'search'));
    }

    private function validarGaleria(Request $request, bool $imagenObligatoria): array
    {
        $reglaImagen = $imagenObligatoria
            ? 'required|image|mimes:jpg,jpeg,png|max:2048'
            : 'nullable|image|mimes:jpg,jpeg,png|max:2048';

        return $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'categoria' => 'required|string|max:255',
            'fecha_imagen' => 'nullable|date',
            'imagen' => $reglaImagen,
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'categoria.required' => 'La categoría es obligatoria.',
            'imagen.required' => 'Debes subir una imagen.',
            'imagen.max' => 'La imagen no puede superar 2MB.',
        ]);
    }

    private function guardarImagen(Request $request): string
    {
        return $this->normalizarRutaImagen(
            $request->file('imagen')->store('galeria', 'public')
        );
    }

    private function borrarImagenSiExiste(?string $nombreImagen): void
    {
        $rutaNormalizada = $this->normalizarRutaImagen($nombreImagen);

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
