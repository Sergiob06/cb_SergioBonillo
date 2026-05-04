<?php $__env->startSection('title', 'Inicio - Bellreguard Club de Basket'); ?>


<?php $__env->startSection('contenido'); ?>

<section class="seccion-galeria-header">
    <div class="header-contenido">
        <h1>Galería Bellreguard CB</h1>
        <p>Revive los mejores momentos de nuestro equipo</p>
        <div class="stats-galeria">
            <span><i class="fas fa-camera"></i> <?php echo e($galerias->count()); ?> Fotos</span>
            <span><i class="fas fa-calendar-alt"></i> <?php echo e($categoriaSeleccionada && $categoriaSeleccionada !== 'Todos' ? $categoriaSeleccionada : 'Todas las categorías'); ?></span>
        </div>
    </div>
</section>

<section class="contenedor-galeria">
    <div class="filtros-galeria" style="display: flex; flex-wrap: wrap; gap: 10px;">
        <a href="<?php echo e(route('basket.galeria')); ?>" class="btn-filtro <?php echo e(!$categoriaSeleccionada || $categoriaSeleccionada === 'Todos' ? 'activo' : ''); ?>" style="text-decoration: none;">Todos</a>
        <?php $__currentLoopData = $categoriasGaleria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('basket.galeria', ['categoria' => $categoria])); ?>" class="btn-filtro <?php echo e($categoriaSeleccionada === $categoria ? 'activo' : ''); ?>" style="text-decoration: none;">
                <?php echo e($categoria); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="rejilla-albumes">
        <?php $__empty_1 = true; $__currentLoopData = $galerias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="tarjeta-album">
                <div class="imagen-album">
                    <?php if($foto->image): ?>
                        <img src="<?php echo e($foto->image_url); ?>" alt="<?php echo e($foto->titulo); ?>">
                    <?php else: ?>
                        <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="<?php echo e($foto->titulo); ?>">
                    <?php endif; ?>
                    <span class="badge-fotos"><?php echo e($foto->categoria); ?></span>
                </div>
                <div class="info-album">
                    <h3><?php echo e($foto->titulo); ?></h3>
                    <p><?php echo e($foto->descripcion); ?></p>
                    <div class="footer-album">
                        <span class="fecha-album"><?php echo e($foto->fecha_imagen ? $foto->fecha_imagen->translatedFormat('d F Y') : 'Sin fecha'); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="tarjeta-album">
                <div class="info-album">
                    <h3>Sin fotos disponibles</h3>
                    <p>Cuando el administrador suba imágenes a la galería, aparecerán aquí automáticamente.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/basket/galeria.blade.php ENDPATH**/ ?>