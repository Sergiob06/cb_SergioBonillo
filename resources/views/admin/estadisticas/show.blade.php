@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Ficha de Estadísticas</h2>
        <p style="color: #777;">Detalle del rendimiento por temporada</p>
    </div>

    <a href="{{ route('estadisticas.index') }}" class="btn-nuevo" style="background-color: #777;">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
</header>

<div class="pizarra-admin">
    <div style="padding: 20px;">
        <h3 style="margin-top: 0;">{{ $estadistica->equipo->nombre ?? 'Sin equipo' }} - {{ $estadistica->temporada }}</h3>
        <p style="color: #777;">{{ $estadistica->equipo->categoria ?? 'Sin categoría' }}</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin: 25px 0;">
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->puntos_totales }}</h3><p>Puntos Totales</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->rebotes }}</h3><p>Rebotes</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->asistencias }}</h3><p>Asistencias</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->robos }}</h3><p>Robos</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->partidos_jugados }}</h3><p>Partidos</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $estadistica->victorias }}-{{ $estadistica->derrotas }}</h3><p>Balance</p></div></div>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('estadisticas.edit', $estadistica->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;">
                <i class="fas fa-pen"></i>
            </a>

            <form action="{{ route('estadisticas.destroy', $estadistica->id) }}" method="POST" onsubmit="return confirm('¿Eliminar estadística?')" style="margin: 0; display: flex; align-items: center;">
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
