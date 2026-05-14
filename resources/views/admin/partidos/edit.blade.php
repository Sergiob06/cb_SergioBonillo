@extends('layouts.admin')

@section('contenido_admin')
<div class="contenedor-edit-jugador">
    <header class="header-ficha">
        <div>
            <h2>Editar Partido</h2>
            <p>Actualizando el enfrentamiento entre <strong>{{ $partido->equipoLocal->nombre ?? $partido->equipo_local }}</strong> y <strong>{{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}</strong></p>
        </div>
        <a href="{{ route('partidos.index') }}" class="btn-volver-link">
            <i class="fas fa-chevron-left"></i> Volver al listado
        </a>
    </header>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>¡Vaya! Algo ha ido mal:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="pizarra-ficha">
        <form action="{{ route('partidos.update', $partido->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid-formulario-fichas">
                <div class="seccion-form-ficha seccion-personal admin-form-span-full">
                    <h3><i class="fas fa-users"></i> Enfrentamiento</h3>

                    <div class="admin-form-inline-grid">
                        <div class="campo-ficha">
                            <label>Equipo Local</label>
                            <select name="equipo_local_id" required class="input-ficha" style="background: white;">
                                @foreach($equipos as $equipo)
                                    <option value="{{ $equipo->id }}" {{ old('equipo_local_id', $partido->equipo_local_id) == $equipo->id ? 'selected' : '' }}>
                                        {{ $equipo->nombre }} ({{ $equipo->categoria }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="campo-ficha">
                            <label>Equipo Visitante</label>
                            <select name="equipo_visitante_id" required class="input-ficha" style="background: white;">
                                @foreach($equipos as $equipo)
                                    <option value="{{ $equipo->id }}" {{ old('equipo_visitante_id', $partido->equipo_visitante_id) == $equipo->id ? 'selected' : '' }}>
                                        {{ $equipo->nombre }} ({{ $equipo->categoria }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="seccion-foto-ficha admin-form-span-full admin-form-section-block">
                    <h3 style="margin-top: 0;"><i class="fas fa-calendar-alt"></i> Fecha y Lugar</h3>

                    <div class="campo-ficha" style="margin-bottom: 20px;">
                        <label>Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_partido" value="{{ old('fecha_partido', $partido->fecha_partido->format('Y-m-d\\TH:i')) }}" required class="input-ficha" style="background: white;">
                    </div>

                    <div class="campo-ficha">
                        <label>Lugar</label>
                        <input type="text" name="lugar" value="{{ old('lugar', $partido->lugar) }}" required class="input-ficha">
                    </div>
                </div>
            </div>

            <div class="form-acciones-ficha">
                <a href="{{ route('partidos.index') }}" class="btn-cancelar-ficha">Cancelar</a>
                <button type="submit" class="btn-actualizar-ficha">
                    <i class="fas fa-sync-alt"></i> ACTUALIZAR PARTIDO
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
