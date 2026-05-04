<?php $__env->startSection('title', 'Equipos'); ?>

<?php $__env->startSection('contenido'); ?>
<section class="seccion-equipo-header">
    <h1>Equipos</h1>
    <p>Descubre todos los equipos del club y filtra por categoría.</p>
</section>

<section class="navegacion-categorias">
    <div class="botones-categoria">
        <a href="<?php echo e(route('basket.equipos')); ?>" class="boton-categoria <?php echo e(empty($selectedCategory) ? 'activo' : ''); ?>">
            Todas
        </a>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('basket.equipos', ['category' => $category->id])); ?>"
               class="boton-categoria <?php echo e((int) $selectedCategory === (int) $category->id ? 'activo' : ''); ?>">
                <?php echo e($category->name); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<section class="equipo-jugadores">
    <div class="rejilla-jugadores">
        <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="tarjeta-jugador">
                <div class="foto-jugador">
                    <?php if($equipo->image): ?>
                        <img src="<?php echo e($equipo->image_url); ?>" alt="<?php echo e($equipo->nombre); ?>">
                    <?php else: ?>
                        <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="Sin imagen disponible" class="foto-defecto">
                    <?php endif; ?>
                </div>

                <div class="info-jugador">
                    <p class="posicion-tag"><?php echo e($equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría'); ?></p>
                    <h2><?php echo e($equipo->nombre ?? $equipo->name); ?></h2>
                    <p><strong>Categoría:</strong> <?php echo e($equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría'); ?></p>

                    <?php if(!empty($equipo->descripcion ?? $equipo->description)): ?>
                        <div class="separador"></div>
                        <p><?php echo e($equipo->descripcion ?? $equipo->description); ?></p>
                    <?php else: ?>
                        <div class="separador"></div>
                        <p>Equipo del Bellreguard Club de Basket.</p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($equipos->isEmpty()): ?>
            <div class="contenedor-mensaje-vacio" style="grid-column: 1 / -1;">
                <div class="alerta-basket">
                    <p>No hay equipos disponibles para este filtro.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/equipos/index.blade.php ENDPATH**/ ?>