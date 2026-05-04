<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <div>
        <h2>Gestión de Galería</h2>
        <p style="color: #777;">Fotos y descripciones de la temporada</p>
    </div>

    <a href="<?php echo e(route('galerias.create')); ?>" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nueva Foto
    </a>
</header>

<div class="contenedor-buscador">
    <form action="<?php echo e(route('galerias.search')); ?>" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text" name="search" placeholder="Buscar por título, descripción o categoría..." value="<?php echo e($search ?? ''); ?>" class="input-search">
            <button type="submit" class="btn-buscar"><i class="fas fa-search"></i></button>
        </div>
        <?php if(isset($search) && $search != ''): ?>
            <a href="<?php echo e(route('galerias.index')); ?>" class="btn-limpiar" title="Limpiar búsqueda"><i class="fas fa-times"></i></a>
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
                <th>Imagen</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $galerias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $galeria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><img src="<?php echo e($galeria->image_url); ?>" alt="<?php echo e($galeria->titulo); ?>" width="80" height="60" style="object-fit: cover; border-radius: 6px;"></td>
                    <td><strong><?php echo e($galeria->titulo); ?></strong></td>
                    <td><?php echo e($galeria->categoria); ?></td>
                    <td><?php echo e($galeria->fecha_imagen ? $galeria->fecha_imagen->format('d/m/Y') : '-'); ?></td>
                    <td><?php echo e(\Illuminate\Support\Str::limit($galeria->descripcion, 80)); ?></td>
                    <td style="padding: 25px 15px; vertical-align: middle;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="<?php echo e(route('galerias.show', $galeria->id)); ?>" class="btn-accion" title="Ver detalle" style="background-color: #00b4d8; color: white;"><i class="fas fa-eye"></i></a>
                            <a href="<?php echo e(route('galerias.edit', $galeria->id)); ?>" class="btn-accion editar" title="Editar" style="margin: 0;"><i class="fas fa-pen"></i></a>
                            <form action="<?php echo e(route('galerias.destroy', $galeria->id)); ?>" method="POST" onsubmit="return confirm('¿Eliminar foto?')" style="margin: 0; display: flex; align-items: center;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #777;">Todavía no hay fotos registradas en la galería.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="contenedor-paginacion">
        <?php echo e($galerias->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/galerias/index.blade.php ENDPATH**/ ?>