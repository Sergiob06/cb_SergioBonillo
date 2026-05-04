<div class="grid-formulario-fichas">
    <div class="seccion-form-ficha seccion-deportiva">
        <h3><i class="fas fa-shield-alt"></i> Equipo y Temporada</h3>

        <div class="campo-ficha">
            <label>Equipo</label>
            <select name="equipo_id" required class="input-ficha" style="background: white;">
                <option value="" disabled {{ old('equipo_id', $estadistica?->equipo_id) == '' ? 'selected' : '' }}>Selecciona un equipo</option>
                @foreach($equipos as $equipo)
                    <option value="{{ $equipo->id }}" {{ old('equipo_id', $estadistica?->equipo_id) == $equipo->id ? 'selected' : '' }}>
                        {{ $equipo->nombre }} ({{ $equipo->categoria }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="campo-ficha">
            <label>Temporada</label>
            <input type="text" name="temporada" value="{{ old('temporada', $estadistica?->temporada) }}" placeholder="Ej: 2025-2026" required class="input-ficha">
        </div>
    </div>

    <div class="seccion-form-ficha seccion-personal">
        <h3><i class="fas fa-chart-bar"></i> Totales</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="campo-ficha"><label>Puntos Totales</label><input type="number" min="0" name="puntos_totales" value="{{ old('puntos_totales', $estadistica?->puntos_totales ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes</label><input type="number" min="0" name="rebotes" value="{{ old('rebotes', $estadistica?->rebotes ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Asistencias</label><input type="number" min="0" name="asistencias" value="{{ old('asistencias', $estadistica?->asistencias ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Robos</label><input type="number" min="0" name="robos" value="{{ old('robos', $estadistica?->robos ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Tapones</label><input type="number" min="0" name="tapones" value="{{ old('tapones', $estadistica?->tapones ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Partidos Jugados</label><input type="number" min="0" name="partidos_jugados" value="{{ old('partidos_jugados', $estadistica?->partidos_jugados ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Victorias</label><input type="number" min="0" name="victorias" value="{{ old('victorias', $estadistica?->victorias ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Derrotas</label><input type="number" min="0" name="derrotas" value="{{ old('derrotas', $estadistica?->derrotas ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes Defensivos</label><input type="number" min="0" name="rebotes_defensivos" value="{{ old('rebotes_defensivos', $estadistica?->rebotes_defensivos ?? 0) }}" class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes Ofensivos</label><input type="number" min="0" name="rebotes_ofensivos" value="{{ old('rebotes_ofensivos', $estadistica?->rebotes_ofensivos ?? 0) }}" class="input-ficha"></div>
        </div>
    </div>
</div>
