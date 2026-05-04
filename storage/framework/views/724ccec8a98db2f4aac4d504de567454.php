<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <div>
        <h2>Gestión de Partidos</h2>
        <p style="color: #777;">Calendario de encuentros del Bellreguard CB</p>
    </div>

    <a href="<?php echo e(route('partidos.create')); ?>" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nuevo Partido
    </a>
</header>

<div class="contenedor-buscador">
    <form action="<?php echo e(route('partidos.search')); ?>" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text"
                   name="search"
                   placeholder="Buscar por equipo o lugar..."
                   value="<?php echo e($search ?? ''); ?>"
                   class="input-search">
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <?php if(isset($search) && $search != ''): ?>
            <a href="<?php echo e(route('partidos.index')); ?>" class="btn-limpiar" title="Limpiar búsqueda">
                <i class="fas fa-times"></i>
            </a>
        <?php endif; ?>
    </form>
</div>

<?php if(session('mensaje')): ?>
    <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> <?php echo e(session('mensaje')); ?>

    </div>
<?php endif; ?>

<div class="pizarra-admin">
    <table class="tabla-admin">
        <thead>
            <tr>
                <th>Local</th>
                <th>Visitante</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $partidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($partido->equipoLocal->nombre ?? $partido->equipo_local); ?></td>
                    <td><?php echo e($partido->equipoVisitante->nombre ?? $partido->equipo_visitante); ?></td>
                    <td><?php echo e($partido->fecha_partido->format('d/m/Y H:i')); ?></td>
                    <td><?php echo e($partido->lugar); ?></td>
                    <td style="padding: 25px 15px; vertical-align: middle;">
                        <div style="display: flex; gap: 8px; align-items: center; justify-content: flex-start;">
                            <a href="<?php echo e(route('partidos.show', $partido->id)); ?>" class="btn-accion" title="Ver detalle" style="background-color: #00b4d8; color: white;">
                                <i class="fas fa-eye"></i>
                            </a>

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
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #777;">Todavía no hay partidos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="contenedor-paginacion">
        <?php echo e($partidos->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/partidos/index.blade.php ENDPATH**/ ?>