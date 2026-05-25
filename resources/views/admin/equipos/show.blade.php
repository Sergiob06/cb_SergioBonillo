@extends('layouts.admin')

@section('contenido_admin')
@php($esAdmin = auth()->user()?->rol === 'admin')
<header class="header-admin">
    <h2>Ficha del Equipo: {{ $equipo->nombre }}</h2>
    <a href="{{ route('equipos.index') }}" class="btn-nuevo" style="background-color: #777;">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
</header>

<div class="pizarra-admin">
    <div class="admin-detail-layout admin-detail-layout--media">
        
        <div class="admin-detail-media admin-detail-panel">
            <img src="{{ $equipo->image_url }}" alt="Escudo de {{ $equipo->nombre }}" class="admin-detail-logo">
        </div>

        <div class="admin-detail-content">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fb8500; display: inline-block;">Información General</h3>
            
            <p style="font-size: 1.2rem; margin: 15px 0;">
                <strong>Nombre:</strong> {{ $equipo->nombre }}
            </p>
            
            <p style="font-size: 1.2rem; margin: 15px 0;">
                <strong>Categoría:</strong> <span class="badge" style="background: #023e8a; color: white; padding: 5px 12px; border-radius: 15px; font-size: 1rem;">{{ $equipo->category->name ?? $equipo->categoria }}</span>
            </p>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Descripción:</strong> {{ $equipo->descripcion ?: 'Sin descripción disponible.' }}
            </p>

            <p style="font-size: 1.2rem; margin: 15px 0;">
                <strong>Fecha de Registro:</strong> {{ $equipo->created_at->format('d/m/Y') }}
            </p>

            <p style="font-size: 1.2rem; margin: 15px 0;">
                <strong>Tipo:</strong> {{ $equipo->es_local ? 'Equipo local del club' : 'Equipo externo/rival' }}
            </p>

            @if($equipo->es_local)
                <p style="font-size: 1.2rem; margin: 15px 0;">
                    <strong>Jugadores:</strong> {{ $equipo->jugadores_count }}
                </p>
            @endif

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <div class="admin-detail-actions">

                @if($esAdmin)
                    <a href="{{ route('equipos.edit', $equipo->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;">
                        <i class="fas fa-pen"></i>
                    </a>
                @endif

                @if($equipo->es_local)
                    <a href="{{ route('equipos.analisis', $equipo) }}" class="btn-accion" title="Análisis del equipo" style="background-color: #6f42c1; color: white;">
                        <i class="fas fa-chart-line"></i>
                    </a>
                @endif
                        
                @if($esAdmin)
                    <form action="{{ route('equipos.destroy', $equipo->id) }}" method="POST" onsubmit="return confirm('¿Eliminar equipo?')" class="admin-detail-inline-form" style="margin: 0; display: flex; align-items: center;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if($equipo->es_local)
        <div class="admin-detail-body" style="margin-top: 25px;">
            <h3 style="margin-top: 0;">Plantilla</h3>

            @forelse($equipo->jugadores as $jugador)
                <div class="fila-detalle">
                    <span>#{{ $jugador->dorsal ?? '00' }} · {{ $jugador->nombre }} {{ $jugador->apellido }}</span>
                    <div class="valor">
                        <strong>{{ $jugador->posicion_nombre }}</strong>
                        <a href="{{ route('jugadores.show', $jugador->id) }}">Ver ficha</a>
                    </div>
                </div>
            @empty
                <p style="color: #777;">Este equipo local todavía no tiene jugadores.</p>
            @endforelse
        </div>
    @endif
</div>
@endsection
