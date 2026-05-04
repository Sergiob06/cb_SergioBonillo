<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Estadistica;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstadisticaController extends Controller
{
    public function index()
    {
        $estadisticas = Estadistica::with('equipo')
            ->orderBy('temporada', 'desc')
            ->paginate(10);

        return view('admin.estadisticas.index', compact('estadisticas'));
    }

    public function create()
    {
        $equipos = Equipo::orderBy('nombre', 'asc')->get();

        return view('admin.estadisticas.create', compact('equipos'));
    }

    public function store(Request $request)
    {
        $datosValidados = $this->validarEstadistica($request);

        Estadistica::create($datosValidados);

        return redirect()->route('estadisticas.index')->with('mensaje', 'Estadística creada correctamente');
    }

    public function show($id)
    {
        $estadistica = Estadistica::with('equipo')->findOrFail($id);

        return view('admin.estadisticas.show', compact('estadistica'));
    }

    public function edit($id)
    {
        $estadistica = Estadistica::findOrFail($id);
        $equipos = Equipo::orderBy('nombre', 'asc')->get();

        return view('admin.estadisticas.edit', compact('estadistica', 'equipos'));
    }

    public function update(Request $request, $id)
    {
        $estadistica = Estadistica::findOrFail($id);
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
        $search = $request->get('search');

        $estadisticas = Estadistica::with('equipo')
            ->where(function ($query) use ($search) {
                $query->where('temporada', 'like', '%' . $search . '%')
                    ->orWhereHas('equipo', function ($subQuery) use ($search) {
                        $subQuery->where('nombre', 'like', '%' . $search . '%')
                            ->orWhere('categoria', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('temporada', 'desc')
            ->paginate(10);

        $estadisticas->appends(['search' => $search]);

        return view('admin.estadisticas.index', compact('estadisticas', 'search'));
    }

    private function validarEstadistica(Request $request, ?int $estadisticaId = null): array
    {
        return $request->validate([
            'equipo_id' => [
                'required',
                'exists:equipos,id',
                Rule::unique('estadisticas')
                    ->where(function ($query) use ($request) {
                        return $query->where('equipo_id', $request->equipo_id)
                            ->where('temporada', $request->temporada);
                    })
                    ->ignore($estadisticaId),
            ],
            'temporada' => 'required|string|max:255',
            'puntos_totales' => 'required|integer|min:0',
            'rebotes' => 'required|integer|min:0',
            'asistencias' => 'required|integer|min:0',
            'robos' => 'required|integer|min:0',
            'rebotes_defensivos' => 'required|integer|min:0',
            'rebotes_ofensivos' => 'required|integer|min:0',
            'tapones' => 'required|integer|min:0',
            'partidos_jugados' => 'required|integer|min:0',
            'victorias' => 'required|integer|min:0',
            'derrotas' => 'required|integer|min:0',
        ], [
            'equipo_id.required' => 'Debes seleccionar un equipo.',
            'equipo_id.exists' => 'El equipo seleccionado no existe.',
            'equipo_id.unique' => 'Ya existe una estadística para ese equipo en esa temporada.',
            'temporada.required' => 'La temporada es obligatoria.',
        ]);
    }
}
