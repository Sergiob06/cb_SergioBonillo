<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EquipoController extends Controller
{
    // Muestra la lista de todos los equipos
    public function index(Request $request)
    {
        if ($request->is('admin/*')) {
            $equipos = Equipo::with(['category'])
                ->withCount('jugadores')
                ->orderBy('nombre', 'asc')
                ->paginate(10);

            return view('admin.equipos.index', compact('equipos'));
        }

        $categories = Category::orderBy('name')->get();
        $selectedCategory = $request->integer('category');

        $query = Equipo::with('category')->orderBy('nombre');

        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }

        $equipos = $query->get();

        return view('equipos.index', compact('equipos', 'categories', 'selectedCategory'));
    }

    // Muestra el formulario para crear un equipo nuevo
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.equipos.create', compact('categories')); // Carga la vista del formulario
    }

    // Guarda el equipo nuevo cuando pulsas "Enviar" o "Guardar"
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'nombre'      => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'descripcion' => 'nullable|string|max:2000',
            'imagen_club' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'es_local'    => 'nullable|boolean',
        ], [
            'nombre.required'    => 'El nombre del equipo es obligatorio.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'imagen_club.image'  => 'El archivo debe ser una imagen.',
            'imagen_club.mimes'  => 'Solo se permiten formatos: jpg, jpeg, png.',
            'imagen_club.max'  => 'La imagen no puede superar 2MB.',
        ]);

        $category = Category::findOrFail($datosValidados['category_id']);

        $datosEquipo = [
            'nombre' => $datosValidados['nombre'],
            'category_id' => $category->id,
            'categoria' => $category->name,
            'descripcion' => $datosValidados['descripcion'] ?? null,
            'es_local' => $request->boolean('es_local'),
        ];

        // Si el usuario ha subido una foto...
        if ($request->hasFile('imagen_club')) {
            $datosEquipo['imagen_club'] = $this->normalizarRutaImagen(
                $request->file('imagen_club')->store('escudos', 'public')
            );
        }

        Equipo::create($datosEquipo); // Guarda el equipo en la base de datos
        return redirect()->route('equipos.index')->with('mensaje', 'Equipo creado'); // Vuelve al listado con un aviso
    }

    // Borra un equipo de la base de datos
    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id); // Busca el equipo por su ID o da error si no existe

        // Si el equipo tiene foto, la borramos de la carpeta pública
        if ($equipo->imagen_club) {
            $this->eliminarImagenSiExiste($equipo->imagen_club);
        }

        $equipo->delete(); // Borra el registro de la base de datos
        return redirect()->route('equipos.index'); // Vuelve al listado
    }

    // Muestra el formulario para editar un equipo que ya existe
    public function edit($id)
    {
        $equipo = Equipo::findOrFail($id); // Busca los datos del equipo que quieres editar
        $categories = Category::orderBy('name')->get();

        return view('admin.equipos.edit', compact('equipo', 'categories')); // Los manda al formulario de edición
    }

    // Actualiza los datos del equipo cuando guardas los cambios en el Edit
    public function update(Request $request, $id)
    {
        $equipo = Equipo::findOrFail($id); // Busca el equipo en la base de datos

        $datosValidados = $request->validate([
            'nombre'      => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'descripcion' => 'nullable|string|max:2000',
            'imagen_club' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'es_local'    => 'nullable|boolean',
        ], [
            'nombre.required'    => 'El nombre del equipo es obligatorio.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'imagen_club.image'  => 'El archivo debe ser una imagen.',
            'imagen_club.mimes'  => 'Solo se permiten formatos: jpg, jpeg, png.',
            'imagen_club.max'  => 'La imagen no puede superar 2MB.',
        ]);

        $category = Category::findOrFail($datosValidados['category_id']);

        $datosEquipo = [
            'nombre' => $datosValidados['nombre'],
            'category_id' => $category->id,
            'categoria' => $category->name,
            'descripcion' => $datosValidados['descripcion'] ?? null,
            'es_local' => $request->boolean('es_local'),
        ];

        // Si has subido una foto NUEVA...
        if ($request->hasFile('imagen_club')) {
            if ($equipo->imagen_club) {
                $this->eliminarImagenSiExiste($equipo->imagen_club);
            }

            $datosEquipo['imagen_club'] = $this->normalizarRutaImagen(
                $request->file('imagen_club')->store('escudos', 'public')
            );
        }

        $equipo->update($datosEquipo); // Guarda los cambios finales
        return redirect()->route('equipos.index')->with('mensaje', 'Equipo actualizado correctamente');
    }


    // Muestra la ficha detallada del equipo
    public function show($id)
    {
        // Buscamos el equipo o lanzamos error 404 si no existe
        $equipo = Equipo::with('category')->findOrFail($id);

        // Retornamos la vista 'show' pasando los datos del equipo
        return view('admin.equipos.show', compact('equipo'));
    }

    ////////////////
    /// BUSCADOR///
    //////////////
    public function search(Request $request)
    {
        $search = $request->get('search');

        $equipos = Equipo::with('category')
            ->where('nombre', 'like', '%' . $search . '%')
            ->orderBy('nombre', 'ASC')
            ->paginate(10); 

        // Importante para que al cambiar de página no se pierda la búsqueda
        $equipos->appends(['search' => $search]);

        return view('admin.equipos.index', compact('equipos', 'search'));
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
