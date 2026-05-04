<?php $__env->startSection('contenido_admin'); ?>
<div class="contenedor-edit-jugador">
    <header class="header-ficha">
        <div>
            <h2>Añadir Fila de Clasificación</h2>
            <p>Introduce la posición del equipo en la tabla</p>
        </div>
        <a href="<?php echo e(route('clasificaciones.index')); ?>" class="btn-nuevo" style="background-color: #777;">
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
        <form action="<?php echo e(route('clasificaciones.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo $__env->make('admin.clasificaciones.partials.form', ['clasificacion' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="form-acciones-ficha">
                <a href="<?php echo e(route('clasificaciones.index')); ?>" class="btn-cancelar-ficha">Cancelar</a>
                <button type="submit" class="btn-actualizar-ficha" style="background: #023e8a;"><i class="fas fa-save"></i> GUARDAR FILA</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/clasificaciones/create.blade.php ENDPATH**/ ?>