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

                <div class="seccion-form-ficha seccion-personal admin-form-span-full">
                    <h3><i class="fas fa-chart-bar"></i> Estadísticas del equipo Bellreguard</h3>
                    <small class="admin-help-text">Solo se registran datos del equipo de Bellreguard que participe, juegue como local o visitante.</small>

                    <div class="campo-ficha" style="margin-top: 18px;">
                        <label>Equipo de las estadísticas</label>
                        <select name="estadisticas_equipo_id" class="input-ficha js-estadisticas-equipo" style="background: white;">
                            <option value="">Asignación automática si solo participa un equipo Bellreguard</option>
                            @foreach($equiposLocales as $equipo)
                                <option value="{{ $equipo->id }}" {{ old('estadisticas_equipo_id', $partido->estadisticas_equipo_id) == $equipo->id ? 'selected' : '' }}>
                                    {{ $equipo->nombre }} ({{ $equipo->categoria }})
                                </option>
                            @endforeach
                        </select>
                        <small class="admin-help-text js-estadisticas-help">Si no participa ningún equipo Bellreguard, estos campos se ignorarán.</small>
                    </div>

                    <div class="admin-form-inline-grid" style="margin-top: 18px;">
                        @foreach(['triples' => 'Triples', 'tiros_libres' => 'Tiros libres', 'rebotes' => 'Rebotes', 'asistencias' => 'Asistencias', 'robos' => 'Robos', 'perdidas' => 'Pérdidas', 'faltas' => 'Faltas'] as $campo => $label)
                            <div class="campo-ficha">
                                <label>{{ $label }}</label>
                                <input type="number" min="0" name="{{ $campo }}" value="{{ old('estado', $partido->estado) === 'jugado' ? old($campo, $partido->{$campo}) : '' }}" class="input-ficha js-estadistica-partido" style="background: white;">
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
        const equipoEstadisticas = document.querySelector('.js-estadisticas-equipo');
        const ayudaEstadisticas = document.querySelector('.js-estadisticas-help');
        const puntos = document.querySelectorAll('.js-puntos-partido');
        const estadisticas = document.querySelectorAll('.js-estadistica-partido');

        const actualizarMarcador = () => {
            const esProximo = estado.value === 'proximo';
            const localSeleccionado = equipoLocal.selectedOptions[0];
            const visitanteSeleccionado = equipoVisitante.selectedOptions[0];
            const participantesBellreguard = [localSeleccionado, visitanteSeleccionado]
                .filter((option) => option && option.dataset.esLocal === '1')
                .map((option) => option.value);
            const sinEquipoBellreguard = participantesBellreguard.length === 0;

            puntos.forEach((input) => {
                input.disabled = esProximo;

                if (esProximo) {
                    input.value = '';
                }
            });

            estadisticas.forEach((input) => {
                input.disabled = esProximo || sinEquipoBellreguard;

                if (esProximo || sinEquipoBellreguard) {
                    input.value = '';
                }
            });

            puntos.forEach((input) => {
                input.required = !esProximo;
            });

            if (equipoEstadisticas) {
                [...equipoEstadisticas.options].forEach((option) => {
                    option.hidden = option.value !== '' && !participantesBellreguard.includes(option.value);
                });

                equipoEstadisticas.disabled = esProximo || sinEquipoBellreguard;
                equipoEstadisticas.required = !esProximo && participantesBellreguard.length > 1;

                if (!participantesBellreguard.includes(equipoEstadisticas.value)) {
                    equipoEstadisticas.value = '';
                }
            }

            if (ayudaEstadisticas) {
                ayudaEstadisticas.textContent = sinEquipoBellreguard
                    ? 'No participa ningún equipo Bellreguard: no se guardarán estadísticas.'
                    : participantesBellreguard.length > 1
                        ? 'Participan dos equipos Bellreguard: selecciona a cuál pertenecen las estadísticas.'
                        : 'Las estadísticas se asignarán al equipo Bellreguard participante.';
            }
        };

        estado.addEventListener('change', actualizarMarcador);
        equipoLocal.addEventListener('change', actualizarMarcador);
        equipoVisitante.addEventListener('change', actualizarMarcador);
        actualizarMarcador();
    });
</script>
@endsection
