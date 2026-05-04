<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Equipo; // Importamos el modelo Equipo para el desplegable
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JugadorController extends Controller
{
    // Muestra la lista de jugadores
    public function index()
    {
        $jugadores = Jugador::with('equipo')->orderBy('nombre', 'asc')->paginate(10);
        return view('admin.jugadores.index', compact('jugadores'));
    }

    // Muestra el formulario (pasando los equipos)
    public function create()
    {
        $equipos = Equipo::orderBy('nombre')->get(); // Necesitamos los equipos para el <select>
        $posicionesDisponibles = Jugador::query()
            ->select('posicion')
            ->distinct()
            ->whereNotNull('posicion')
            ->orderBy('posicion')
            ->pluck('posicion');

        return view('admin.jugadores.create', compact('equipos', 'posicionesDisponibles'));
    }

    // Guarda el jugador en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellido'         => 'required|string|max:255',
            'dorsal'           => 'required|integer|min:0|max:99',
            'posicion'         => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'equipo_id'        => 'required|exists:equipos,id',
            'imagen_jugador'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nombre.required'           => 'El nombre es obligatorio.',
            'apellido.required'         => 'El apellido es obligatorio.',
            'dorsal.required'           => 'El dorsal es obligatorio.',
            'dorsal.integer'            => 'El dorsal debe ser un número.',
            'equipo_id.required'        => 'Debes asignar al jugador a un equipo.',
            'equipo_id.exists'          => 'El equipo seleccionado no es válido.',
            'imagen_jugador.image'      => 'El archivo debe ser una imagen.',
            'imagen_jugador.mimes'      => 'La foto debe ser JPG o PNG.',
            'imagen_jugador.max'        => 'La foto es demasiado pesada (máximo 2MB).',
        ]);

        $datosJugador = [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'dorsal' => $request->dorsal,
            'posicion' => $request->posicion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'equipo_id' => $request->equipo_id,
        ];

        if ($request->hasFile('imagen_jugador')) {
            $datosJugador['imagen_jugador'] = $this->normalizarRutaImagen(
                $request->file('imagen_jugador')->store('jugadores', 'public')
            );
        }

        Jugador::create($datosJugador);
        return redirect()->route('jugadores.index')->with('mensaje', 'Jugador creado con éxito');
    }

    public function edit($id)
    {
        $jugador = Jugador::findOrFail($id);
        $equipos = Equipo::orderBy('nombre')->get(); // Para el desplegable de cambio de equipo
        $posicionesDisponibles = Jugador::query()
            ->select('posicion')
            ->distinct()
            ->whereNotNull('posicion')
            ->orderBy('posicion')
            ->pluck('posicion');

        return view('admin.jugadores.edit', compact('jugador', 'equipos', 'posicionesDisponibles'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellido'         => 'required|string|max:255',
            'dorsal'           => 'required|integer|min:0|max:99',
            'posicion'         => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'equipo_id'        => 'required|exists:equipos,id',
            'imagen_jugador'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nombre.required'           => 'El nombre es obligatorio.',
            'apellido.required'         => 'El apellido es obligatorio.',
            'dorsal.required'           => 'El dorsal es obligatorio.',
            'dorsal.integer'            => 'El dorsal debe ser un número.',
            'equipo_id.required'        => 'Debes asignar al jugador a un equipo.',
            'equipo_id.exists'          => 'El equipo seleccionado no es válido.',
            'imagen_jugador.image'      => 'El archivo debe ser una imagen.',
            'imagen_jugador.mimes'      => 'La foto debe ser JPG o PNG.',
            'imagen_jugador.max'        => 'La foto es demasiado pesada (máximo 2MB).',
        ]);

        $jugador = Jugador::findOrFail($id);

        $datosJugador = [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'dorsal' => $request->dorsal,
            'posicion' => $request->posicion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'equipo_id' => $request->equipo_id,
        ];

        if ($request->hasFile('imagen_jugador')) {
            if ($jugador->imagen_jugador) {
                $this->eliminarImagenSiExiste($jugador->imagen_jugador);
            }

            $datosJugador['imagen_jugador'] = $this->normalizarRutaImagen(
                $request->file('imagen_jugador')->store('jugadores', 'public')
            );
        }

        $jugador->update($datosJugador);
        return redirect()->route('jugadores.index')->with('mensaje', 'Jugador actualizado correctamente');
    }


    public function destroy($id)
    {
        // 1. Buscamos al jugador o lanzamos error 404 si no existe
        $jugador = Jugador::findOrFail($id);

        // 2. Definimos la ruta de su imagen
        if ($jugador->imagen_jugador) {
            $this->eliminarImagenSiExiste($jugador->imagen_jugador);
        }

        // 4. Borramos el registro de la base de datos
        $jugador->delete();

        // 5. Redirigimos con un mensaje de éxito
        return redirect()->route('jugadores.index')->with('mensaje', 'Jugador eliminado correctamente del club');
    }

    public function show($id)
    {
        // Usamos with('equipo') para traer también los datos del equipo al que pertenece
        $jugador = Jugador::with('equipo')->findOrFail($id);
        return view('admin.jugadores.show', compact('jugador'));
    }

    ///////////////
    // BUSCADOR //
    /////////////
    public function search(Request $request)
    {
        $search = $request->get('search');

        $jugadores = Jugador::with('equipo')
            ->where(function ($query) use ($search) {
                $query->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('apellido', 'like', '%' . $search . '%');
            })
            ->orderBy('apellido', 'ASC')
            ->paginate(10);
            
        // Importante para que al cambiar de página no se pierda la búsqueda
        $jugadores->appends(['search' => $search]);

        return view('admin.jugadores.index', compact('jugadores', 'search'));
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
