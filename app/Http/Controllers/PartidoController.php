<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipo;
use App\Models\Partido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PartidoController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $categoriaSeleccionada = $request->integer('categoria');
        $estadoSeleccionado = $request->get('estado');
        $categories = Category::orderBy('name')->get();

        $partidos = Partido::with(['equipoLocal.category', 'equipoVisitante.category', 'equipoEstadisticas.category', 'category'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('equipo_local', 'like', '%' . $search . '%')
                        ->orWhere('equipo_visitante', 'like', '%' . $search . '%')
                        ->orWhere('lugar', 'like', '%' . $search . '%')
                        ->orWhereHas('equipoLocal', function ($subQuery) use ($search) {
                            $subQuery->where('nombre', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('equipoVisitante', function ($subQuery) use ($search) {
                            $subQuery->where('nombre', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($categoriaSeleccionada, function ($query) use ($categoriaSeleccionada) {
                $query->where('category_id', $categoriaSeleccionada);
            })
            ->when(in_array($estadoSeleccionado, ['proximo', 'jugado'], true), function ($query) use ($estadoSeleccionado) {
                $query->where('estado', $estadoSeleccionado);
            })
            ->orderBy('fecha_partido', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.partidos.index', compact('partidos', 'categories', 'categoriaSeleccionada', 'estadoSeleccionado', 'search'));
    }

    public function create()
    {
        $equipos = Equipo::orderBy('nombre', 'asc')->get();
        $equiposLocales = $equipos->where('es_local', true)->values();

        return view('admin.partidos.create', compact('equipos', 'equiposLocales'));
    }

    public function store(Request $request)
    {
        $datosValidados = $this->validarPartido($request);
        $datosValidados = $this->normalizarMarcador($datosValidados);
        $equipoLocal = Equipo::findOrFail($datosValidados['equipo_local_id']);
        $equipoVisitante = Equipo::findOrFail($datosValidados['equipo_visitante_id']);
        $equipoEstadisticas = $this->resolverEquipoEstadisticas($datosValidados, $equipoLocal, $equipoVisitante);
        $datosValidados = $this->normalizarEstadisticas($datosValidados, $equipoEstadisticas);

        Partido::create([
            'equipo_local_id' => $equipoLocal->id,
            'equipo_visitante_id' => $equipoVisitante->id,
            'estadisticas_equipo_id' => $equipoEstadisticas?->id,
            'category_id' => $equipoEstadisticas?->category_id ?? $equipoLocal->category_id,
            'equipo_local' => $equipoLocal->nombre,
            'equipo_visitante' => $equipoVisitante->nombre,
            'fecha_partido' => Carbon::parse($datosValidados['fecha_partido']),
            'estado' => $datosValidados['estado'],
            'lugar' => $datosValidados['lugar'],
            'puntos_local' => $datosValidados['puntos_local'] ?? null,
            'puntos_visitante' => $datosValidados['puntos_visitante'] ?? null,
            'triples' => $datosValidados['triples'] ?? null,
            'tiros_libres' => $datosValidados['tiros_libres'] ?? null,
            'rebotes' => $datosValidados['rebotes'] ?? null,
            'asistencias' => $datosValidados['asistencias'] ?? null,
            'robos' => $datosValidados['robos'] ?? null,
            'perdidas' => $datosValidados['perdidas'] ?? null,
            'faltas' => $datosValidados['faltas'] ?? null,
        ]);

        return redirect()->route('partidos.index')->with('mensaje', 'Partido creado correctamente');
    }

    public function show($id)
    {
        $partido = Partido::with(['equipoLocal.category', 'equipoVisitante.category', 'equipoEstadisticas.category', 'category'])->findOrFail($id);

        return view('admin.partidos.show', compact('partido'));
    }

    public function edit($id)
    {
        $partido = Partido::findOrFail($id);
        $equipos = Equipo::orderBy('nombre', 'asc')->get();
        $equiposLocales = $equipos->where('es_local', true)->values();

        return view('admin.partidos.edit', compact('partido', 'equipos', 'equiposLocales'));
    }

    public function update(Request $request, $id)
    {
        $datosValidados = $this->validarPartido($request);
        $datosValidados = $this->normalizarMarcador($datosValidados);
        $partido = Partido::findOrFail($id);
        $equipoLocal = Equipo::findOrFail($datosValidados['equipo_local_id']);
        $equipoVisitante = Equipo::findOrFail($datosValidados['equipo_visitante_id']);
        $equipoEstadisticas = $this->resolverEquipoEstadisticas($datosValidados, $equipoLocal, $equipoVisitante);
        $datosValidados = $this->normalizarEstadisticas($datosValidados, $equipoEstadisticas);

        $partido->equipo_local_id = $equipoLocal->id;
        $partido->equipo_visitante_id = $equipoVisitante->id;
        $partido->estadisticas_equipo_id = $equipoEstadisticas?->id;
        $partido->category_id = $equipoEstadisticas?->category_id ?? $equipoLocal->category_id;
        $partido->equipo_local = $equipoLocal->nombre;
        $partido->equipo_visitante = $equipoVisitante->nombre;
        $partido->fecha_partido = Carbon::parse($datosValidados['fecha_partido']);
        $partido->estado = $datosValidados['estado'];
        $partido->lugar = $datosValidados['lugar'];
        $partido->puntos_local = $datosValidados['puntos_local'] ?? null;
        $partido->puntos_visitante = $datosValidados['puntos_visitante'] ?? null;
        $partido->triples = $datosValidados['triples'] ?? null;
        $partido->tiros_libres = $datosValidados['tiros_libres'] ?? null;
        $partido->rebotes = $datosValidados['rebotes'] ?? null;
        $partido->asistencias = $datosValidados['asistencias'] ?? null;
        $partido->robos = $datosValidados['robos'] ?? null;
        $partido->perdidas = $datosValidados['perdidas'] ?? null;
        $partido->faltas = $datosValidados['faltas'] ?? null;
        $partido->save();

        return redirect()->route('partidos.index')->with('mensaje', 'Partido actualizado correctamente');
    }

    public function destroy($id)
    {
        $partido = Partido::findOrFail($id);
        $partido->delete();

        return redirect()->route('partidos.index')->with('mensaje', 'Partido eliminado correctamente');
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    private function validarPartido(Request $request): array
    {
        $datos = $request->validate([
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id|different:equipo_local_id',
            'estadisticas_equipo_id' => 'nullable|exists:equipos,id',
            'fecha_partido' => 'required|date',
            'estado' => 'required|in:proximo,jugado',
            'lugar' => 'required|string|max:255',
            'puntos_local' => 'nullable|required_if:estado,jugado|required_with:puntos_visitante|integer|min:0|max:300',
            'puntos_visitante' => 'nullable|required_if:estado,jugado|required_with:puntos_local|integer|min:0|max:300',
            'triples' => 'nullable|integer|min:0|max:99',
            'tiros_libres' => 'nullable|integer|min:0|max:99',
            'rebotes' => 'nullable|integer|min:0|max:200',
            'asistencias' => 'nullable|integer|min:0|max:150',
            'robos' => 'nullable|integer|min:0|max:99',
            'perdidas' => 'nullable|integer|min:0|max:99',
            'faltas' => 'nullable|integer|min:0|max:99',
        ], [
            'equipo_local_id.required' => 'Debes seleccionar el equipo local.',
            'equipo_local_id.exists' => 'El equipo local seleccionado no existe.',
            'equipo_local_id.different' => 'El equipo local y el visitante no pueden ser iguales.',
            'equipo_visitante_id.required' => 'Debes seleccionar el equipo visitante.',
            'equipo_visitante_id.exists' => 'El equipo visitante seleccionado no existe.',
            'equipo_visitante_id.different' => 'El equipo visitante y el local no pueden ser iguales.',
            'fecha_partido.required' => 'La fecha del partido es obligatoria.',
            'fecha_partido.date' => 'La fecha del partido no tiene un formato válido.',
            'estado.required' => 'Debes indicar si el partido es próximo o jugado.',
            'estado.in' => 'El estado del partido no es válido.',
            'lugar.required' => 'El lugar del partido es obligatorio.',
            'puntos_local.required_if' => 'Si el partido está jugado, introduce los puntos del equipo local.',
            'puntos_visitante.required_if' => 'Si el partido está jugado, introduce los puntos del visitante.',
            'puntos_local.required_with' => 'Introduce también los puntos del equipo local.',
            'puntos_visitante.required_with' => 'Introduce también los puntos del visitante.',
        ]);

        return $datos;
    }

    private function normalizarMarcador(array $datos): array
    {
        if (($datos['estado'] ?? 'proximo') === 'proximo') {
            $datos['puntos_local'] = null;
            $datos['puntos_visitante'] = null;
            foreach (['triples', 'tiros_libres', 'rebotes', 'asistencias', 'robos', 'perdidas', 'faltas'] as $campo) {
                $datos[$campo] = null;
            }
        }

        return $datos;
    }

    private function resolverEquipoEstadisticas(array $datos, Equipo $equipoLocal, Equipo $equipoVisitante): ?Equipo
    {
        $equiposBellreguard = collect([$equipoLocal, $equipoVisitante])
            ->filter(fn (Equipo $equipo) => $equipo->es_local)
            ->values();

        if ($equiposBellreguard->isEmpty()) {
            return null;
        }

        $equipoSeleccionadoId = $datos['estadisticas_equipo_id'] ?? null;

        if ($equipoSeleccionadoId) {
            $equipoSeleccionado = $equiposBellreguard->firstWhere('id', (int) $equipoSeleccionadoId);

            if (!$equipoSeleccionado) {
                throw ValidationException::withMessages([
                    'estadisticas_equipo_id' => 'Las estadísticas solo pueden asignarse a un equipo de Bellreguard que participe en el partido.',
                ]);
            }

            return $equipoSeleccionado;
        }

        if (($datos['estado'] ?? 'proximo') === 'proximo') {
            return $equiposBellreguard->first();
        }

        if ($equiposBellreguard->count() === 1) {
            return $equiposBellreguard->first();
        }

        throw ValidationException::withMessages([
            'estadisticas_equipo_id' => 'Si participan dos equipos de Bellreguard, selecciona a qué equipo pertenecen las estadísticas.',
        ]);
    }

    private function normalizarEstadisticas(array $datos, ?Equipo $equipoEstadisticas): array
    {
        if (!$equipoEstadisticas) {
            foreach (['triples', 'tiros_libres', 'rebotes', 'asistencias', 'robos', 'perdidas', 'faltas'] as $campo) {
                $datos[$campo] = null;
            }

            $datos['estadisticas_equipo_id'] = null;
            return $datos;
        }

        $datos['estadisticas_equipo_id'] = $equipoEstadisticas->id;

        return $datos;
    }
}
