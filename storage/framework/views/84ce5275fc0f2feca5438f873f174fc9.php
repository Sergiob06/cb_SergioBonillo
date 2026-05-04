<?php $__env->startSection('contenido_admin'); ?>
<div class="contenedor-edit-jugador">
    
    <header class="header-ficha">
        <div>
            <h2>Ficha del Jugador</h2>
            <p>Perfil detallado de la plantilla</p>
        </div>
        <div class="acciones-header-ficha">
            
            <a href="<?php echo e(route('jugadores.edit', $jugador->id)); ?>" class="btn-ficha-accion btn-ficha-edit">
                <i class="fas fa-edit"></i>&nbsp;EDITAR
            </a>

            <form action="<?php echo e(route('jugadores.destroy', $jugador->id)); ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este jugador?')" style="margin: 0;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn-ficha-accion btn-ficha-delete">
                    <i class="fas fa-trash-alt"></i>&nbsp;ELIMINAR
                </button>
            </form>

            <a href="<?php echo e(route('jugadores.index')); ?>" class="btn-ficha-accion btn-ficha-volver" style="background-color: #777;">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </header>

    <div class="pizarra-ficha">
        <div class="grid-formulario-fichas">
            
            <div class="seccion-form-ficha ficha-col-foto">
                <div class="ficha-foto-principal">
                    <?php if($jugador->image): ?>
                        <img src="<?php echo e($jugador->image_url); ?>" alt="Foto oficial">
                    <?php else: ?>
                        <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="Sin foto">
                    <?php endif; ?>
                </div>
                <p style="margin-top: 15px; color: #888; font-style: italic;">Fotografía Oficial del Club</p>
            </div>

            <div class="seccion-form-ficha ficha-col-datos">
                <h3 style="margin-top: 0;"><i class="fas fa-id-card"></i> Datos Técnicos</h3>
                
                <div class="campo-ficha ficha-info-row" style="margin-bottom: 25px;">
                    <label>Nombre Completo</label>
                    <p class="ficha-nombre-principal">
                        <?php echo e($jugador->nombre); ?> <br> <?php echo e($jugador->apellido); ?>

                    </p>
                </div>

                <div class="ficha-dorsal-wrapper">
                    <div class="campo-ficha" style="flex: 1;">
                        <label>Dorsal</label>
                        <div>
                            <span class="ficha-valor-azul">#<?php echo e($jugador->dorsal ?? '00'); ?></span>
                        </div>
                    </div>
                    <div class="campo-ficha" style="flex: 2;">
                        <label>Posición</label>
                        <p class="ficha-dato-destacado">
                            <i class="fas fa-basketball-ball"></i> <?php echo e($jugador->posicion); ?>

                        </p>
                    </div>
                </div>

                <div class="campo-ficha ficha-info-row">
                    <label>Equipo Actual</label>
                    <p class="ficha-valor-azul">
                        <i class="fas fa-shield-alt"></i> <?php echo e($jugador->equipo->nombre ?? 'Sin equipo'); ?>

                    </p>
                </div>

                <div class="campo-ficha ficha-info-row">
                    <label>Categoría</label>
                    <p class="ficha-valor-azul">
                        <i class="fas fa-tags"></i> <?php echo e($jugador->equipo->categoria ?? 'Sin Categoría'); ?>

                    </p>
                </div>

                <div class="campo-ficha" style="margin-top: 20px;">
                    <label>Fecha de Nacimiento</label>
                    <p style="margin: 0; font-size: 1.1rem; color: #4a5568;">
                        <i class="far fa-calendar-alt"></i> 
                        <?php echo e($jugador->fecha_nacimiento ? \Carbon\Carbon::parse($jugador->fecha_nacimiento)->format('d/m/Y') : 'No registrada'); ?>

                    </p>
                </div>
            </div>

        </div>

        <div class="form-acciones-ficha" style="justify-content: center; border-top: 1px solid #eee; margin-top: 40px; padding-top: 20px;">
            <p style="color: #bbb; font-size: 0.8rem; margin: 0;">
                Registro creado el: <?php echo e($jugador->created_at->format('d/m/Y H:i')); ?> | ID Sistema: #<?php echo e($jugador->id); ?>

            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/jugadores/show.blade.php ENDPATH**/ ?>