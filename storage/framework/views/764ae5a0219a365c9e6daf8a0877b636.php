<?php $__env->startSection('title', 'Equipos'); ?>

<?php $__env->startSection('contenido'); ?>
<section class="seccion-equipo-header">
    <h1>Equipos</h1>
    <p>Descubre todos los equipos del club y filtra por categoría.</p>
</section>

<section class="navegacion-categorias">
    <form action="<?php echo e(route('basket.equipos')); ?>" method="GET" class="public-filters public-filters-form">
        <div class="public-filter-group public-filter-group--search public-search-input">
            <input type="text"
                   name="search"
                   placeholder="Buscar por equipo o categoría..."
                   value="<?php echo e($search ?? ''); ?>"
                   class="public-filter-control">
            <button type="submit" class="public-search-button" aria-label="Buscar equipos">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <select name="category" class="public-filter-control public-filter-select" aria-label="Filtrar por categoría" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php echo e((int) $selectedCategory === (int) $category->id ? 'selected' : ''); ?>>
                    <?php echo e($category->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <?php if(($search ?? '') !== '' || $selectedCategory): ?>
            <a href="<?php echo e(route('basket.equipos')); ?>" class="btn-public btn-public--secondary public-filter-button">Limpiar filtro</a>
        <?php endif; ?>
    </form>
</section>

<section class="equipo-jugadores">
    <div class="rejilla-jugadores">
        <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="tarjeta-jugador">
                <div class="foto-jugador foto-jugador--logo">
                    <img src="<?php echo e($equipo->image_url); ?>" alt="<?php echo e($equipo->nombre); ?>">
                </div>

                <div class="info-jugador">
                    <p class="posicion-tag"><?php echo e($equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría'); ?></p>
                    <h2><?php echo e($equipo->nombre ?? $equipo->name); ?></h2>
                    <p><strong>Categoría:</strong> <?php echo e($equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría'); ?></p>
                    <p><strong>Plantilla:</strong> <?php echo e($equipo->es_local ? $equipo->jugadores_count . ' jugadores' : 'Equipo externo'); ?></p>

                    <?php if(!empty($equipo->descripcion ?? $equipo->description)): ?>
                        <div class="separador"></div>
                        <p><?php echo e($equipo->descripcion ?? $equipo->description); ?></p>
                    <?php else: ?>
                        <div class="separador"></div>
                        <p>Equipo del Bellreguard Club de Basket.</p>
                    <?php endif; ?>

                    <div class="card-public-actions">
                        <a href="<?php echo e(route('basket.partidos', ['equipo' => $equipo->id])); ?>" class="btn-public btn-public--secondary">
                            Ver partidos
                        </a>
                        <?php if($equipo->es_local): ?>
                            <a href="<?php echo e(route('basket.equipos.show', $equipo)); ?>" class="btn-public btn-public--primary">
                                Ver plantilla
                            </a>
                        <?php endif; ?>
                    </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/equipos/index.blade.php ENDPATH**/ ?>