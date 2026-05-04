<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Partido;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PartidoController extends Controller
{
    public function index()
    {
        $partidos = Partido::with(['equipoLocal', 'equipoVisitante'])
            ->orderBy('fecha_partido', 'asc')
            ->paginate(10);

        return view('admin.partidos.index', compact('partidos'));
    }

    public function create()
    {
        $equipos = Equipo::orderBy('nombre', 'asc')->get();

        return view('admin.partidos.create', compact('equipos'));
    }

    public function store(Request $request)
    {
        $datosValidados = $this->validarPartido($request);
        $equipoLocal = Equipo::findOrFail($datosValidados['equipo_local_id']);
        $equipoVisitante = Equipo::findOrFail($datosValidados['equipo_visitante_id']);

        Partido::create([
            'equipo_local_id' => $equipoLocal->id,
            'equipo_visitante_id' => $equipoVisitante->id,
            'equipo_local' => $equipoLocal->nombre,
            'equipo_visitante' => $equipoVisitante->nombre,
            'fecha_partido' => Carbon::parse($datosValidados['fecha_partido']),
            'lugar' => $datosValidados['lugar'],
        ]);

        return redirect()->route('partidos.index')->with('mensaje', 'Partido creado correctamente');
    }

    public function show($id)
    {
        $partido = Partido::with(['equipoLocal', 'equipoVisitante'])->findOrFail($id);

        return view('admin.partidos.show', compact('partido'));
    }

    public function edit($id)
    {
        $partido = Partido::findOrFail($id);
        $equipos = Equipo::orderBy('nombre', 'asc')->get();

        return view('admin.partidos.edit', compact('partido', 'equipos'));
    }

    public function update(Request $request, $id)
    {
        $datosValidados = $this->validarPartido($request);
        $partido = Partido::findOrFail($id);
        $equipoLocal = Equipo::findOrFail($datosValidados['equipo_local_id']);
        $equipoVisitante = Equipo::findOrFail($datosValidados['equipo_visitante_id']);

        $partido->equipo_local_id = $equipoLocal->id;
        $partido->equipo_visitante_id = $equipoVisitante->id;
        $partido->equipo_local = $equipoLocal->nombre;
        $partido->equipo_visitante = $equipoVisitante->nombre;
        $partido->fecha_partido = Carbon::parse($datosValidados['fecha_partido']);
        $partido->lugar = $datosValidados['lugar'];
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
        $search = $request->get('search');

        $partidos = Partido::with(['equipoLocal', 'equipoVisitante'])
            ->where(function ($query) use ($search) {
                $query->where('equipo_local', 'like', '%' . $search . '%')
                    ->orWhere('equipo_visitante', 'like', '%' . $search . '%')
                    ->orWhere('lugar', 'like', '%' . $search . '%')
                    ->orWhereHas('equipoLocal', function ($subQuery) use ($search) {
                        $subQuery->where('nombre', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('equipoVisitante', function ($subQuery) use ($search) {
                        $subQuery->where('nombre', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('fecha_partido', 'asc')
            ->paginate(10);

        $partidos->appends(['search' => $search]);

        return view('admin.partidos.index', compact('partidos', 'search'));
    }

    private function validarPartido(Request $request): array
    {
        return $request->validate([
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id|different:equipo_local_id',
            'fecha_partido' => 'required|date',
            'lugar' => 'required|string|max:255',
        ], [
            'equipo_local_id.required' => 'Debes seleccionar el equipo local.',
            'equipo_local_id.exists' => 'El equipo local seleccionado no existe.',
            'equipo_local_id.different' => 'El equipo local y el visitante no pueden ser iguales.',
            'equipo_visitante_id.required' => 'Debes seleccionar el equipo visitante.',
            'equipo_visitante_id.exists' => 'El equipo visitante seleccionado no existe.',
            'equipo_visitante_id.different' => 'El equipo visitante y el local no pueden ser iguales.',
            'fecha_partido.required' => 'La fecha del partido es obligatoria.',
            'fecha_partido.date' => 'La fecha del partido no tiene un formato válido.',
            'lugar.required' => 'El lugar del partido es obligatorio.',
        ]);
    }
}
