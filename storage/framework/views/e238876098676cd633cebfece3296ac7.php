<header class="site-header">
<nav class="barra-navegacion">

    
    <div class="navegacion-izquierda">
        <img src="<?php echo e(asset('img/basket.jpeg')); ?>" class="logo-navegacion" alt="Logo Bellreguard" />
        <div class="titulo-navegacion">
            <h3>Bellreguard</h3>
            <span>Club de Basket</span>
        </div>
    </div>

    
    <div class="contenedor-menu-movil">

        
        <button
            class="boton-menu-movil"
            id="botonMenu"
            type="button"
            aria-label="Abrir menú"
            aria-controls="menuNavegacion"
            aria-expanded="false"
        >
            ☰
        </button>

        
        <ul class="menu-navegacion" id="menuNavegacion" aria-hidden="true">
            <li><a href="<?php echo e(url('/')); ?>">Inicio</a></li>
            <li><a href="<?php echo e(route('basket.equipos')); ?>">Equipos</a></li>
            <li><a href="<?php echo e(route('basket.merchandising')); ?>">Merchandising</a></li>
            <li><a href="<?php echo e(route('basket.clasificacion')); ?>">Clasificación</a></li>
            <li><a href="<?php echo e(route('basket.estadisticas')); ?>">Estadísticas</a></li>
            <li><a href="<?php echo e(route('basket.partidos')); ?>">Partidos</a></li>
            <li><a href="<?php echo e(route('basket.galeria')); ?>">Galería</a></li>
            <li><a href="<?php echo e(route('basket.contacto')); ?>">Contacto</a></li>

            
            <li class="login-movil">
                <a href="<?php echo e(route('login')); ?>" class="boton-inicio-sesion">
                    Iniciar Sesión
                </a>
            </li>
        </ul>

    </div>

    
    <div class="login-desktop">
        <a href="<?php echo e(route('login')); ?>" style="text-decoration: none;">
            <button class="boton-inicio-sesion">Iniciar Sesión</button>
        </a>
    </div>

</nav>

<div class="overlay-menu" id="overlayMenu" hidden aria-hidden="true"></div>
</header>
<?php /**PATH /var/www/html/resources/views/partials/nav.blade.php ENDPATH**/ ?>