<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipo;
use App\Models\EstadisticaEquipo;
use App\Models\Partido;
use App\Support\ImagePath;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EquipoController extends Controller
{
    // Muestra la lista de todos los equipos
    public function index(Request $request)
    {
        if ($request->is('admin/*')) {
            $equipos = Equipo::with(['category'])
                ->withCount('jugadores')
                ->when($request->boolean('locales'), fn ($query) => $query->locales())
                ->orderBy('nombre', 'asc')
                ->paginate(10)
                ->withQueryString();

            $mostrarLocales = $request->boolean('locales');

            return view('admin.equipos.index', compact('equipos', 'mostrarLocales'));
        }

        $categories = Category::orderBy('name')->get();
        $selectedCategory = $request->integer('category');
        $search = trim((string) $request->get('search', ''));

        $query = Equipo::with('category')
            ->withCount('jugadores')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('nombre', 'like', '%' . $search . '%')
                        ->orWhere('categoria', 'like', '%' . $search . '%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('nombre');

        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }

        $equipos = $query->get();

        return view('equipos.index', compact('equipos', 'categories', 'selectedCategory', 'search'));
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
            'imagen_club' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:4096',
            'imagen_existente' => 'nullable|string|max:255',
            'es_local'    => 'nullable|boolean',
        ], [
            'nombre.required'    => 'El nombre del equipo es obligatorio.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'imagen_club.file'  => 'El archivo debe ser una imagen.',
            'imagen_club.mimes'  => 'Solo se permiten formatos: jpg, jpeg, png, webp o svg.',
            'imagen_club.max'  => 'La imagen no puede superar 4MB.',
        ]);

        $category = Category::findOrFail($datosValidados['category_id']);

        $datosEquipo = [
            'nombre' => $datosValidados['nombre'],
            'category_id' => $category->id,
            'categoria' => $category->name,
            'descripcion' => $datosValidados['descripcion'] ?? null,
            'es_local' => $request->boolean('es_local'),
        ];

        if (!empty($datosValidados['imagen_existente'])) {
            $datosEquipo['imagen_club'] = $this->normalizarRutaImagen($datosValidados['imagen_existente']);
        }

        // Si el usuario ha subido una foto...
        if ($request->hasFile('imagen_club')) {
            $datosEquipo['imagen_club'] = $this->normalizarRutaImagen(
                $request->file('imagen_club')->store('fotos/equipos', 'public')
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
            'imagen_club' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:4096',
            'imagen_existente' => 'nullable|string|max:255',
            'es_local'    => 'nullable|boolean',
        ], [
            'nombre.required'    => 'El nombre del equipo es obligatorio.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'imagen_club.file'  => 'El archivo debe ser una imagen.',
            'imagen_club.mimes'  => 'Solo se permiten formatos: jpg, jpeg, png, webp o svg.',
            'imagen_club.max'  => 'La imagen no puede superar 4MB.',
        ]);

        $category = Category::findOrFail($datosValidados['category_id']);

        $datosEquipo = [
            'nombre' => $datosValidados['nombre'],
            'category_id' => $category->id,
            'categoria' => $category->name,
            'descripcion' => $datosValidados['descripcion'] ?? null,
            'es_local' => $request->boolean('es_local'),
        ];

        if (!$datosEquipo['es_local'] && $equipo->es_local && $equipo->jugadores()->exists()) {
            throw ValidationException::withMessages([
                'es_local' => 'No puedes marcar este equipo como externo mientras tenga jugadores asignados.',
            ]);
        }

        if (!empty($datosValidados['imagen_existente'])) {
            $datosEquipo['imagen_club'] = $this->normalizarRutaImagen($datosValidados['imagen_existente']);
        }

        // Si has subido una foto NUEVA...
        if ($request->hasFile('imagen_club')) {
            if ($equipo->imagen_club) {
                $this->eliminarImagenSiExiste($equipo->imagen_club);
            }

            $datosEquipo['imagen_club'] = $this->normalizarRutaImagen(
                $request->file('imagen_club')->store('fotos/equipos', 'public')
            );
        }

        $equipo->update($datosEquipo); // Guarda los cambios finales
        return redirect()->route('equipos.index')->with('mensaje', 'Equipo actualizado correctamente');
    }


    // Muestra la ficha detallada del equipo
    public function show($id)
    {
        // Buscamos el equipo o lanzamos error 404 si no existe
        $equipo = Equipo::with(['category', 'jugadores.posicion', 'jugadores' => function ($query) {
                $query->orderBy('dorsal')->orderBy('apellido')->orderBy('nombre');
            }])
            ->withCount('jugadores')
            ->findOrFail($id);

        if (!request()->is('admin/*')) {
            $partidos = $equipo->partidosComoLocal()
                ->with(['equipoLocal', 'equipoVisitante', 'category'])
                ->orderByDesc('fecha_partido')
                ->get()
                ->merge(
                    $equipo->partidosComoVisitante()
                        ->with(['equipoLocal', 'equipoVisitante', 'category'])
                        ->orderByDesc('fecha_partido')
                        ->get()
                )
                ->sortByDesc('fecha_partido')
                ->values();

            $analisisEquipo = $equipo->es_local
                ? $this->calcularAnalisisEquipo($this->estadisticasAnalisisEquipo($equipo)->with(['partido.estadisticasEquipos'])->get())
                : null;

            return view('equipos.show', compact('equipo', 'partidos', 'analisisEquipo'));
        }

        // Retornamos la vista 'show' pasando los datos del equipo
        return view('admin.equipos.show', compact('equipo'));
    }

    public function analisis(Equipo $equipo)
    {
        abort_unless($equipo->es_local, 404);

        $estadisticas = $this->estadisticasAnalisisEquipo($equipo)
            ->with(['partido.equipoLocal', 'partido.equipoVisitante', 'partido.category', 'partido.estadisticasEquipos'])
            ->get();

        $analisisEquipo = $this->calcularAnalisisEquipo($estadisticas);
        $chartData = [
            'labels' => $estadisticas->map(function (EstadisticaEquipo $estadistica) use ($equipo) {
                $partido = $estadistica->partido;
                $rival = (int) $partido->equipo_local_id === (int) $equipo->id
                    ? ($partido->equipoVisitante?->nombre ?? $partido->equipo_visitante)
                    : ($partido->equipoLocal?->nombre ?? $partido->equipo_local);

                return $partido->fecha_partido->format('d/m') . ' vs ' . $rival;
            })->values(),
            'puntosAnotados' => $estadisticas->pluck('puntos_anotados')->values(),
            'puntosRecibidos' => $estadisticas->map(fn (EstadisticaEquipo $estadistica) => $estadistica->estadisticaRival()?->puntos_anotados)->values(),
            'diferencias' => $estadisticas->map(fn (EstadisticaEquipo $estadistica) => $this->diferenciaEstadistica($estadistica))->values(),
            'victorias' => $analisisEquipo['victorias'],
            'derrotas' => $analisisEquipo['derrotas'],
        ];

        return view('admin.equipos.analisis', ['equipo' => $equipo, 'partidos' => $estadisticas, 'analisisEquipo' => $analisisEquipo, 'chartData' => $chartData]);
    }

    ////////////////
    /// BUSCADOR///
    //////////////
    public function search(Request $request)
    {
        $search = $request->get('search');

        $equipos = Equipo::with('category')
            ->withCount('jugadores')
            ->where('nombre', 'like', '%' . $search . '%')
            ->when($request->boolean('locales'), fn ($query) => $query->locales())
            ->orderBy('nombre', 'ASC')
            ->paginate(10); 

        // Importante para que al cambiar de página no se pierda la búsqueda
        $equipos->appends($request->only(['search', 'locales']));
        $mostrarLocales = $request->boolean('locales');

        return view('admin.equipos.index', compact('equipos', 'search', 'mostrarLocales'));
    }

    private function eliminarImagenSiExiste(?string $rutaImagen): void
    {
        ImagePath::deleteFromDirectories($rutaImagen, Equipo::IMAGE_DIRECTORIES);
    }

    private function normalizarRutaImagen(?string $rutaImagen): ?string
    {
        return ImagePath::normalizeFromDirectories($rutaImagen, Equipo::IMAGE_DIRECTORIES);
    }

    private function calcularAnalisisEquipo($estadisticas): array
    {
        $estadisticasConResultado = $estadisticas
            ->filter(fn (EstadisticaEquipo $estadistica) => $estadistica->puntos_anotados !== null && $estadistica->estadisticaRival()?->puntos_anotados !== null)
            ->values();

        $partidosJugados = $estadisticasConResultado->count();

        if ($partidosJugados === 0) {
            return [
                'partidos_jugados' => 0,
                'victorias' => 0,
                'derrotas' => 0,
                'media_puntos_anotados' => null,
                'media_puntos_recibidos' => null,
                'diferencia_media' => null,
                'mejor_partido_ofensivo' => null,
                'peor_partido_defensivo' => null,
            ];
        }

        return [
            'partidos_jugados' => $partidosJugados,
            'victorias' => $estadisticasConResultado->filter(fn (EstadisticaEquipo $estadistica) => $this->diferenciaEstadistica($estadistica) > 0)->count(),
            'derrotas' => $estadisticasConResultado->filter(fn (EstadisticaEquipo $estadistica) => $this->diferenciaEstadistica($estadistica) < 0)->count(),
            'media_puntos_anotados' => round($estadisticasConResultado->avg('puntos_anotados'), 1),
            'media_puntos_recibidos' => round($estadisticasConResultado->map(fn (EstadisticaEquipo $estadistica) => $estadistica->estadisticaRival()?->puntos_anotados)->avg(), 1),
            'diferencia_media' => round($estadisticasConResultado->map(fn (EstadisticaEquipo $estadistica) => $this->diferenciaEstadistica($estadistica))->avg(), 1),
            'mejor_partido_ofensivo' => $estadisticasConResultado->sortByDesc('puntos_anotados')->first(),
            'peor_partido_defensivo' => $estadisticasConResultado->sortByDesc(fn (EstadisticaEquipo $estadistica) => $estadistica->estadisticaRival()?->puntos_anotados)->first(),
        ];
    }

    private function partidosAnalisisEquipo(Equipo $equipo)
    {
        return Partido::query()
            ->where('estadisticas_equipo_id', $equipo->id)
            ->jugados()
            ->orderBy('fecha_partido');
    }

    private function estadisticasAnalisisEquipo(Equipo $equipo)
    {
        return EstadisticaEquipo::query()
            ->where('equipo_id', $equipo->id)
            ->whereHas('equipo', fn ($query) => $query->where('es_local', true))
            ->whereHas('partido', fn ($query) => $query->jugados())
            ->join('partidos', 'partidos.id', '=', 'estadisticas_equipos.partido_id')
            ->select('estadisticas_equipos.*')
            ->orderBy('partidos.fecha_partido');
    }

    private function diferenciaEstadistica(EstadisticaEquipo $estadistica): ?int
    {
        $puntosRival = $estadistica->estadisticaRival()?->puntos_anotados;

        if ($estadistica->puntos_anotados === null || $puntosRival === null) {
            return null;
        }

        return $estadistica->puntos_anotados - $puntosRival;
    }
}
