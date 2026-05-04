<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use App\Models\Estadistica;
use App\Models\Galeria;
use App\Models\Partido;

class BasketController extends Controller
{
    public function inicio()
    {
        $proximosPartidos = Partido::with(['equipoLocal', 'equipoVisitante'])
            ->where('fecha_partido', '>=', now())
            ->orderBy('fecha_partido', 'asc')
            ->limit(3)
            ->get();

        $ultimasFotos = Galeria::orderBy('fecha_imagen', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $temporadaClasificacion = Clasificacion::query()
            ->orderBy('temporada', 'desc')
            ->value('temporada');

        $categoriaClasificacion = Clasificacion::query()
            ->when($temporadaClasificacion, function ($query) use ($temporadaClasificacion) {
                $query->where('temporada', $temporadaClasificacion);
            })
            ->orderBy('categoria')
            ->value('categoria');

        $clasificacion = Clasificacion::query()
            ->when($temporadaClasificacion, function ($query) use ($temporadaClasificacion) {
                $query->where('temporada', $temporadaClasificacion);
            })
            ->when($categoriaClasificacion, function ($query) use ($categoriaClasificacion) {
                $query->where('categoria', $categoriaClasificacion);
            })
            ->orderBy('posicion')
            ->get();

        return view('basket.inicio', compact('proximosPartidos', 'ultimasFotos', 'clasificacion', 'temporadaClasificacion', 'categoriaClasificacion'));
    }

    public function clasificacion()
    {
        $categorias = Clasificacion::query()
            ->select('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        $categoriaSeleccionada = request('categoria', $categorias->first());

        $temporadas = Clasificacion::query()
            ->where('categoria', $categoriaSeleccionada)
            ->select('temporada')
            ->distinct()
            ->orderBy('temporada', 'desc')
            ->pluck('temporada');

        $temporadaActual = request('temporada', $temporadas->first());

        $clasificacionActual = Clasificacion::with('equipo')
            ->where('categoria', $categoriaSeleccionada)
            ->where('temporada', $temporadaActual)
            ->orderBy('posicion')
            ->get();

        return view('basket.clasificacion', compact('categorias', 'categoriaSeleccionada', 'clasificacionActual', 'temporadaActual', 'temporadas'));
    }

    public function estadisticas()
    {
        $estadisticas = Estadistica::with('equipo')
            ->orderBy('temporada', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        $equiposConEstadisticas = $estadisticas
            ->filter(fn ($estadistica) => $estadistica->equipo)
            ->groupBy('equipo_id')
            ->map(fn ($estadisticasEquipo) => $estadisticasEquipo->first())
            ->sortBy(fn ($estadistica) => $estadistica->equipo?->nombre ?? '')
            ->values();

        $equipoSeleccionado = request()->integer('equipo');

        if (!$equipoSeleccionado || !$equiposConEstadisticas->contains(fn ($estadistica) => $estadistica->equipo_id === $equipoSeleccionado)) {
            $equipoSeleccionado = $equiposConEstadisticas->first()?->equipo_id;
        }

        $estadisticaSeleccionada = $estadisticas
            ->first(fn ($estadistica) => $estadistica->equipo_id === $equipoSeleccionado)
            ?? $estadisticas->first();

        return view('basket.estadisticas', compact('estadisticas', 'equiposConEstadisticas', 'equipoSeleccionado', 'estadisticaSeleccionada'));
    }

    public function partidos()
    {
        $partidos = Partido::with(['equipoLocal', 'equipoVisitante'])
            ->orderBy('fecha_partido', 'asc')
            ->get();

        $partidosAgrupados = $partidos->groupBy(function ($partido) {
            return $partido->equipoLocal?->nombre
                ?? $partido->equipo_local
                ?? 'Partidos';
        });

        return view('basket.partidos', compact('partidosAgrupados'));
    }

    public function galeria()
    {
        $categoriasGaleria = Galeria::query()
            ->select('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        $categoriaSeleccionada = request('categoria');

        $galerias = Galeria::query()
            ->when($categoriaSeleccionada && $categoriaSeleccionada !== 'Todos', function ($query) use ($categoriaSeleccionada) {
                $query->where('categoria', $categoriaSeleccionada);
            })
            ->orderBy('fecha_imagen', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('basket.galeria', compact('galerias', 'categoriaSeleccionada', 'categoriasGaleria'));
    }

    public function contacto()
    {
        return view('basket.contacto');
    }
}
