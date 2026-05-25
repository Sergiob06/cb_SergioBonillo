<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipo;
use App\Models\Galeria;
use App\Models\Partido;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function inicio()
    {
        $proximosPartidos = Partido::with(['equipoLocal', 'equipoVisitante'])
            ->proximos()
            ->orderBy('fecha_partido', 'asc')
            ->limit(3)
            ->get();

        $ultimasFotos = Galeria::orderBy('fecha_imagen', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('basket.inicio', compact('proximosPartidos', 'ultimasFotos'));
    }

    public function estadisticas(Request $request)
    {
        $equipoSeleccionado = $request->integer('equipo');
        $categoriaSeleccionada = $request->integer('categoria');
        $search = trim((string) $request->get('search', ''));

        $equiposLocales = Equipo::where('es_local', true)->orderBy('nombre')->get();
        $categories = Category::orderBy('name')->get();

        $partidos = Partido::with(['category', 'equipoLocal.category', 'equipoVisitante.category', 'equipoEstadisticas.category', 'estadisticasEquipos.equipo'])
            ->jugados()
            ->whereHas('estadisticasEquipos.equipo', fn ($query) => $query->where('es_local', true))
            ->when($equipoSeleccionado, fn ($query) => $query->whereHas('estadisticasEquipos', fn ($statsQuery) => $statsQuery->where('equipo_id', $equipoSeleccionado)))
            ->when($categoriaSeleccionada, fn ($query) => $query->where('category_id', $categoriaSeleccionada))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('equipo_local', 'like', '%' . $search . '%')
                        ->orWhere('equipo_visitante', 'like', '%' . $search . '%')
                        ->orWhere('lugar', 'like', '%' . $search . '%')
                        ->orWhere('fecha_partido', 'like', '%' . $search . '%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('equipoLocal', function ($equipoQuery) use ($search) {
                            $equipoQuery->where('nombre', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('equipoVisitante', function ($equipoQuery) use ($search) {
                            $equipoQuery->where('nombre', 'like', '%' . $search . '%');
                        });
                    });
            })
            ->orderByDesc('fecha_partido')
            ->get();

        return view('basket.estadisticas', compact('partidos', 'equiposLocales', 'categories', 'equipoSeleccionado', 'categoriaSeleccionada', 'search'));
    }

    public function partidos(Request $request)
    {
        $equipoSeleccionado = $request->integer('equipo');
        $categoriaSeleccionada = $request->integer('categoria');
        $search = trim((string) $request->get('search', ''));
        $equiposLocales = Equipo::where('es_local', true)->orderBy('nombre')->get();
        $categories = Category::orderBy('name')->get();

        $partidos = Partido::with(['equipoLocal.category', 'equipoVisitante.category', 'equipoEstadisticas.category', 'category', 'estadisticasEquipos.equipo'])
            ->when($equipoSeleccionado, function ($query) use ($equipoSeleccionado) {
                $query->where(function ($equipoQuery) use ($equipoSeleccionado) {
                    $equipoQuery->where('equipo_local_id', $equipoSeleccionado)
                        ->orWhere('equipo_visitante_id', $equipoSeleccionado);
                });
            })
            ->when($categoriaSeleccionada, function ($query) use ($categoriaSeleccionada) {
                $query->where('category_id', $categoriaSeleccionada);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('equipo_local', 'like', '%' . $search . '%')
                        ->orWhere('equipo_visitante', 'like', '%' . $search . '%')
                        ->orWhere('lugar', 'like', '%' . $search . '%')
                        ->orWhere('fecha_partido', 'like', '%' . $search . '%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('equipoLocal', function ($equipoQuery) use ($search) {
                            $equipoQuery->where('nombre', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('equipoVisitante', function ($equipoQuery) use ($search) {
                            $equipoQuery->where('nombre', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('fecha_partido', 'asc')
            ->get();

        $partidosAgrupados = $partidos->groupBy(function ($partido) use ($equipoSeleccionado) {
            if ($equipoSeleccionado) {
                return $partido->equipoLocal?->id === $equipoSeleccionado
                    ? ($partido->equipoLocal?->nombre ?? $partido->equipo_local ?? 'Partidos')
                    : ($partido->equipoVisitante?->nombre ?? $partido->equipo_visitante ?? 'Partidos');
            }

            return $partido->equipoLocal?->nombre
                ?? $partido->equipo_local
                ?? 'Partidos';
        });

        return view('basket.partidos', compact('partidos', 'partidosAgrupados', 'equiposLocales', 'equipoSeleccionado', 'categories', 'categoriaSeleccionada', 'search'));
    }

    public function partido(Partido $partido)
    {
        $partido->load(['equipoLocal.category', 'equipoVisitante.category', 'equipoEstadisticas.category', 'estadisticasEquipos.equipo']);

        return view('basket.partido', compact('partido'));
    }

    public function galeria()
    {
        $galerias = Galeria::query()
            ->orderBy('fecha_imagen', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('basket.galeria', compact('galerias'));
    }

    public function contacto()
    {
        return view('basket.contacto');
    }
}
