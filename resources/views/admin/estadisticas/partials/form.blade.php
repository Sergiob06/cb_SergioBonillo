<div class="grid-formulario-fichas">
    <div class="seccion-form-ficha seccion-deportiva admin-form-span-full">
        <h3><i class="fas fa-calendar-check"></i> Partido</h3>

        <div class="campo-ficha">
            <label>Partido</label>
            @if(!empty($bloquearPartido) && optional($estadistica)->partido)
                @php
                    $partidoActual = $estadistica->partido;
                @endphp
                <div class="admin-fixed-match">
                    <strong>{{ $partidoActual->equipoLocal->nombre ?? $partidoActual->equipo_local }} vs {{ $partidoActual->equipoVisitante->nombre ?? $partidoActual->equipo_visitante }}</strong>
                    <span>{{ $partidoActual->fecha_partido?->format('d/m/Y H:i') }}{{ $partidoActual->lugar ? ' · ' . $partidoActual->lugar : '' }}</span>
                </div>
                <input type="hidden" name="partido_id" value="{{ $estadistica->partido_id }}">
            @else
                <select name="partido_id" required class="input-ficha" style="background: white;">
                    <option value="" disabled {{ old('partido_id', $estadistica?->partido_id ?? $partidoSeleccionado ?? '') == '' ? 'selected' : '' }}>
                        Selecciona un partido
                    </option>
                    @foreach($partidos as $partido)
                        @php
                            $partidoOcupado = $partido->estadistica && (int) $partido->estadistica->id !== (int) ($estadistica?->id ?? 0);
                            $selected = (int) old('partido_id', $estadistica?->partido_id ?? $partidoSeleccionado ?? 0) === (int) $partido->id;
                        @endphp
                        <option value="{{ $partido->id }}" {{ $selected ? 'selected' : '' }} {{ $partidoOcupado ? 'disabled' : '' }}>
                            {{ $partido->fecha_partido->format('d/m/Y H:i') }} · {{ $partido->equipoLocal->nombre ?? $partido->equipo_local }} vs {{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}
                            {{ $partidoOcupado ? ' · ya tiene estadísticas' : '' }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <div class="seccion-form-ficha seccion-personal admin-form-span-full">
        <h3><i class="fas fa-chart-bar"></i> Estadísticas Totales del Partido</h3>

        <div class="admin-form-inline-grid">
            <div class="campo-ficha"><label>Puntos Totales</label><input type="number" min="0" name="puntos_totales" value="{{ old('puntos_totales', $estadistica?->puntos_totales ?? 0) }}" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes</label><input type="number" min="0" name="rebotes" value="{{ old('rebotes', $estadistica?->rebotes ?? 0) }}" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Asistencias</label><input type="number" min="0" name="asistencias" value="{{ old('asistencias', $estadistica?->asistencias ?? 0) }}" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Robos</label><input type="number" min="0" name="robos" value="{{ old('robos', $estadistica?->robos ?? 0) }}" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Tapones</label><input type="number" min="0" name="tapones" value="{{ old('tapones', $estadistica?->tapones ?? 0) }}" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes Defensivos</label><input type="number" min="0" name="rebotes_defensivos" value="{{ old('rebotes_defensivos', $estadistica?->rebotes_defensivos ?? 0) }}" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes Ofensivos</label><input type="number" min="0" name="rebotes_ofensivos" value="{{ old('rebotes_ofensivos', $estadistica?->rebotes_ofensivos ?? 0) }}" required class="input-ficha"></div>
        </div>
    </div>
</div>
