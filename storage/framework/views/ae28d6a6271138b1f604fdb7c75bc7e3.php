<?php $__env->startSection('title', 'Estadísticas - Bellreguard Club de Basket'); ?>

<?php $__env->startSection('contenido'); ?>
<section class="seccion-estadisticas-header">
    <div class="header-contenido">
        <h1>Estadísticas de Partidos</h1>
        <p>Resumen sencillo de los partidos jugados por equipos locales</p>
    </div>
</section>

<section class="contenedor-estadisticas">
    <form action="<?php echo e(route('basket.estadisticas')); ?>" method="GET" class="public-filters public-filters-form">
        <div class="public-filter-group public-filter-group--search public-search-input">
            <input type="text"
                   name="search"
                   placeholder="Buscar por partido, equipo o categoría..."
                   value="<?php echo e($search ?? ''); ?>"
                   class="public-filter-control">
            <button type="submit" class="public-search-button" aria-label="Buscar estadísticas">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <select name="equipo" class="public-filter-control public-filter-select" aria-label="Filtrar por equipo">
            <option value="">Todos los equipos locales</option>
            <?php $__currentLoopData = $equiposLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($equipo->id); ?>" <?php echo e((int) $equipoSeleccionado === (int) $equipo->id ? 'selected' : ''); ?>>
                    <?php echo e($equipo->nombre); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="categoria" class="public-filter-control public-filter-select" aria-label="Filtrar por categoría">
            <option value="">Todas las categorías</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php echo e((int) $categoriaSeleccionada === (int) $category->id ? 'selected' : ''); ?>>
                    <?php echo e($category->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <button type="submit" class="btn-public btn-public--primary public-filter-button">Filtrar</button>

        <?php if(($search ?? '') !== '' || $equipoSeleccionado || $categoriaSeleccionada): ?>
            <a href="<?php echo e(route('basket.estadisticas')); ?>" class="btn-public btn-public--secondary public-filter-button">Limpiar</a>
        <?php endif; ?>
    </form>

    <?php $__empty_1 = true; $__currentLoopData = $partidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $nombreLocal = $partido?->equipoLocal?->nombre ?? $partido?->equipo_local ?? 'Sin local';
            $nombreVisitante = $partido?->equipoVisitante?->nombre ?? $partido?->equipo_visitante ?? 'Sin visitante';
        ?>

        <article class="caja-detalle estadistica-card">
            <div class="detalle-header">
                <i class="fas fa-chart-line icon-red"></i>
                <div>
                    <h4><?php echo e($nombreLocal); ?> vs <?php echo e($nombreVisitante); ?></h4>
                    <p class="estadistica-card-meta">
                        <?php echo e($partido?->fecha_partido?->format('d/m/Y H:i') ?? 'Sin fecha'); ?>

                        · <?php echo e($partido?->lugar ?? 'Sin lugar'); ?>

                        · Resultado: <?php echo e($partido?->resultado ?? 'Pendiente'); ?>

                        · Estadísticas: <?php echo e($partido?->equipo_estadisticas_resuelto?->nombre ?? 'Bellreguard'); ?>

                    </p>
                </div>
            </div>

            <div class="rejilla-stats-top estadistica-card-stats">
                <div class="card-stat"><div class="stat-info"><h3><?php echo e($partido->puntos_anotados ?? '-'); ?></h3><p>Puntos anotados</p></div></div>
                <div class="card-stat"><div class="stat-info"><h3><?php echo e($partido->puntos_recibidos ?? '-'); ?></h3><p>Puntos recibidos</p></div></div>
                <div class="card-stat"><div class="stat-info"><h3><?php echo e($partido->diferencia_puntos ?? '-'); ?></h3><p>Diferencia</p></div></div>
                <div class="card-stat"><div class="stat-info"><h3><?php echo e($partido->rebotes ?? '-'); ?></h3><p>Rebotes</p></div></div>
            </div>

            <div class="estadistica-card-actions">
                <a href="<?php echo e(route('basket.partidos.show', $partido)); ?>" class="btn-public btn-public--primary">
                    <i class="fas fa-arrow-right"></i>
                    Ver partido
                </a>
            </div>
        </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="caja-equipo full-width">
            <div class="titulo-equipo">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Sin estadísticas disponibles</h3>
                    <p>No hay partidos con estadísticas para los filtros seleccionados.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/basket/estadisticas.blade.php ENDPATH**/ ?>