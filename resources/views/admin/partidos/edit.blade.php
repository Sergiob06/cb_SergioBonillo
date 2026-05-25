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
                            <select name="equipo_local_id" required class="input-ficha js-equipo-local" style="background: white;">
                                @foreach($equipos as $equipo)
                                    <option value="{{ $equipo->id }}" data-es-local="{{ $equipo->es_local ? '1' : '0' }}" {{ old('equipo_local_id', $partido->equipo_local_id) == $equipo->id ? 'selected' : '' }}>
                                        {{ $equipo->nombre }} ({{ $equipo->categoria }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="campo-ficha">
                            <label>Equipo Visitante</label>
                            <select name="equipo_visitante_id" required class="input-ficha js-equipo-visitante" style="background: white;">
                                @foreach($equipos as $equipo)
                                    <option value="{{ $equipo->id }}" data-es-local="{{ $equipo->es_local ? '1' : '0' }}" {{ old('equipo_visitante_id', $partido->equipo_visitante_id) == $equipo->id ? 'selected' : '' }}>
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

                    <div class="campo-ficha" style="margin-bottom: 20px;">
                        <label>Estado</label>
                        <select name="estado" id="estado_partido" required class="input-ficha js-estado-partido" style="background: white;">
                            <option value="proximo" {{ old('estado', $partido->estado) === 'proximo' ? 'selected' : '' }}>Próximo</option>
                            <option value="jugado" {{ old('estado', $partido->estado) === 'jugado' ? 'selected' : '' }}>Jugado</option>
                        </select>
                    </div>

                    <div class="campo-ficha">
                        <label>Lugar</label>
                        <input type="text" name="lugar" value="{{ old('lugar', $partido->lugar) }}" required class="input-ficha">
                    </div>
                </div>

                <div class="seccion-form-ficha seccion-deportiva admin-form-span-full">
                    <h3><i class="fas fa-basketball-ball"></i> Resultado</h3>

                    <div class="admin-form-inline-grid">
                        <div class="campo-ficha">
                            <label>Puntos Local</label>
                            <input type="number" min="0" max="300" name="puntos_local" value="{{ old('estado', $partido->estado) === 'jugado' ? old('puntos_local', $partido->puntos_local) : '' }}" class="input-ficha js-puntos-partido" style="background: white;">
                            <small class="admin-help-text">Obligatorio si el partido está marcado como jugado.</small>
                        </div>

                        <div class="campo-ficha">
                            <label>Puntos Visitante</label>
                            <input type="number" min="0" max="300" name="puntos_visitante" value="{{ old('estado', $partido->estado) === 'jugado' ? old('puntos_visitante', $partido->puntos_visitante) : '' }}" class="input-ficha js-puntos-partido" style="background: white;">
                            <small class="admin-help-text">Si cambias a próximo, no se mostrará el marcador real.</small>
                        </div>
                    </div>
                </div>

                @php
                    $estadisticaLocal = $partido->estadisticasEquipos->firstWhere('es_local', true);
                    $estadisticaVisitante = $partido->estadisticasEquipos->firstWhere('es_local', false);
                    $camposEstadisticas = [
                        'puntos_anotados' => 'Puntos anotados',
                        't2_intentados' => 'T2 intentados',
                        't3_intentados' => 'T3 intentados',
                        'tl_intentados' => 'TL intentados',
                        'balones_perdidos' => 'Balones perdidos',
                        'rebotes_ofensivos' => 'Rebotes ofensivos',
                        'tiros_anotados' => 'Tiros anotados',
                        'rebotes_defensivos' => 'Rebotes defensivos',
                        'asistencias' => 'Asistencias',
                        'robos' => 'Robos',
                        'tapones' => 'Tapones',
                        'faltas' => 'Faltas',
                    ];
                @endphp

                <div class="seccion-form-ficha seccion-personal admin-form-span-full">
                    <h3><i class="fas fa-chart-bar"></i> Estadísticas del equipo local</h3>
                    <small class="admin-help-text">Bloque correspondiente al equipo marcado como local.</small>

                    <div class="admin-form-inline-grid" style="margin-top: 18px;">
                        @foreach($camposEstadisticas as $campo => $label)
                            <div class="campo-ficha">
                                <label>{{ $label }}</label>
                                <input type="number" min="0" name="estadisticas[local][{{ $campo }}]" value="{{ old('estado', $partido->estado) === 'jugado' ? old('estadisticas.local.'.$campo, $estadisticaLocal?->{$campo}) : '' }}" class="input-ficha js-estadistica-partido" style="background: white;">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="seccion-form-ficha seccion-personal admin-form-span-full">
                    <h3><i class="fas fa-chart-bar"></i> Estadísticas del equipo visitante</h3>
                    <small class="admin-help-text">Bloque correspondiente al equipo marcado como visitante.</small>

                    <div class="admin-form-inline-grid" style="margin-top: 18px;">
                        @foreach($camposEstadisticas as $campo => $label)
                            <div class="campo-ficha">
                                <label>{{ $label }}</label>
                                <input type="number" min="0" name="estadisticas[visitante][{{ $campo }}]" value="{{ old('estado', $partido->estado) === 'jugado' ? old('estadisticas.visitante.'.$campo, $estadisticaVisitante?->{$campo}) : '' }}" class="input-ficha js-estadistica-partido" style="background: white;">
                            </div>
                        @endforeach
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const estado = document.querySelector('.js-estado-partido');
        const equipoLocal = document.querySelector('.js-equipo-local');
        const equipoVisitante = document.querySelector('.js-equipo-visitante');
        const puntos = document.querySelectorAll('.js-puntos-partido');
        const estadisticas = document.querySelectorAll('.js-estadistica-partido');

        const actualizarMarcador = () => {
            const esProximo = estado.value === 'proximo';
            puntos.forEach((input) => {
                input.disabled = esProximo;

                if (esProximo) {
                    input.value = '';
                }
            });

            estadisticas.forEach((input) => {
                input.disabled = esProximo;

                if (esProximo) {
                    input.value = '';
                }
            });

            puntos.forEach((input) => {
                input.required = !esProximo;
            });

        };

        estado.addEventListener('change', actualizarMarcador);
        equipoLocal.addEventListener('change', actualizarMarcador);
        equipoVisitante.addEventListener('change', actualizarMarcador);
        actualizarMarcador();
    });
</script>
@endsection
