<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <h2>Crear Nuevo Equipo</h2>
    <a href="<?php echo e(route('equipos.index')); ?>" class="btn-nuevo" style="background: #333;">Volver</a>
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

<div class="pizarra-admin">
    <form action="<?php echo e(route('equipos.store')); ?>" method="POST" enctype="multipart/form-data" class="formulario-admin">
        <?php echo csrf_field(); ?>
        <div class="grid-formulario">
            <div class="campo">
                <label>Nombre del Equipo</label>
                <input type="text" name="nombre" value="<?php echo e(old('nombre')); ?>" placeholder="Ej: Senior A" required>
            </div>
            <div class="campo">
                <label>Categoría</label>
                <select name="category_id" required>
                    <option value="">Selecciona una categoría</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="campo">
                <label>Descripción</label>
                <textarea name="descripcion" placeholder="Breve descripción del equipo"><?php echo e(old('descripcion')); ?></textarea>
            </div>

            <div class="campo campo-checkbox">
                <label>¿Es del club?</label>
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="es_local" id="es_local" value="1" <?php echo e(old('es_local') ? 'checked' : ''); ?>>
                    <span>Sí, es equipo local</span>
                </div>
            </div>

            <div class="campo">
                <label>Escudo/Imagen del Club</label>
                <input type="file" name="imagen_club">
            </div>
        </div>
        <div class="form-acciones">
            <button type="submit" class="btn-guardar">Guardar Equipo</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/equipos/create.blade.php ENDPATH**/ ?>