<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Posicion;
use App\Support\ImagePath;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JugadorController extends Controller
{
    // Muestra la lista de jugadores
    public function index(Request $request)
    {
        $equiposLocales = Equipo::locales()->orderBy('nombre')->get();
        $equipoSeleccionado = $this->equipoLocalSeleccionado($request, $equiposLocales);
        $search = trim((string) $request->get('search', ''));

        $jugadores = Jugador::with(['equipo.category', 'posicion'])
            ->deEquiposLocales()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('nombre', 'like', '%'.$search.'%')
                        ->orWhere('apellido', 'like', '%'.$search.'%');
                });
            })
            ->when($equipoSeleccionado, function ($query) use ($equipoSeleccionado) {
                $query->where('equipo_id', $equipoSeleccionado);
            })
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jugadores.index', compact('jugadores', 'equiposLocales', 'equipoSeleccionado', 'search'));
    }

    // Muestra el formulario (pasando los equipos)
    public function create()
    {
        $equipos = Equipo::locales()->orderBy('nombre')->get();
        $posiciones = Posicion::orderBy('nombre')->get();

        return view('admin.jugadores.create', compact('equipos', 'posiciones'));
    }

    // Guarda el jugador en la base de datos
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dorsal' => 'nullable|integer|min:0|max:99',
            'posicion_id' => 'required|exists:posiciones,id',
            'fecha_nacimiento' => 'nullable|date',
            'equipo_id' => ['required', Rule::exists('equipos', 'id')->where('es_local', true)],
            'imagen_jugador' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'dorsal.integer' => 'El dorsal debe ser un número.',
            'posicion_id.required' => 'Debes seleccionar una posición.',
            'posicion_id.exists' => 'La posición seleccionada no es válida.',
            'equipo_id.required' => 'Debes asignar al jugador a un equipo.',
            'equipo_id.exists' => 'El jugador solo puede pertenecer a un equipo local.',
            'imagen_jugador.image' => 'El archivo debe ser una imagen.',
            'imagen_jugador.mimes' => 'La foto debe ser JPG, PNG o WEBP.',
            'imagen_jugador.max' => 'La foto es demasiado pesada (máximo 4MB).',
        ]);

        $posicion = Posicion::findOrFail($datosValidados['posicion_id']);

        $datosJugador = [
            'nombre' => $datosValidados['nombre'],
            'apellido' => $datosValidados['apellido'],
            'dorsal' => $datosValidados['dorsal'] ?? null,
            'posicion_id' => $posicion->id,
            'posicion' => $posicion->nombre,
            'fecha_nacimiento' => $datosValidados['fecha_nacimiento'] ?? null,
            'equipo_id' => $datosValidados['equipo_id'],
        ];

        if ($request->hasFile('imagen_jugador')) {
            $datosJugador['imagen_jugador'] = $this->guardarImagenJugador(
                $request->file('imagen_jugador'),
                $datosValidados['nombre'],
                $datosValidados['apellido']
            );
        }

        Jugador::create($datosJugador);

        return redirect()->route('jugadores.index')->with('mensaje', 'Jugador creado con éxito');
    }

    public function edit($id)
    {
        $jugador = Jugador::with(['equipo', 'posicion'])->deEquiposLocales()->findOrFail($id);
        $equipos = Equipo::locales()->orderBy('nombre')->get();
        $posiciones = Posicion::orderBy('nombre')->get();

        return view('admin.jugadores.edit', compact('jugador', 'equipos', 'posiciones'));
    }

    public function update(Request $request, $id)
    {

        $datosValidados = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dorsal' => 'nullable|integer|min:0|max:99',
            'posicion_id' => 'required|exists:posiciones,id',
            'fecha_nacimiento' => 'nullable|date',
            'equipo_id' => ['required', Rule::exists('equipos', 'id')->where('es_local', true)],
            'imagen_jugador' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'dorsal.integer' => 'El dorsal debe ser un número.',
            'posicion_id.required' => 'Debes seleccionar una posición.',
            'posicion_id.exists' => 'La posición seleccionada no es válida.',
            'equipo_id.required' => 'Debes asignar al jugador a un equipo.',
            'equipo_id.exists' => 'El jugador solo puede pertenecer a un equipo local.',
            'imagen_jugador.image' => 'El archivo debe ser una imagen.',
            'imagen_jugador.mimes' => 'La foto debe ser JPG, PNG o WEBP.',
            'imagen_jugador.max' => 'La foto es demasiado pesada (máximo 4MB).',
        ]);

        $jugador = Jugador::deEquiposLocales()->findOrFail($id);

        $posicion = Posicion::findOrFail($datosValidados['posicion_id']);

        $datosJugador = [
            'nombre' => $datosValidados['nombre'],
            'apellido' => $datosValidados['apellido'],
            'dorsal' => $datosValidados['dorsal'] ?? null,
            'posicion_id' => $posicion->id,
            'posicion' => $posicion->nombre,
            'fecha_nacimiento' => $datosValidados['fecha_nacimiento'] ?? null,
            'equipo_id' => $datosValidados['equipo_id'],
        ];

        if ($request->hasFile('imagen_jugador')) {
            if ($jugador->imagen_jugador) {
                $this->eliminarImagenSiExiste($jugador->imagen_jugador, $jugador->id);
            }

            $datosJugador['imagen_jugador'] = $this->guardarImagenJugador(
                $request->file('imagen_jugador'),
                $datosValidados['nombre'],
                $datosValidados['apellido']
            );
        }

        $jugador->update($datosJugador);

        return redirect()->route('jugadores.index')->with('mensaje', 'Jugador actualizado correctamente');
    }

    public function destroy($id)
    {
        // 1. Buscamos al jugador o lanzamos error 404 si no existe
        $jugador = Jugador::deEquiposLocales()->findOrFail($id);

        // 2. Definimos la ruta de su imagen
        if ($jugador->imagen_jugador) {
            $this->eliminarImagenSiExiste($jugador->imagen_jugador, $jugador->id);
        }

        // 4. Borramos el registro de la base de datos
        $jugador->delete();

        // 5. Redirigimos con un mensaje de éxito
        return redirect()->route('jugadores.index')->with('mensaje', 'Jugador eliminado correctamente del club');
    }

    public function show($id)
    {
        // Usamos with('equipo') para traer también los datos del equipo al que pertenece
        $jugador = Jugador::with(['equipo.category', 'posicion'])->deEquiposLocales()->findOrFail($id);

        return view('admin.jugadores.show', compact('jugador'));
    }

    // /////////////
    // BUSCADOR //
    // ///////////
    public function search(Request $request)
    {
        return $this->index($request);
    }

    private function equipoLocalSeleccionado(Request $request, $equiposLocales): ?int
    {
        $equipoId = $request->integer('equipo_id') ?: $request->integer('equipo');

        if (! $equipoId) {
            return null;
        }

        return $equiposLocales->contains('id', $equipoId) ? $equipoId : null;
    }

    private function guardarImagenJugador(UploadedFile $imagen, string $nombre, string $apellido): string
    {
        $directorio = public_path('jugadores');

        if (! is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $extension = strtolower($imagen->getClientOriginalExtension() ?: $imagen->extension());
        $base = Str::slug($nombre.' '.$apellido) ?: 'jugador';
        $nombreArchivo = $base.'-'.now()->format('YmdHis').'-'.Str::lower(Str::random(8)).'.'.$extension;

        $imagen->move($directorio, $nombreArchivo);

        return 'jugadores/'.$nombreArchivo;
    }

    private function eliminarImagenSiExiste(?string $rutaImagen, ?int $jugadorId = null): void
    {
        $rutaNormalizada = $this->normalizarRutaImagen($rutaImagen);

        if (! $rutaNormalizada || $rutaNormalizada === ImagePath::DEFAULT_IMAGE) {
            return;
        }

        $imagenUsadaPorOtroJugador = Jugador::query()
            ->when($jugadorId, fn ($query) => $query->whereKeyNot($jugadorId))
            ->whereIn('imagen_jugador', [
                $rutaNormalizada,
                'public/'.$rutaNormalizada,
                'storage/'.$rutaNormalizada,
            ])
            ->exists();

        if (! $imagenUsadaPorOtroJugador) {
            ImagePath::deleteFromPublicPath($rutaNormalizada, 'jugadores');
        }
    }

    private function normalizarRutaImagen(?string $rutaImagen): ?string
    {
        return ImagePath::normalize($rutaImagen, 'jugadores');
    }
}
