 

<?php $__env->startSection('contenido_admin'); ?> 
<header class="header-admin">
    <div>
        <h2>Gestión de Equipos</h2>
        <p style="color: #777;">Categorías y grupos del Bellreguard CB</p>
    </div>
    
    <a href="<?php echo e(route('equipos.create')); ?>" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nuevo Equipo
    </a>
</header>


<div class="contenedor-buscador">
    <form action="<?php echo e(route('equipos.search')); ?>" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text" 
                   name="search" 
                   placeholder="Buscar por nombre..." 
                   value="<?php echo e($search ?? ''); ?>" 
                   class="input-search">
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <?php if(isset($search) && $search != ''): ?>
            <a href="<?php echo e(route('equipos.index')); ?>" class="btn-limpiar" title="Limpiar búsqueda">
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
                <th>Escudo</th>
                <th>Nombre Equipo</th>
                <th>Categoría</th>
                <th>Nº Jugadores</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            
            <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <div class="contenedor-escudo">
                        
                        <img src="<?php echo e($equipo->image_url); ?>" alt="Escudo" width="60" height="60" style="object-fit: contain;">
                    </div> 
                </td>
                <td><strong><?php echo e($equipo->nombre); ?></strong></td>
                <td><?php echo e($equipo->category->name ?? $equipo->categoria); ?></td>
                <td><?php echo e($equipo->jugadores_count); ?></td>
                <td style="padding: 25px 15px; vertical-align: middle;">
                    
                    <div style="display: flex; gap: 8px; align-items: center; justify-content: flex-start;">
                        
                        <a href="<?php echo e(route('equipos.show', $equipo->id)); ?>" class="btn-accion" title="Ver Detalle" style="background-color: #00b4d8; color: white;">
                            <i class="fas fa-eye"></i>
                        </a>

                        
                        <a href="<?php echo e(route('equipos.edit', $equipo->id)); ?>" class="btn-accion editar" title="Editar" style="margin: 0;">
                            <i class="fas fa-pen"></i>
                        </a>
                        
                        
                        <form action="<?php echo e(route('equipos.destroy', $equipo->id)); ?>" method="POST" onsubmit="return confirm('¿Eliminar equipo?')" style="margin: 0; display: flex; align-items: center;">
                            <?php echo csrf_field(); ?> 
                            <?php echo method_field('DELETE'); ?> 
                            <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
        
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    
    <div class="contenedor-paginacion">
        <?php echo e($equipos->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/equipos/index.blade.php ENDPATH**/ ?>