<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <div>
        <h2>Ficha del Partido</h2>
        <p style="color: #777;">Detalle del encuentro programado</p>
    </div>

    <a href="<?php echo e(route('partidos.index')); ?>" class="btn-nuevo" style="background-color: #777;">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
</header>

<div class="pizarra-admin">
    <div style="display: flex; gap: 40px; align-items: flex-start; padding: 20px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 260px; background: #f9f9f9; padding: 25px; border-radius: 10px; border: 1px solid #eee;">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fb8500; display: inline-block;">Encuentro</h3>

            <p style="font-size: 1.2rem; margin: 20px 0 10px;">
                <strong><?php echo e($partido->equipoLocal->nombre ?? $partido->equipo_local); ?></strong>
            </p>
            <p style="margin: 0 0 10px; color: #777;">vs</p>
            <p style="font-size: 1.2rem; margin: 0;">
                <strong><?php echo e($partido->equipoVisitante->nombre ?? $partido->equipo_visitante); ?></strong>
            </p>
        </div>

        <div style="flex: 2; min-width: 320px;">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fb8500; display: inline-block;">Información General</h3>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Fecha y hora:</strong> <?php echo e($partido->fecha_partido->format('d/m/Y H:i')); ?>

            </p>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Lugar:</strong> <?php echo e($partido->lugar); ?>

            </p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <div style="display: flex; gap: 10px;">
                <a href="<?php echo e(route('partidos.edit', $partido->id)); ?>" class="btn-accion editar" title="Editar" style="margin: 0;">
                    <i class="fas fa-pen"></i>
                </a>

                <form action="<?php echo e(route('partidos.destroy', $partido->id)); ?>" method="POST" onsubmit="return confirm('¿Eliminar partido?')" style="margin: 0; display: flex; align-items: center;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/partidos/show.blade.php ENDPATH**/ ?>