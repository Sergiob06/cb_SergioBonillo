<?php $__env->startSection('contenido_admin'); ?>
<header class="header-admin">
    <div>
        <h2>Bienvenido al Panel</h2>
        <p style="color: #777;">Resumen del contenido que ya está persistido en la base de datos</p>
    </div>
</header>

<div class="pizarra-admin">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px;">
        <div class="tarjeta-vacia">
            <i class="fas fa-tshirt"></i>
            <h3><?php echo e($resumenAdmin['equipos']); ?></h3>
            <p>Equipos</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-users"></i>
            <h3><?php echo e($resumenAdmin['jugadores']); ?></h3>
            <p>Jugadores</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-calendar-alt"></i>
            <h3><?php echo e($resumenAdmin['partidos']); ?></h3>
            <p>Partidos</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-chart-line"></i>
            <h3><?php echo e($resumenAdmin['estadisticas']); ?></h3>
            <p>Estadísticas</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-trophy"></i>
            <h3><?php echo e($resumenAdmin['clasificaciones']); ?></h3>
            <p>Clasificación</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-camera"></i>
            <h3><?php echo e($resumenAdmin['galerias']); ?></h3>
            <p>Galería</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-bag-shopping"></i>
            <h3><?php echo e($resumenAdmin['productos']); ?></h3>
            <p>Productos</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>