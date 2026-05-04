<?php $__env->startSection('contenido_admin'); ?>
<div class="contenedor-edit-jugador">
    <header class="header-ficha">
        <div>
            <h2>Editar Partido</h2>
            <p>Actualizando el enfrentamiento entre <strong><?php echo e($partido->equipoLocal->nombre ?? $partido->equipo_local); ?></strong> y <strong><?php echo e($partido->equipoVisitante->nombre ?? $partido->equipo_visitante); ?></strong></p>
        </div>
        <a href="<?php echo e(route('partidos.index')); ?>" class="btn-volver-link">
            <i class="fas fa-chevron-left"></i> Volver al listado
        </a>
    </header>

    <?php if($errors->any()): ?>
        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>¡Vaya! Algo ha ido mal:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="pizarra-ficha">
        <form action="<?php echo e(route('partidos.update', $partido->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid-formulario-fichas">
                <div class="seccion-form-ficha seccion-personal" style="grid-column: span 2;">
                    <h3><i class="fas fa-users"></i> Enfrentamiento</h3>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">
                        <div class="campo-ficha">
                            <label>Equipo Local</label>
                            <select name="equipo_local_id" required class="input-ficha" style="background: white;">
                                <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($equipo->id); ?>" <?php echo e(old('equipo_local_id', $partido->equipo_local_id) == $equipo->id ? 'selected' : ''); ?>>
                                        <?php echo e($equipo->nombre); ?> (<?php echo e($equipo->categoria); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="campo-ficha">
                            <label>Equipo Visitante</label>
                            <select name="equipo_visitante_id" required class="input-ficha" style="background: white;">
                                <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($equipo->id); ?>" <?php echo e(old('equipo_visitante_id', $partido->equipo_visitante_id) == $equipo->id ? 'selected' : ''); ?>>
                                        <?php echo e($equipo->nombre); ?> (<?php echo e($equipo->categoria); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="seccion-foto-ficha" style="display: block; grid-column: span 2;">
                    <h3 style="margin-top: 0;"><i class="fas fa-calendar-alt"></i> Fecha y Lugar</h3>

                    <div class="campo-ficha" style="margin-bottom: 20px;">
                        <label>Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_partido" value="<?php echo e(old('fecha_partido', $partido->fecha_partido->format('Y-m-d\\TH:i'))); ?>" required class="input-ficha" style="background: white;">
                    </div>

                    <div class="campo-ficha">
                        <label>Lugar</label>
                        <input type="text" name="lugar" value="<?php echo e(old('lugar', $partido->lugar)); ?>" required class="input-ficha">
                    </div>
                </div>
            </div>

            <div class="form-acciones-ficha">
                <a href="<?php echo e(route('partidos.index')); ?>" class="btn-cancelar-ficha">Cancelar</a>
                <button type="submit" class="btn-actualizar-ficha">
                    <i class="fas fa-sync-alt"></i> ACTUALIZAR PARTIDO
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/partidos/edit.blade.php ENDPATH**/ ?>