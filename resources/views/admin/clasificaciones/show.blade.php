@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Detalle de Clasificación</h2>
        <p style="color: #777;">Información completa de la fila seleccionada</p>
    </div>
    <a href="{{ route('clasificaciones.index') }}" class="btn-nuevo" style="background-color: #777;"><i class="fas fa-arrow-left"></i> Volver al listado</a>
</header>

<div class="pizarra-admin">
    <div style="padding: 20px;">
        <h3 style="margin-top: 0;">{{ $clasificacion->equipo_nombre }}</h3>
        <p style="color: #777;">{{ $clasificacion->categoria }} · {{ $clasificacion->temporada }}</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin: 25px 0;">
            <div class="card-stat"><div class="stat-info"><h3>{{ $clasificacion->posicion }}</h3><p>Posición</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $clasificacion->partidos_jugados }}</h3><p>Partidos Jugados</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $clasificacion->partidos_ganados }}</h3><p>Ganados</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $clasificacion->partidos_perdidos }}</h3><p>Perdidos</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $clasificacion->puntos_favor }}</h3><p>Puntos a Favor</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $clasificacion->puntos_contra }}</h3><p>Puntos en Contra</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3>{{ $clasificacion->puntos }}</h3><p>Puntos</p></div></div>
        </div>
    </div>
</div>
@endsection
