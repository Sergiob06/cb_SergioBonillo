<?php $__env->startSection('contenido_admin'); ?>
    <div class="contenedor-edit-jugador">
        <header class="header-ficha">
            <div>
                <h2>Añadir Nuevo Partido</h2>
                <p>Programa el próximo encuentro del club</p>
            </div>
            <a href="<?php echo e(route('partidos.index')); ?>" class="btn-nuevo" style="background-color: #777;">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </header>

        <?php if($errors->any()): ?>
            <div
                style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <strong>¡Vaya! Algo ha ido mal:</strong>
                <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="pizarra-ficha">
            <form action="<?php echo e(route('partidos.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="grid-formulario-fichas">
                    <div class="seccion-form-ficha seccion-personal" style="grid-column: span 2;">
                        <h3><i class="fas fa-users"></i> Enfrentamiento</h3>

                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">
                            <div class="campo-ficha">
                                <label>Equipo Local</label>
                                <select name="equipo_local_id" required class="input-ficha" style="background: white;">
                                    <option value="" disabled <?php echo e(old('equipo_local_id') == '' ? 'selected' : ''); ?>>
                                        Selecciona el equipo local</option>
                                    <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($equipo->id); ?>"
                                            <?php echo e(old('equipo_local_id') == $equipo->id ? 'selected' : ''); ?>>
                                            <?php echo e($equipo->nombre); ?> (<?php echo e($equipo->categoria); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="campo-ficha">
                                <label>Equipo Visitante</label>
                                <select name="equipo_visitante_id" required class="input-ficha" style="background: white;">
                                    <option value="" disabled
                                        <?php echo e(old('equipo_visitante_id') == '' ? 'selected' : ''); ?>>Selecciona el equipo
                                        visitante</option>
                                    <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($equipo->id); ?>"
                                            <?php echo e(old('equipo_visitante_id') == $equipo->id ? 'selected' : ''); ?>>
                                            <?php echo e($equipo->nombre); ?> (<?php echo e($equipo->categoria); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <input type="datetime-local" name="fecha_partido" value="<?php echo e(old('fecha_partido')); ?>" required
                                class="input-ficha" style="background: white;">
                        </div>

                        <div class="campo-ficha" style="margin-top: 10px;">
                            <label style="display: block; margin-bottom: 8px;">Lugar</label>
                            <input type="text" name="lugar" value="<?php echo e(old('lugar')); ?>"
                                placeholder="Ej: Pabellón Municipal Bellreguard" required class="input-ficha">
                        </div>
                    </div>
                </div>

                <div class="form-acciones-ficha">
                    <a href="<?php echo e(route('partidos.index')); ?>" class="btn-cancelar-ficha">Cancelar</a>
                    <button type="submit" class="btn-actualizar-ficha"
                        style="background: #023e8a; box-shadow: 0 4px 6px rgba(2, 62, 138, 0.3);">
                        <i class="fas fa-save"></i> GUARDAR PARTIDO
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/partidos/create.blade.php ENDPATH**/ ?>