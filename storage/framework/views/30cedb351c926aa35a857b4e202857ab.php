<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <div>
        <h2>Detalle de Clasificación</h2>
        <p style="color: #777;">Información completa de la fila seleccionada</p>
    </div>
    <a href="<?php echo e(route('clasificaciones.index')); ?>" class="btn-nuevo" style="background-color: #777;"><i class="fas fa-arrow-left"></i> Volver al listado</a>
</header>

<div class="pizarra-admin">
    <div style="padding: 20px;">
        <h3 style="margin-top: 0;"><?php echo e($clasificacion->equipo_nombre); ?></h3>
        <p style="color: #777;"><?php echo e($clasificacion->categoria); ?> · <?php echo e($clasificacion->temporada); ?></p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin: 25px 0;">
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($clasificacion->posicion); ?></h3><p>Posición</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($clasificacion->partidos_jugados); ?></h3><p>Partidos Jugados</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($clasificacion->partidos_ganados); ?></h3><p>Ganados</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($clasificacion->partidos_perdidos); ?></h3><p>Perdidos</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($clasificacion->puntos_favor); ?></h3><p>Puntos a Favor</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($clasificacion->puntos_contra); ?></h3><p>Puntos en Contra</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($clasificacion->puntos); ?></h3><p>Puntos</p></div></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/clasificaciones/show.blade.php ENDPATH**/ ?>