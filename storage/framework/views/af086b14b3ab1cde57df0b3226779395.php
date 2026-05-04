<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <div>
        <h2>Detalle de Foto</h2>
        <p style="color: #777;">Vista previa del contenido de la galería</p>
    </div>
    <a href="<?php echo e(route('galerias.index')); ?>" class="btn-nuevo" style="background-color: #777;"><i class="fas fa-arrow-left"></i> Volver al listado</a>
</header>

<div class="pizarra-admin">
    <div style="display: flex; gap: 30px; padding: 20px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 280px;">
            <img src="<?php echo e($galeria->image_url); ?>" alt="<?php echo e($galeria->titulo); ?>" style="width: 100%; max-width: 420px; border-radius: 10px; object-fit: cover;">
        </div>
        <div style="flex: 1; min-width: 280px;">
            <h3 style="margin-top: 0;"><?php echo e($galeria->titulo); ?></h3>
            <p><strong>Categoría:</strong> <?php echo e($galeria->categoria); ?></p>
            <p><strong>Fecha:</strong> <?php echo e($galeria->fecha_imagen ? $galeria->fecha_imagen->format('d/m/Y') : 'Sin fecha'); ?></p>
            <p><strong>Descripción:</strong> <?php echo e($galeria->descripcion); ?></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/galerias/show.blade.php ENDPATH**/ ?>