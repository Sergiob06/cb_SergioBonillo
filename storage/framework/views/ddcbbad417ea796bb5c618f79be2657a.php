<?php $__env->startSection('title', 'Inicio - Bellreguard Club de Basket'); ?>


<?php $__env->startSection('contenido'); ?>
<?php
    $calcularMedia = function ($valor, $partidos) {
        return $partidos > 0 ? number_format($valor / $partidos, 1) : '0.0';
    };
?>

<section class="seccion-estadisticas-header">
    <div class="header-contenido">
        <h1>Estadísticas del Equipo</h1>
        <p>Datos y métricas de rendimiento por temporada</p>
        <div class="temporada-actual">
            <i class="fas fa-calendar-alt"></i>
            <?php echo e($estadisticaSeleccionada ? $estadisticaSeleccionada->temporada : 'Sin estadísticas disponibles'); ?>

        </div>
    </div>
</section>

<section class="contenedor-estadisticas">
    <?php if($estadisticas->isNotEmpty() && $estadisticaSeleccionada): ?>
        <div class="selector-temporadas" style="display: flex; flex-wrap: wrap; gap: 10px;">
            <?php $__currentLoopData = $estadisticas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estadistica): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('basket.estadisticas', ['estadistica' => $estadistica->id])); ?>"
                   class="btn-temp <?php echo e($estadisticaSeleccionada->id === $estadistica->id ? 'activo' : ''); ?>"
                   style="text-decoration: none; display: inline-block;">
                    <?php echo e($estadistica->equipo?->nombre ?? 'Equipo'); ?> · <?php echo e($estadistica->temporada); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div style="margin-bottom: 20px; color: #555;">
            <strong><?php echo e($estadisticaSeleccionada->equipo?->nombre ?? 'Equipo sin asignar'); ?></strong>
            <span style="margin-left: 8px;"><?php echo e($estadisticaSeleccionada->equipo?->categoria ?? ''); ?></span>
        </div>

        <div class="rejilla-stats-top">
            <div class="card-stat">
                <div class="icon-box red"><i class="fas fa-bullseye"></i></div>
                <div class="stat-info">
                    <h3><?php echo e($estadisticaSeleccionada->puntos_totales); ?></h3>
                    <p>Puntos Totales</p>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box orange"><i class="fas fa-redo"></i></div>
                <div class="stat-info">
                    <h3><?php echo e($estadisticaSeleccionada->rebotes); ?></h3>
                    <p>Rebotes</p>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box red-dark"><i class="fas fa-hand-paper"></i></div>
                <div class="stat-info">
                    <h3><?php echo e($estadisticaSeleccionada->asistencias); ?></h3>
                    <p>Asistencias</p>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box red-soft"><i class="fas fa-shield-alt"></i></div>
                <div class="stat-info">
                    <h3><?php echo e($estadisticaSeleccionada->robos); ?></h3>
                    <p>Robos</p>
                </div>
            </div>
        </div>

        <div class="rejilla-detalles">
            <div class="caja-detalle">
                <div class="detalle-header">
                    <i class="fas fa-basketball-ball icon-red"></i>
                    <h4>Rendimiento General</h4>
                </div>
                <div class="fila-detalle">
                    <span>Puntos Totales</span>
                    <div class="valor">
                        <strong><?php echo e($estadisticaSeleccionada->puntos_totales); ?></strong>
                        <small><?php echo e($calcularMedia($estadisticaSeleccionada->puntos_totales, $estadisticaSeleccionada->partidos_jugados)); ?> por partido</small>
                    </div>
                </div>
                <div class="fila-detalle">
                    <span>Asistencias</span>
                    <div class="valor">
                        <strong><?php echo e($estadisticaSeleccionada->asistencias); ?></strong>
                        <small><?php echo e($calcularMedia($estadisticaSeleccionada->asistencias, $estadisticaSeleccionada->partidos_jugados)); ?> por partido</small>
                    </div>
                </div>
                <div class="fila-detalle">
                    <span>Robos</span>
                    <div class="valor">
                        <strong><?php echo e($estadisticaSeleccionada->robos); ?></strong>
                        <small><?php echo e($calcularMedia($estadisticaSeleccionada->robos, $estadisticaSeleccionada->partidos_jugados)); ?> por partido</small>
                    </div>
                </div>
            </div>

            <div class="caja-detalle">
                <div class="detalle-header">
                    <i class="fas fa-chart-line icon-red"></i>
                    <h4>Rendimiento Defensivo</h4>
                </div>
                <div class="fila-detalle">
                    <span>Rebotes Defensivos</span>
                    <div class="valor">
                        <strong><?php echo e($estadisticaSeleccionada->rebotes_defensivos); ?></strong>
                        <small><?php echo e($calcularMedia($estadisticaSeleccionada->rebotes_defensivos, $estadisticaSeleccionada->partidos_jugados)); ?> por partido</small>
                    </div>
                </div>
                <div class="fila-detalle">
                    <span>Rebotes Ofensivos</span>
                    <div class="valor">
                        <strong><?php echo e($estadisticaSeleccionada->rebotes_ofensivos); ?></strong>
                        <small><?php echo e($calcularMedia($estadisticaSeleccionada->rebotes_ofensivos, $estadisticaSeleccionada->partidos_jugados)); ?> por partido</small>
                    </div>
                </div>
                <div class="fila-detalle">
                    <span>Tapones</span>
                    <div class="valor">
                        <strong><?php echo e($estadisticaSeleccionada->tapones); ?></strong>
                        <small><?php echo e($calcularMedia($estadisticaSeleccionada->tapones, $estadisticaSeleccionada->partidos_jugados)); ?> por partido</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="rejilla-resumen-final">
            <div class="card-resumen red">
                <div class="resumen-head">
                    <i class="fas fa-trophy"></i>
                    <span>Partidos</span>
                </div>
                <div class="resumen-body">
                    <h2><?php echo e($estadisticaSeleccionada->partidos_jugados); ?></h2>
                    <p>Partidos Jugados</p>
                </div>
            </div>
            <div class="card-resumen green">
                <div class="resumen-head">
                    <i class="fas fa-check-circle"></i>
                    <span>Victorias</span>
                </div>
                <div class="resumen-body">
                    <h2><?php echo e($estadisticaSeleccionada->victorias); ?></h2>
                    <p>Partidos Ganados</p>
                </div>
            </div>
            <div class="card-resumen dark">
                <div class="resumen-head">
                    <i class="fas fa-times-circle"></i>
                    <span>Derrotas</span>
                </div>
                <div class="resumen-body">
                    <h2><?php echo e($estadisticaSeleccionada->derrotas); ?></h2>
                    <p>Partidos Perdidos</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="caja-equipo full-width">
            <div class="titulo-equipo">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Sin estadísticas disponibles</h3>
                    <p>Cuando el administrador añada registros, aparecerán aquí automáticamente.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/basket/estadisticas.blade.php ENDPATH**/ ?>