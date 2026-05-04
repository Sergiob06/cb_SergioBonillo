<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <div>
        <h2>Gestión de Jugadores</h2>
        <p style="color: #777;">Plantilla completa del club</p>
    </div>
    
    <a href="<?php echo e(route('jugadores.create')); ?>" class="btn-nuevo">
        <i class="fas fa-plus"></i> Añadir Jugador
    </a>
</header>



<div class="contenedor-buscador">
    <form action="<?php echo e(route('jugadores.search')); ?>" method="GET" class="form-buscador">
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
            <a href="<?php echo e(route('jugadores.index')); ?>" class="btn-limpiar" title="Limpiar búsqueda">
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
                <th>Foto</th>
                <th>Dorsal</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Club</th>
                <th>Categoría</th>
                <th>Posición</th>
                <th>Fecha Nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $jugadores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jugador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                
                <td>
                    <div class="contenedor-escudo" style="width: 60px; height: 60px; overflow: hidden; border: 1px solid #ddd; border-radius: 4px;">
                        <?php if($jugador->image): ?>
                            <img src="<?php echo e($jugador->image_url); ?>" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="Sin foto" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                </td>
                
                <td>
                    <span class="dorsal-badge">#<?php echo e($jugador->dorsal); ?></span>
                </td>            
                
                <td><strong><?php echo e($jugador->nombre); ?></strong></td>

                
                <td><strong><?php echo e($jugador->apellido); ?></strong></td>
                
                
                <td>
                    <?php echo e($jugador->equipo->nombre ?? 'Sin Equipo'); ?>

                </td>

                
                <td>
                    <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold;">
                        <?php echo e($jugador->equipo->categoria ?? '-'); ?>

                    </span>
                </td>

                
                <td><?php echo e($jugador->posicion); ?></td>

                
                <td>
                    
                    <?php echo e($jugador->fecha_nacimiento ? \Carbon\Carbon::parse($jugador->fecha_nacimiento)->format('d/m/Y') : 'No asignada'); ?>

                </td>
                
                
                <td style="padding: 25px 15px; vertical-align: middle;">
                    <div style="display: flex; gap: 8px; align-items: center; justify-content: flex-start;">
                        
                        
                        <a href="<?php echo e(route('jugadores.show', $jugador->id)); ?>" class="btn-accion" style="background: #e3f2fd; color: #1976d2;" title="Ver Detalles">
                            <i class="fas fa-eye"></i>
                        </a>

                        
                        <a href="<?php echo e(route ('jugadores.edit', $jugador->id)); ?>" class="btn-accion editar" title="Editar" style="margin: 0;">
                            <i class="fas fa-pen"></i>
                        </a>
                        
                        
                        <form action="<?php echo e(route ('jugadores.destroy', $jugador->id)); ?>" method="POST" onsubmit="return confirm('¿Eliminar jugador?')" style="margin: 0; display: flex; align-items: center;">
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
        <?php echo e($jugadores->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/jugadores/index.blade.php ENDPATH**/ ?>