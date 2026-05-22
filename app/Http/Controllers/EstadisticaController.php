<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipo;
use App\Models\Estadistica;
use App\Models\Partido;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstadisticaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $equipos = Equipo::locales()
            ->with('category')
            ->withCount(['partidosConEstadisticas as partidos_jugados_count' => fn ($query) => $query->jugados()])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('categoria', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('admin.estadisticas.index', compact('equipos', 'search'));
    }

    public function create()
    {
        $partidos = Partido::with(['equipoLocal', 'equipoVisitante', 'equipoEstadisticas', 'estadistica'])
            ->jugados()
            ->whereHas('equipoEstadisticas', fn ($query) => $query->where('es_local', true))
            ->orderByDesc('fecha_partido')
            ->get();

        return view('admin.estadisticas.create', [
            'partidos' => $partidos,
            'partidoSeleccionado' => request()->integer('partido'),
        ]);
    }

    public function store(Request $request)
    {
        $datosValidados = $this->validarEstadistica($request);

        Estadistica::create($datosValidados);

        return redirect()->route('estadisticas.index')->with('mensaje', 'Estadística creada correctamente');
    }

    public function show($id)
    {
        $estadistica = Estadistica::with(['partido.equipoLocal.category', 'partido.equipoVisitante.category', 'partido.category'])->findOrFail($id);

        return view('admin.estadisticas.show', compact('estadistica'));
    }

    public function edit($id)
    {
        $estadistica = Estadistica::with(['partido.equipoLocal', 'partido.equipoVisitante'])->findOrFail($id);

        return view('admin.estadisticas.edit', compact('estadistica'));
    }

    public function update(Request $request, $id)
    {
        $estadistica = Estadistica::findOrFail($id);

        $request->merge([
            'partido_id' => $estadistica->partido_id,
        ]);

        $datosValidados = $this->validarEstadistica($request, $estadistica->id);

        $estadistica->update($datosValidados);

        return redirect()->route('estadisticas.index')->with('mensaje', 'Estadística actualizada correctamente');
    }

    public function destroy($id)
    {
        $estadistica = Estadistica::findOrFail($id);
        $estadistica->delete();

        return redirect()->route('estadisticas.index')->with('mensaje', 'Estadística eliminada correctamente');
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    private function validarEstadistica(Request $request, ?int $estadisticaId = null): array
    {
        $datos = $request->validate([
            'partido_id' => [
                'required',
                Rule::exists('partidos', 'id')->where('estado', 'jugado'),
                Rule::unique('estadisticas')
                    ->ignore($estadisticaId),
            ],
            'puntos_totales' => 'required|integer|min:0',
            'rebotes' => 'required|integer|min:0',
            'asistencias' => 'required|integer|min:0',
            'robos' => 'required|integer|min:0',
            'rebotes_defensivos' => 'required|integer|min:0',
            'rebotes_ofensivos' => 'required|integer|min:0',
            'tapones' => 'required|integer|min:0',
        ], [
            'partido_id.required' => 'Debes seleccionar un partido.',
            'partido_id.exists' => 'El partido seleccionado no existe o todavía no está marcado como jugado.',
            'partido_id.unique' => 'Ese partido ya tiene estadísticas registradas.',
        ]);

        $partido = Partido::with('equipoEstadisticas')->find($datos['partido_id']);

        if (!$partido?->equipoEstadisticas?->es_local) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'partido_id' => 'Solo se pueden crear estadísticas para partidos con un equipo Bellreguard asignado.',
            ]);
        }

        return $datos;
    }
}
