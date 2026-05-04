<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasificacionController extends Controller
{
    public function index()
    {
        $clasificaciones = Clasificacion::with('equipo')
            ->orderBy('categoria')
            ->orderBy('temporada', 'desc')
            ->orderBy('posicion')
            ->paginate(15);

        return view('admin.clasificaciones.index', compact('clasificaciones'));
    }

    public function create()
    {
        $equipos = Equipo::orderBy('nombre')->get();

        return view('admin.clasificaciones.create', compact('equipos'));
    }

    public function store(Request $request)
    {
        $datosValidados = $this->validarClasificacion($request);
        $datosValidados = $this->normalizarClasificacion($datosValidados);

        Clasificacion::create($datosValidados);

        return redirect()->route('clasificaciones.index')->with('mensaje', 'Clasificación creada correctamente');
    }

    public function show($id)
    {
        $clasificacion = Clasificacion::with('equipo')->findOrFail($id);

        return view('admin.clasificaciones.show', compact('clasificacion'));
    }

    public function edit($id)
    {
        $clasificacion = Clasificacion::findOrFail($id);
        $equipos = Equipo::orderBy('nombre')->get();

        return view('admin.clasificaciones.edit', compact('clasificacion', 'equipos'));
    }

    public function update(Request $request, $id)
    {
        $clasificacion = Clasificacion::findOrFail($id);
        $datosValidados = $this->validarClasificacion($request, $clasificacion->id);
        $datosValidados = $this->normalizarClasificacion($datosValidados);

        $clasificacion->update($datosValidados);

        return redirect()->route('clasificaciones.index')->with('mensaje', 'Clasificación actualizada correctamente');
    }

    public function destroy($id)
    {
        $clasificacion = Clasificacion::findOrFail($id);
        $clasificacion->delete();

        return redirect()->route('clasificaciones.index')->with('mensaje', 'Clasificación eliminada correctamente');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        $clasificaciones = Clasificacion::with('equipo')
            ->where(function ($query) use ($search) {
                $query->where('equipo_nombre', 'like', '%' . $search . '%')
                    ->orWhere('categoria', 'like', '%' . $search . '%')
                    ->orWhere('temporada', 'like', '%' . $search . '%');
            })
            ->orderBy('categoria')
            ->orderBy('temporada', 'desc')
            ->orderBy('posicion')
            ->paginate(15);

        $clasificaciones->appends(['search' => $search]);

        return view('admin.clasificaciones.index', compact('clasificaciones', 'search'));
    }

    private function validarClasificacion(Request $request, ?int $clasificacionId = null): array
    {
        return $request->validate([
            'equipo_id' => 'nullable|exists:equipos,id',
            'equipo_nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'temporada' => 'required|string|max:255',
            'posicion' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('clasificaciones')
                    ->where(function ($query) use ($request) {
                        return $query->where('categoria', $request->categoria)
                            ->where('temporada', $request->temporada)
                            ->where('posicion', $request->posicion);
                    })
                    ->ignore($clasificacionId),
            ],
            'partidos_jugados' => 'required|integer|min:0',
            'partidos_ganados' => 'required|integer|min:0',
            'partidos_perdidos' => 'required|integer|min:0',
            'puntos_favor' => 'required|integer|min:0',
            'puntos_contra' => 'required|integer|min:0',
            'puntos' => 'required|integer|min:0',
        ], [
            'equipo_nombre.required' => 'El nombre del equipo es obligatorio.',
            'categoria.required' => 'La categoría es obligatoria.',
            'temporada.required' => 'La temporada es obligatoria.',
            'posicion.unique' => 'Ya existe una fila con esa posición para esa categoría y temporada.',
        ]);
    }

    private function normalizarClasificacion(array $datosValidados): array
    {
        if (!empty($datosValidados['equipo_id'])) {
            $equipo = Equipo::find($datosValidados['equipo_id']);

            if ($equipo) {
                $datosValidados['equipo_nombre'] = $equipo->nombre;
                if (empty($datosValidados['categoria'])) {
                    $datosValidados['categoria'] = $equipo->categoria;
                }
            }
        }

        return $datosValidados;
    }
}
