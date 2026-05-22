@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Ficha de Estadísticas</h2>
        <p style="color: #777;">Detalle de los totales del partido</p>
    </div>

    <a href="{{ route('estadisticas.index') }}" class="btn-nuevo" style="background-color: #777;">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
</header>

<div class="pizarra-admin">
    <div class="admin-detail-body">
        <h3 style="margin-top: 0;">
            {{ $estadistica->partido?->equipoLocal?->nombre ?? $estadistica->partido?->equipo_local ?? 'Sin local' }}
            vs
            {{ $estadistica->partido?->equipoVisitante?->nombre ?? $estadistica->partido?->equipo_visitante ?? 'Sin visitante' }}
        </h3>
        <p style="color: #777;">
            {{ $estadistica->partido?->fecha_partido?->format('d/m/Y H:i') ?? 'Sin fecha' }}
            · {{ $estadistica->partido?->lugar ?? 'Sin lugar' }}
            · Resultado: {{ $estadistica->partido?->resultado ?? 'Pendiente' }}
        </p>

        <div class="admin-detail-stat-grid">
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->puntos_totales }}</h3><p>Puntos Totales</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->rebotes }}</h3><p>Rebotes</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->asistencias }}</h3><p>Asistencias</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->robos }}</h3><p>Robos</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->rebotes_defensivos }}</h3><p>Rebotes Def.</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->rebotes_ofensivos }}</h3><p>Rebotes Of.</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->tapones }}</h3><p>Tapones</p></div></div>
        </div>

        <div class="admin-detail-actions">
            <a href="{{ route('estadisticas.edit', $estadistica->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;">
                <i class="fas fa-pen"></i>
            </a>

            <form action="{{ route('estadisticas.destroy', $estadistica->id) }}" method="POST" onsubmit="return confirm('¿Eliminar estadística?')" class="admin-detail-inline-form" style="margin: 0; display: flex; align-items: center;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
