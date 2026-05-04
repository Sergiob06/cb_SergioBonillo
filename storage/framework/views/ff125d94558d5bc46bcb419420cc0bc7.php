<?php $__env->startSection('contenido_admin'); ?>
<div class="contenedor-edit-jugador">
    
    <header class="header-ficha">
        <div>
            <h2>Añadir Nuevo Jugador</h2>
            <p>Introduce los datos para inscribir al jugador en el club</p>
        </div>
        <a href="<?php echo e(route('jugadores.index')); ?>" class="btn-nuevo" style="background-color: #777;">
            <i class="fas fa-arrow-left"></i> Volver al listado
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
        <form action="<?php echo e(route('jugadores.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="grid-formulario-fichas">
                
                <div class="seccion-form-ficha seccion-personal">
                    <h3><i class="fas fa-user-plus"></i> Datos Personales</h3>
                    
                    <div class="campo-ficha">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="<?php echo e(old('nombre')); ?>" placeholder="Nombre del jugador" required class="input-ficha">
                    </div>

                    <div class="campo-ficha">
                        <label>Apellido</label>
                        <input type="text" name="apellido" value="<?php echo e(old('apellido')); ?>" placeholder="Apellidos" required class="input-ficha">
                    </div>

                    <div class="campo-ficha">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="<?php echo e(old('fecha_nacimiento')); ?>" class="input-ficha">
                    </div>
                </div>

                <div class="seccion-form-ficha seccion-deportiva">
                    <h3><i class="fas fa-basketball-ball"></i> Datos Deportivos</h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="campo-ficha">
                            <label>Dorsal</label>
                            <input type="number" name="dorsal" value="<?php echo e(old('dorsal')); ?>" placeholder="00" class="input-ficha">
                        </div>
                        <div class="campo-ficha">
                            <label>Posición</label>
                            <input type="text" name="posicion" list="lista-posiciones" value="<?php echo e(old('posicion')); ?>" placeholder="Ej: Base" class="input-ficha">
                            <datalist id="lista-posiciones">
                                <?php $__currentLoopData = $posicionesDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $posicion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($posicion); ?>">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </datalist>
                        </div>
                    </div>

                    <div class="campo-ficha">
                        <label>Asignar Equipo</label>
                        <select name="equipo_id" required class="input-ficha" style="background: white;">
                            <option value="" disabled <?php echo e(old('equipo_id') == '' ? 'selected' : ''); ?>>Selecciona un equipo</option>
                            <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($equipo->id); ?>" <?php echo e(old('equipo_id') == $equipo->id ? 'selected' : ''); ?>>
                                    <?php echo e($equipo->nombre); ?> (<?php echo e($equipo->categoria); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="seccion-foto-ficha">
                    <div style="text-align: center;">
                        <label>Imagen</label>
                        <div class="preview-foto">
                            <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="Imagen por defecto">
                        </div>
                    </div>
                    
                    <div style="flex: 1;">
                        <label>Subir fotografía del jugador</label>
                        <input type="file" name="imagen_jugador" class="input-ficha" style="background: white;">
                        <p style="margin: 10px 0 0; font-size: 0.8em; color: #718096;">Formatos: JPG o PNG. La foto se guardará en public/jugadores.</p>
                    </div>
                </div>
            </div>

            <div class="form-acciones-ficha">
                <a href="<?php echo e(route('jugadores.index')); ?>" class="btn-cancelar-ficha">Cancelar</a>
                <button type="submit" class="btn-actualizar-ficha" style="background: #023e8a; box-shadow: 0 4px 6px rgba(2, 62, 138, 0.3);">
                    <i class="fas fa-save"></i> REGISTRAR JUGADOR
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/jugadores/create.blade.php ENDPATH**/ ?>