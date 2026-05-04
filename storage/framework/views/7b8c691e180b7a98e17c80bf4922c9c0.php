<h2>Nueva solicitud de compra</h2>

<p><strong>Producto:</strong> <?php echo e($product->name); ?></p>
<p><strong>Precio:</strong> <?php echo e(number_format((float) $product->price, 2, ',', '.')); ?> EUR</p>
<p><strong>Nombre:</strong> <?php echo e($customerData['name']); ?></p>
<p><strong>Email:</strong> <?php echo e($customerData['email']); ?></p>
<p><strong>Mensaje:</strong> <?php echo e($customerData['message'] ?? 'Sin mensaje adicional.'); ?></p>
<?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/emails/product-purchase-inquiry.blade.php ENDPATH**/ ?>