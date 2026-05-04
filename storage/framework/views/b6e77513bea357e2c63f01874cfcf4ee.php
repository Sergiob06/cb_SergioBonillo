<?php $__env->startSection('title', 'Inicio - Bellreguard Club de Basket'); ?>


<?php $__env->startSection('contenido'); ?>

<section class="seccion-partidos-header">
    <div class="header-contenido">
        <div class="header-texto">
            <h1>Próximos Partidos</h1>
            <p>Consulta el calendario real de todos nuestros equipos</p>
        </div>
    </div>
</section>

<section class="seccion-calendario">
    <div class="rejilla-partidos">
        <?php $__empty_1 = true; $__currentLoopData = $partidosAgrupados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombreEquipo => $partidosEquipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $equipoClub = $partidosEquipo->first()->equipoLocal ?? $partidosEquipo->first()->equipoVisitante;
            ?>

            <div class="caja-equipo full-width">
                <div class="titulo-equipo">
                    <i class="fa-solid fa-trophy"></i>
                    <div>
                        <h3><?php echo e($nombreEquipo); ?></h3>
                        <p><?php echo e($equipoClub?->category?->name ?? $equipoClub?->categoria ?? 'Calendario del equipo'); ?></p>
                    </div>
                </div>

                <div class="rejilla-partido-doble">
                    <?php $__currentLoopData = $partidosEquipo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $nombreLocal = $partido->equipoLocal->nombre ?? $partido->equipo_local;
                            $nombreVisitante = $partido->equipoVisitante->nombre ?? $partido->equipo_visitante;
                            $logoLocal = $partido->equipoLocal && $partido->equipoLocal->imagen_club
                                ? $partido->equipoLocal->image_url
                                : ($equipoClub && $nombreLocal === $equipoClub->nombre && $equipoClub->imagen_club
                                ? $equipoClub->image_url
                                : asset('img/basket.jpeg'));
                            $logoVisitante = $partido->equipoVisitante && $partido->equipoVisitante->imagen_club
                                ? $partido->equipoVisitante->image_url
                                : ($equipoClub && $nombreVisitante === $equipoClub->nombre && $equipoClub->imagen_club
                                ? $equipoClub->image_url
                                : asset('img/basket.jpeg'));
                        ?>

                        <div class="tarjeta-partido">
                            <span class="etiqueta-proximo activo">Próximo</span>
                            <div class="enfrentamiento">
                                <div class="equipo local">
                                    <img src="<?php echo e($logoLocal); ?>" alt="<?php echo e($nombreLocal); ?>">
                                    <p><?php echo e($nombreLocal); ?></p>
                                </div>

                                <span class="vs">VS</span>

                                <div class="equipo visitante">
                                    <img src="<?php echo e($logoVisitante); ?>" alt="<?php echo e($nombreVisitante); ?>">
                                    <p><?php echo e($nombreVisitante); ?></p>
                                </div>
                            </div>

                            <div class="info-adicional">
                                <div class="dato-horario">
                                    <i class="fa-regular fa-calendar-alt"></i>
                                    <?php echo e($partido->fecha_partido->locale('es')->translatedFormat('l, d F Y')); ?>

                                </div>
                                <div class="dato-horario">
                                    <i class="fa-regular fa-clock"></i>
                                    <?php echo e($partido->fecha_partido->format('H:i')); ?>h
                                </div>
                                <div class="dato-lugar">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <?php echo e($partido->lugar); ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="caja-equipo full-width">
                <div class="titulo-equipo">
                    <i class="fa-solid fa-calendar-alt"></i>
                    <div>
                        <h3>Sin partidos programados</h3>
                        <p>Cuando el administrador añada encuentros, aparecerán aquí automáticamente.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/basket/partidos.blade.php ENDPATH**/ ?>