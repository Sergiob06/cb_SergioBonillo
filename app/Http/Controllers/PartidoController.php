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

        $partidos = Partido::with(['equipoLocal.category', 'equipoVisitante.category', 'equipoEstadisticas.category', 'category', 'estadisticasEquipos'])
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

        $partido = Partido::create([
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

        $this->sincronizarEstadisticasEquipos($partido, $datosValidados, $equipoLocal, $equipoVisitante);

        return redirect()->route('partidos.index')->with('mensaje', 'Partido creado correctamente');
    }

    public function show($id)
    {
        $partido = Partido::with(['equipoLocal.category', 'equipoVisitante.category', 'equipoEstadisticas.category', 'category', 'estadisticasEquipos.equipo'])->findOrFail($id);

        return view('admin.partidos.show', compact('partido'));
    }

    public function edit($id)
    {
        $partido = Partido::with('estadisticasEquipos')->findOrFail($id);
        $equipos = Equipo::orderBy('nombre', 'asc')->get();
        $equiposLocales = $equipos->where('es_local', true)->values();

        return view('admin.partidos.edit', compact('partido', 'equipos', 'equiposLocales'));
    }

    public function editEstadisticas(Partido $partido)
    {
        $partido->load(['equipoLocal', 'equipoVisitante', 'estadisticasEquipos.equipo']);

        if (!$partido->es_jugado) {
            return redirect()
                ->route('partidos.show', $partido)
                ->with('mensaje_error', 'Las estadísticas solo pueden añadirse cuando el partido ya ha sido jugado.');
        }

        return view('admin.partidos.estadisticas', compact('partido'));
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

        $this->sincronizarEstadisticasEquipos($partido, $datosValidados, $equipoLocal, $equipoVisitante);

        return redirect()->route('partidos.index')->with('mensaje', 'Partido actualizado correctamente');
    }

    public function updateEstadisticas(Request $request, Partido $partido)
    {
        $partido->load(['equipoLocal', 'equipoVisitante']);

        if (!$partido->es_jugado) {
            throw ValidationException::withMessages([
                'estadisticas' => 'Las estadísticas solo pueden añadirse cuando el partido ya ha sido jugado.',
            ]);
        }

        if (!$partido->equipoLocal || !$partido->equipoVisitante) {
            throw ValidationException::withMessages([
                'estadisticas' => 'El partido debe tener equipo local y visitante para introducir estadísticas.',
            ]);
        }

        $datosValidados = $this->validarEstadisticasEquipos($request);
        $estadisticasLocal = $datosValidados['estadisticas']['local'];
        $estadisticasVisitante = $datosValidados['estadisticas']['visitante'];

        $this->guardarEstadisticaEquipo(
            $partido,
            $partido->equipoLocal,
            true,
            $estadisticasLocal,
            $partido->puntos_local
        );

        $this->guardarEstadisticaEquipo(
            $partido,
            $partido->equipoVisitante,
            false,
            $estadisticasVisitante,
            $partido->puntos_visitante
        );

        $this->sincronizarResultadoDesdeEstadisticas(
            $partido,
            (int) $estadisticasLocal['puntos_anotados'],
            (int) $estadisticasVisitante['puntos_anotados']
        );

        return redirect()->route('partidos.show', $partido)->with('mensaje', 'Estadísticas actualizadas correctamente');
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
            'estadisticas' => 'nullable|array',
            'estadisticas.local' => 'nullable|array',
            'estadisticas.visitante' => 'nullable|array',
            'estadisticas.local.puntos_anotados' => 'nullable|integer|min:0|max:300',
            'estadisticas.visitante.puntos_anotados' => 'nullable|integer|min:0|max:300',
            'estadisticas.local.t2_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.t2_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.t3_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.t3_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.tl_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.tl_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.balones_perdidos' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.balones_perdidos' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.rebotes_ofensivos' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.rebotes_ofensivos' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.tiros_anotados' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.tiros_anotados' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.rebotes_defensivos' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.rebotes_defensivos' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.asistencias' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.asistencias' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.robos' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.robos' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.tapones' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.tapones' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.faltas' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.faltas' => 'nullable|integer|min:0|max:200',
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

    private function validarEstadisticasEquipos(Request $request): array
    {
        return $request->validate([
            'estadisticas' => 'required|array',
            'estadisticas.local' => 'required|array',
            'estadisticas.visitante' => 'required|array',
            'estadisticas.local.puntos_anotados' => 'required|integer|min:0|max:300',
            'estadisticas.visitante.puntos_anotados' => 'required|integer|min:0|max:300',
            'estadisticas.local.t2_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.t2_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.t3_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.t3_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.tl_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.tl_intentados' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.balones_perdidos' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.balones_perdidos' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.rebotes_ofensivos' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.rebotes_ofensivos' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.tiros_anotados' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.tiros_anotados' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.rebotes_defensivos' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.rebotes_defensivos' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.asistencias' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.asistencias' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.robos' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.robos' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.tapones' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.tapones' => 'nullable|integer|min:0|max:200',
            'estadisticas.local.faltas' => 'nullable|integer|min:0|max:200',
            'estadisticas.visitante.faltas' => 'nullable|integer|min:0|max:200',
        ], [
            'estadisticas.local.puntos_anotados.required' => 'Debes indicar los puntos anotados del equipo local para actualizar el resultado.',
            'estadisticas.visitante.puntos_anotados.required' => 'Debes indicar los puntos anotados del equipo visitante para actualizar el resultado.',
        ]);
    }

    private function normalizarMarcador(array $datos): array
    {
        if (($datos['estado'] ?? 'proximo') === 'proximo') {
            $datos['puntos_local'] = null;
            $datos['puntos_visitante'] = null;
            $datos['estadisticas'] = [];
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

        return $equiposBellreguard->first();
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

    private function sincronizarEstadisticasEquipos(Partido $partido, array $datos, Equipo $equipoLocal, Equipo $equipoVisitante): void
    {
        if (($datos['estado'] ?? 'proximo') !== 'jugado') {
            $partido->estadisticasEquipos()->delete();
            return;
        }

        $partido->estadisticasEquipos()
            ->whereNotIn('equipo_id', [$equipoLocal->id, $equipoVisitante->id])
            ->delete();

        $estadisticas = $datos['estadisticas'] ?? [];

        $this->guardarEstadisticaEquipo(
            $partido,
            $equipoLocal,
            true,
            $estadisticas['local'] ?? [],
            $datos['puntos_local'] ?? null
        );

        $this->guardarEstadisticaEquipo(
            $partido,
            $equipoVisitante,
            false,
            $estadisticas['visitante'] ?? [],
            $datos['puntos_visitante'] ?? null
        );

        $this->sincronizarResultadoDesdeEstadisticas(
            $partido,
            (int) ($estadisticas['local']['puntos_anotados'] ?? $datos['puntos_local']),
            (int) ($estadisticas['visitante']['puntos_anotados'] ?? $datos['puntos_visitante'])
        );
    }

    private function guardarEstadisticaEquipo(Partido $partido, Equipo $equipo, bool $esLocal, array $estadisticas, ?int $puntosMarcador): void
    {
        $campos = [
            'puntos_anotados',
            't2_intentados',
            't3_intentados',
            'tl_intentados',
            'balones_perdidos',
            'rebotes_ofensivos',
            'tiros_anotados',
            'rebotes_defensivos',
            'asistencias',
            'robos',
            'tapones',
            'faltas',
        ];

        $datos = [
            'es_local' => $esLocal,
            'puntos_anotados' => $estadisticas['puntos_anotados'] ?? $puntosMarcador,
        ];

        foreach ($campos as $campo) {
            if ($campo === 'puntos_anotados') {
                continue;
            }

            $datos[$campo] = $estadisticas[$campo] ?? null;
        }

        $partido->estadisticasEquipos()->updateOrCreate([
            'equipo_id' => $equipo->id,
        ], $datos);
    }

    private function sincronizarResultadoDesdeEstadisticas(Partido $partido, int $puntosLocal, int $puntosVisitante): void
    {
        $partido->forceFill([
            'puntos_local' => $puntosLocal,
            'puntos_visitante' => $puntosVisitante,
        ])->save();
    }
}
