<?php $__env->startSection('title', 'Merchandising - Bellreguard Club de Basket'); ?>


<?php $__env->startSection('contenido'); ?>
<section class="seccion-merchandising-header">
    <h1>Merchandising Oficial</h1>
    <p>Lleva los colores del Bellreguard CB contigo</p>
    <div class="iconos-merchandising">
        <i class="fa-solid fa-shirt"></i>
        <i class="fa-solid fa-hat-cap"></i>
        <i class="fa-solid fa-bag-shopping"></i>
    </div>
</section>

<section class="productos-oficiales">
    <h2>Productos Oficiales</h2>
    <p class="subtitulo"></p>

    <div class="rejilla-productos">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="tarjeta-producto tarjeta-producto-merch">
                <div class="media-producto">
                    <?php if($product->image_url): ?>
                        <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" />
                    <?php else: ?>
                        <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="<?php echo e($product->name); ?>" />
                    <?php endif; ?>
                </div>
                <div class="info-producto">
                    <h3><?php echo e($product->name); ?></h3>
                    <p class="descripcion-producto"><?php echo e($product->description ?: 'Producto oficial del Bellreguard Club de Basket.'); ?></p>
                    <div class="precio-comprar">
                        <span class="precio"><?php echo e(number_format((float) $product->price, 2, ',', '.')); ?>€</span>
                        <a href="<?php echo e(route('basket.merchandising.buy', $product)); ?>" class="boton-comprar">Comprar</a>
                    </div>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="tarjeta-producto tarjeta-producto-vacia" style="grid-column: 1 / -1;">
                <div class="info-producto" style="text-align: center;">
                    <h3>Proximamente</h3>
                    <p class="descripcion-producto">Todavia no hay productos publicados. Puedes crearlos desde el panel de administracion y apareceran aqui automaticamente.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="contacto-pedido">
    <h2>¿Te gusta algun producto?</h2>
    <p>Haz clic en Comprar para enviar tu solicitud y el club te respondera por email.</p>
    <?php if($products->isNotEmpty()): ?>
        <a href="<?php echo e(route('basket.merchandising.buy', $products->first())); ?>" class="boton-contacto">
            <i class="fa-solid fa-envelope"></i> Solicitar un producto
        </a>
    <?php else: ?>
        <a href="<?php echo e(route('basket.contacto')); ?>" class="boton-contacto">
            <i class="fa-solid fa-envelope"></i> Contactar con el club
        </a>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/basket/merchandising.blade.php ENDPATH**/ ?>