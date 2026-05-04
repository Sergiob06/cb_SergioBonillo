<?php $__env->startSection('title', 'Comprar ' . $product->name . ' - Bellreguard Club de Basket'); ?>

<?php $__env->startSection('contenido'); ?>
<section class="seccion-merchandising-header">
    <h1>Comprar Producto</h1>
    <p>Envianos tu solicitud y el club se pondra en contacto contigo</p>
    <div class="iconos-merchandising">
        <i class="fa-solid fa-shirt"></i>
        <i class="fa-solid fa-envelope"></i>
        <i class="fa-solid fa-basketball"></i>
    </div>
</section>

<section class="contacto-pedido">
    <h2><?php echo e($product->name); ?></h2>
    <p><?php echo e($product->description ?: 'Producto oficial del Bellreguard Club de Basket.'); ?></p>

    <?php if(session('mensaje')): ?>
        <div style="max-width: 720px; margin: 20px auto; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px;">
            <i class="fas fa-check-circle"></i> <?php echo e(session('mensaje')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div style="max-width: 720px; margin: 20px auto; background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; text-align: left;">
            <strong>Revisa el formulario:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="compra-grid">
        <article class="tarjeta-producto tarjeta-producto-compra">
            <div class="media-producto media-producto-compra">
                <?php if($product->image_url): ?>
                    <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" />
                <?php else: ?>
                    <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="<?php echo e($product->name); ?>" />
                <?php endif; ?>
            </div>
            <div class="info-producto">
                <h3><?php echo e($product->name); ?></h3>
                <p class="descripcion-producto"><?php echo e($product->description ?: 'Producto oficial del club.'); ?></p>
                <div class="precio-comprar">
                    <span class="precio"><?php echo e(number_format((float) $product->price, 2, ',', '.')); ?> EUR</span>
                </div>
            </div>
        </article>

        <div class="panel-formulario-compra">
            <form action="<?php echo e(route('basket.merchandising.buy.store', $product)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="grid-formulario compra-formulario-grid">
                    <div class="campo">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required>
                    </div>

                    <div class="campo">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required>
                    </div>

                    <div class="campo">
                        <label for="message">Mensaje opcional</label>
                        <textarea id="message" name="message" rows="6"><?php echo e(old('message')); ?></textarea>
                    </div>
                </div>

                <div class="form-acciones compra-formulario-acciones">
                    <button type="submit" class="boton-contacto boton-contacto-compra" style="border: 0; cursor: pointer;">
                        <i class="fa-solid fa-paper-plane"></i> Enviar solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/basket/purchase.blade.php ENDPATH**/ ?>