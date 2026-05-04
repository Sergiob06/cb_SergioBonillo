<div class="grid-formulario-fichas">
    <div class="seccion-form-ficha seccion-deportiva">
        <h3><i class="fas fa-shield-alt"></i> Equipo y Temporada</h3>

        <div class="campo-ficha">
            <label>Equipo</label>
            <select name="equipo_id" required class="input-ficha" style="background: white;">
                <option value="" disabled <?php echo e(old('equipo_id', $estadistica?->equipo_id) == '' ? 'selected' : ''); ?>>Selecciona un equipo</option>
                <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($equipo->id); ?>" <?php echo e(old('equipo_id', $estadistica?->equipo_id) == $equipo->id ? 'selected' : ''); ?>>
                        <?php echo e($equipo->nombre); ?> (<?php echo e($equipo->categoria); ?>)
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="campo-ficha">
            <label>Temporada</label>
            <input type="text" name="temporada" value="<?php echo e(old('temporada', $estadistica?->temporada)); ?>" placeholder="Ej: 2025-2026" required class="input-ficha">
        </div>
    </div>

    <div class="seccion-form-ficha seccion-personal">
        <h3><i class="fas fa-chart-bar"></i> Totales</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="campo-ficha"><label>Puntos Totales</label><input type="number" min="0" name="puntos_totales" value="<?php echo e(old('puntos_totales', $estadistica?->puntos_totales ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes</label><input type="number" min="0" name="rebotes" value="<?php echo e(old('rebotes', $estadistica?->rebotes ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Asistencias</label><input type="number" min="0" name="asistencias" value="<?php echo e(old('asistencias', $estadistica?->asistencias ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Robos</label><input type="number" min="0" name="robos" value="<?php echo e(old('robos', $estadistica?->robos ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Tapones</label><input type="number" min="0" name="tapones" value="<?php echo e(old('tapones', $estadistica?->tapones ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Partidos Jugados</label><input type="number" min="0" name="partidos_jugados" value="<?php echo e(old('partidos_jugados', $estadistica?->partidos_jugados ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Victorias</label><input type="number" min="0" name="victorias" value="<?php echo e(old('victorias', $estadistica?->victorias ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Derrotas</label><input type="number" min="0" name="derrotas" value="<?php echo e(old('derrotas', $estadistica?->derrotas ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes Defensivos</label><input type="number" min="0" name="rebotes_defensivos" value="<?php echo e(old('rebotes_defensivos', $estadistica?->rebotes_defensivos ?? 0)); ?>" class="input-ficha"></div>
            <div class="campo-ficha"><label>Rebotes Ofensivos</label><input type="number" min="0" name="rebotes_ofensivos" value="<?php echo e(old('rebotes_ofensivos', $estadistica?->rebotes_ofensivos ?? 0)); ?>" class="input-ficha"></div>
        </div>
    </div>
</div>
<?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/estadisticas/partials/form.blade.php ENDPATH**/ ?>