<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Bellreguard CB</title>
   <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="contenedor-admin">
        <aside class="sidebar-admin">
            <div class="admin-perfil">
                <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="Logo Admin">
                <div class="admin-info">
                    <h4>Panel Admin</h4>
                    <span>Bellreguard CB</span>
                </div>
            </div>

            <nav class="menu-admin">
                <a href="<?php echo e(route('dashboard')); ?>" class="item-admin <?php echo e(request()->routeIs('dashboard') ? 'activo' : ''); ?>">
                    <i class="fas fa-home"></i> Inicio
                </a>

                <a href="<?php echo e(route('jugadores.index')); ?>" class="item-admin <?php echo e(request()->is('admin/jugadores*') ? 'activo' : ''); ?>">
                    <i class="fas fa-users"></i> Jugadores
                </a>

                <a href="<?php echo e(route('equipos.index')); ?>" class="item-admin <?php echo e(request()->is('admin/equipos*') ? 'activo' : ''); ?>">
                    <i class="fas fa-tshirt"></i> Equipos
                </a>

                <a href="<?php echo e(route('partidos.index')); ?>" class="item-admin <?php echo e(request()->is('admin/partidos*') ? 'activo' : ''); ?>">
                    <i class="fas fa-calendar-alt"></i> Partidos
                </a>

                <a href="<?php echo e(route('estadisticas.index')); ?>" class="item-admin <?php echo e(request()->is('admin/estadisticas*') ? 'activo' : ''); ?>">
                    <i class="fas fa-chart-line"></i> Estadísticas
                </a>

                <a href="<?php echo e(route('clasificaciones.index')); ?>" class="item-admin <?php echo e(request()->is('admin/clasificaciones*') ? 'activo' : ''); ?>">
                    <i class="fas fa-trophy"></i> Clasificación
                </a>

                <a href="<?php echo e(route('galerias.index')); ?>" class="item-admin <?php echo e(request()->is('admin/galerias*') ? 'activo' : ''); ?>">
                    <i class="fas fa-camera"></i> Galería
                </a>

                <a href="<?php echo e(route('productos.index')); ?>" class="item-admin <?php echo e(request()->is('admin/productos*') ? 'activo' : ''); ?>">
                    <i class="fas fa-bag-shopping"></i> Productos
                </a>

                <div class="separador-admin"></div>
    
                <a href="<?php echo e(url('/')); ?>" class="item-admin volver-web">
                    <i class="fas fa-arrow-left"></i> Ver Web
                </a>
</nav>
        </aside>

        <main class="contenido-admin">
            <?php echo $__env->yieldContent('contenido_admin'); ?>
        </main>
    </div>
</body>
</html>
<?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/layouts/admin.blade.php ENDPATH**/ ?>