<?php $__env->startSection('title', 'Inicio - Bellreguard Club de Basket'); ?>


<?php $__env->startSection('contenido'); ?>

<section class="seccion-clasificacion-header">
    <div class="header-contenido">
        <h1>Clasificaciones</h1>
        <p>Consulta la posición de nuestros equipos en todas las categorías</p>
        <div class="icono-trofeo">
            <i class="fas fa-trophy"></i>
        </div>
    </div>
</section>

<section class="contenedor-clasificacion">
    <?php if($categorias->isNotEmpty() && $clasificacionActual): ?>
        <div class="selector-categorias" style="display: flex; flex-wrap: wrap; gap: 10px;">
            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('basket.clasificacion', ['categoria' => $categoria])); ?>"
                   class="btn-cat <?php echo e($categoriaSeleccionada === $categoria ? 'activo' : ''); ?>"
                   style="text-decoration: none; display: inline-block;">
                    <?php echo e($categoria); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($temporadas->isNotEmpty()): ?>
            <div class="selector-categorias" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px;">
                <?php $__currentLoopData = $temporadas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $temporada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('basket.clasificacion', ['categoria' => $categoriaSeleccionada, 'temporada' => $temporada])); ?>"
                       class="btn-cat <?php echo e($temporadaActual === $temporada ? 'activo' : ''); ?>"
                       style="text-decoration: none; display: inline-block;">
                        <?php echo e($temporada); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="tabla-contenedor">
            <div class="tabla-header">
                <i class="fas fa-trophy"></i> Liga <?php echo e($categoriaSeleccionada); ?> - Temporada <?php echo e($temporadaActual); ?>

            </div>
            <div class="tabla-scroll">
                <table class="tabla-clasificacion">
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Equipo</th>
                            <th>PJ</th>
                            <th>PG</th>
                            <th>PP</th>
                            <th>PF</th>
                            <th>PC</th>
                            <th>Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $clasificacionActual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fila): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e($fila->equipo_id ? 'fila-destacada' : ''); ?>">
                                <td><strong><?php echo e($fila->posicion); ?></strong></td>
                                <td><?php echo e($fila->equipo_nombre); ?></td>
                                <td><?php echo e($fila->partidos_jugados); ?></td>
                                <td><?php echo e($fila->partidos_ganados); ?></td>
                                <td><?php echo e($fila->partidos_perdidos); ?></td>
                                <td><?php echo e($fila->puntos_favor); ?></td>
                                <td><?php echo e($fila->puntos_contra); ?></td>
                                <td><strong><?php echo e($fila->puntos); ?></strong></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="tabla-contenedor">
            <div class="tabla-header">
                <i class="fas fa-trophy"></i> Clasificación no disponible
            </div>
            <div style="padding: 30px; color: #777;">Cuando el administrador añada la clasificación, aparecerá aquí automáticamente.</div>
        </div>
    <?php endif; ?>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/basket/clasificacion.blade.php ENDPATH**/ ?>