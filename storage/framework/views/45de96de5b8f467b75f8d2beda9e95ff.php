<?php $__env->startSection('title', 'Inicio - Bellreguard Club de Basket'); ?>


<?php $__env->startSection('contenido'); ?>

    <section class="seccion-hero">
        <div class="hero-texto">
            <h1>Bellreguard Club de Basket</h1>
            <p>En Club Bàsquet Bellreguard vivimos el baloncesto con pasión, esfuerzo y compromiso. Cada partido y entrenamiento refleja nuestros valores: compañerismo, superación y dedicación dentro y fuera de la pista.

Más que un club, somos una familia unida por la ilusión de crecer, competir y disfrutar del baloncesto. Únete a nuestro proyecto y forma parte de esta pasión.</p>
            <div class="botones-hero">
                <a href="<?php echo e(route('basket.partidos')); ?>" class="boton-principal" style="text-decoration: none;">Ver Próximos
                    Partidos</a>
                <a href="<?php echo e(route('basket.equipos')); ?>" class="boton-secundario" style="text-decoration: none;">Ver Equipos</a>
            </div>
        </div>
        <div class="hero-imagen">
            <img src="<?php echo e(asset('img/basket.png')); ?>" alt="Equipo celebrando" />
        </div>
    </section>

    <section class="home-section">
        <div class="home-section-shell">
            <div class="home-section-box">
                <div class="home-section-header">
                    <h2>Próximos Partidos</h2>
                    <p class="subtitulo">Entérate de los próximos partidos</p>
                </div>

                <div class="home-card-grid home-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $proximosPartidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="home-card">
                            <div class="home-card-content">
                                <span class="fecha"><?php echo e($partido->fecha_partido->translatedFormat('d F Y - H:i')); ?></span>
                                <h3><?php echo e($partido->equipo_local); ?> vs <?php echo e($partido->equipo_visitante); ?></h3>
                                <p><?php echo e($partido->lugar); ?></p>
                                <a href="<?php echo e(route('basket.partidos')); ?>">Ver calendario →</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="home-card">
                            <div class="home-card-content">
                                <span class="fecha">Agenda vacía</span>
                                <h3>No hay partidos programados</h3>
                                <p></p>
                                <a href="<?php echo e(route('basket.partidos')); ?>">Ir a partidos →</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if($ultimasFotos->isNotEmpty()): ?>
        <section class="home-section">
            <div class="home-section-shell">
                <div class="home-section-box">
                    <div class="home-section-header">
                        <h2>Últimas Fotos</h2>
                        <p class="subtitulo">Contenido real de la galería del club</p>
                    </div>

                    <div class="home-card-grid home-grid">
                        <?php $__currentLoopData = $ultimasFotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="home-card">
                                <img src="<?php echo e($foto->image_url); ?>" alt="<?php echo e($foto->titulo); ?>" />
                                <div class="home-card-content">
                                    <span
                                        class="fecha"><?php echo e($foto->fecha_imagen ? $foto->fecha_imagen->translatedFormat('d F Y') : 'Sin fecha'); ?></span>
                                    <h3><?php echo e($foto->titulo); ?></h3>
                                    <p><?php echo e(\Illuminate\Support\Str::limit($foto->descripcion, 90)); ?></p>
                                    <a href="<?php echo e(route('basket.galeria')); ?>">Ver galería →</a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/basket/inicio.blade.php ENDPATH**/ ?>