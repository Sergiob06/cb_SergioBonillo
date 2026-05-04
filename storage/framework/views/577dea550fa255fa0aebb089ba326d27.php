<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <div>
        <h2>Ficha de Estadísticas</h2>
        <p style="color: #777;">Detalle del rendimiento por temporada</p>
    </div>

    <a href="<?php echo e(route('estadisticas.index')); ?>" class="btn-nuevo" style="background-color: #777;">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
</header>

<div class="pizarra-admin">
    <div style="padding: 20px;">
        <h3 style="margin-top: 0;"><?php echo e($estadistica->equipo->nombre ?? 'Sin equipo'); ?> - <?php echo e($estadistica->temporada); ?></h3>
        <p style="color: #777;"><?php echo e($estadistica->equipo->categoria ?? 'Sin categoría'); ?></p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin: 25px 0;">
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($estadistica->puntos_totales); ?></h3><p>Puntos Totales</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($estadistica->rebotes); ?></h3><p>Rebotes</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($estadistica->asistencias); ?></h3><p>Asistencias</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($estadistica->robos); ?></h3><p>Robos</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($estadistica->partidos_jugados); ?></h3><p>Partidos</p></div></div>
            <div class="card-stat"><div class="stat-info"><h3><?php echo e($estadistica->victorias); ?>-<?php echo e($estadistica->derrotas); ?></h3><p>Balance</p></div></div>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="<?php echo e(route('estadisticas.edit', $estadistica->id)); ?>" class="btn-accion editar" title="Editar" style="margin: 0;">
                <i class="fas fa-pen"></i>
            </a>

            <form action="<?php echo e(route('estadisticas.destroy', $estadistica->id)); ?>" method="POST" onsubmit="return confirm('¿Eliminar estadística?')" style="margin: 0; display: flex; align-items: center;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/estadisticas/show.blade.php ENDPATH**/ ?>