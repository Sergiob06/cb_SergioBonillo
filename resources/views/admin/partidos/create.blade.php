@extends('layouts.admin')

@section('contenido_admin')
    <div class="contenedor-edit-jugador">
        <header class="header-ficha">
            <div>
                <h2>Añadir Nuevo Partido</h2>
                <p>Programa el próximo encuentro del club</p>
            </div>
            <a href="{{ route('partidos.index') }}" class="btn-nuevo" style="background-color: #777;">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </header>

        @if ($errors->any())
            <div
                style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <strong>¡Vaya! Algo ha ido mal:</strong>
                <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="pizarra-ficha">
            <form action="{{ route('partidos.store') }}" method="POST">
                @csrf

                <div class="grid-formulario-fichas">
                    <div class="seccion-form-ficha seccion-personal" style="grid-column: span 2;">
                        <h3><i class="fas fa-users"></i> Enfrentamiento</h3>

                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">
                            <div class="campo-ficha">
                                <label>Equipo Local</label>
                                <select name="equipo_local_id" required class="input-ficha" style="background: white;">
                                    <option value="" disabled {{ old('equipo_local_id') == '' ? 'selected' : '' }}>
                                        Selecciona el equipo local</option>
                                    @foreach ($equipos as $equipo)
                                        <option value="{{ $equipo->id }}"
                                            {{ old('equipo_local_id') == $equipo->id ? 'selected' : '' }}>
                                            {{ $equipo->nombre }} ({{ $equipo->categoria }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="campo-ficha">
                                <label>Equipo Visitante</label>
                                <select name="equipo_visitante_id" required class="input-ficha" style="background: white;">
                                    <option value="" disabled
                                        {{ old('equipo_visitante_id') == '' ? 'selected' : '' }}>Selecciona el equipo
                                        visitante</option>
                                    @foreach ($equipos as $equipo)
                                        <option value="{{ $equipo->id }}"
                                            {{ old('equipo_visitante_id') == $equipo->id ? 'selected' : '' }}>
                                            {{ $equipo->nombre }} ({{ $equipo->categoria }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="seccion-foto-ficha" style="display: block; grid-column: span 2;">
                        <h3 style="margin-top: 0; margin-bottom: 20px;">
                            <i class="fas fa-calendar-alt"></i> Fecha y Lugar
                        </h3>

                        <div class="campo-ficha" style="margin-bottom: 25px;">
                            <label style="display: block; margin-bottom: 8px;">Fecha y Hora</label>
                            <input type="datetime-local" name="fecha_partido" value="{{ old('fecha_partido') }}" required
                                class="input-ficha" style="background: white;">
                        </div>

                        <div class="campo-ficha" style="margin-top: 10px;">
                            <label style="display: block; margin-bottom: 8px;">Lugar</label>
                            <input type="text" name="lugar" value="{{ old('lugar') }}"
                                placeholder="Ej: Pabellón Municipal Bellreguard" required class="input-ficha">
                        </div>
                    </div>
                </div>

                <div class="form-acciones-ficha">
                    <a href="{{ route('partidos.index') }}" class="btn-cancelar-ficha">Cancelar</a>
                    <button type="submit" class="btn-actualizar-ficha"
                        style="background: #023e8a; box-shadow: 0 4px 6px rgba(2, 62, 138, 0.3);">
                        <i class="fas fa-save"></i> GUARDAR PARTIDO
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
