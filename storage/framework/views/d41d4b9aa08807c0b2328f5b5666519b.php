<div class="grid-formulario-fichas">
    <div class="seccion-form-ficha seccion-deportiva">
        <h3><i class="fas fa-trophy"></i> Equipo</h3>

        <div class="campo-ficha">
            <label>Equipo registrado del club (opcional)</label>
            <select name="equipo_id" id="equipo_id" class="input-ficha" style="background: white;">
                <option value="">Sin vincular</option>
                <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($equipo->id); ?>"
                            data-nombre="<?php echo e($equipo->nombre); ?>"
                            data-categoria="<?php echo e($equipo->categoria); ?>"
                            <?php echo e(old('equipo_id', $clasificacion?->equipo_id) == $equipo->id ? 'selected' : ''); ?>>
                        <?php echo e($equipo->nombre); ?> (<?php echo e($equipo->categoria); ?>)
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="campo-ficha">
            <label>Nombre del equipo en la clasificación</label>
            <input type="text" name="equipo_nombre" id="equipo_nombre" value="<?php echo e(old('equipo_nombre', $clasificacion?->equipo_nombre)); ?>" required class="input-ficha">
        </div>

        <div class="campo-ficha">
            <label>Categoría</label>
            <input type="text" name="categoria" id="categoria" value="<?php echo e(old('categoria', $clasificacion?->categoria)); ?>" required class="input-ficha">
        </div>

        <div class="campo-ficha">
            <label>Temporada</label>
            <input type="text" name="temporada" value="<?php echo e(old('temporada', $clasificacion?->temporada)); ?>" required class="input-ficha">
        </div>
    </div>

    <div class="seccion-form-ficha seccion-personal">
        <h3><i class="fas fa-list-ol"></i> Posición y Balance</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="campo-ficha"><label>Posición</label><input type="number" min="1" name="posicion" value="<?php echo e(old('posicion', $clasificacion?->posicion ?? 1)); ?>" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Puntos</label><input type="number" min="0" name="puntos" value="<?php echo e(old('puntos', $clasificacion?->puntos ?? 0)); ?>" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Partidos Jugados</label><input type="number" min="0" name="partidos_jugados" value="<?php echo e(old('partidos_jugados', $clasificacion?->partidos_jugados ?? 0)); ?>" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Partidos Ganados</label><input type="number" min="0" name="partidos_ganados" value="<?php echo e(old('partidos_ganados', $clasificacion?->partidos_ganados ?? 0)); ?>" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Partidos Perdidos</label><input type="number" min="0" name="partidos_perdidos" value="<?php echo e(old('partidos_perdidos', $clasificacion?->partidos_perdidos ?? 0)); ?>" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Puntos a Favor</label><input type="number" min="0" name="puntos_favor" value="<?php echo e(old('puntos_favor', $clasificacion?->puntos_favor ?? 0)); ?>" required class="input-ficha"></div>
            <div class="campo-ficha"><label>Puntos en Contra</label><input type="number" min="0" name="puntos_contra" value="<?php echo e(old('puntos_contra', $clasificacion?->puntos_contra ?? 0)); ?>" required class="input-ficha"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const equipoSelect = document.getElementById('equipo_id');
    const nombreInput = document.getElementById('equipo_nombre');
    const categoriaInput = document.getElementById('categoria');

    if (!equipoSelect || !nombreInput || !categoriaInput) {
        return;
    }

    equipoSelect.addEventListener('change', function () {
        const seleccion = this.options[this.selectedIndex];

        if (!seleccion || !seleccion.value) {
            return;
        }

        nombreInput.value = seleccion.dataset.nombre || nombreInput.value;
        categoriaInput.value = seleccion.dataset.categoria || categoriaInput.value;
    });
});
</script>
<?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/clasificaciones/partials/form.blade.php ENDPATH**/ ?>