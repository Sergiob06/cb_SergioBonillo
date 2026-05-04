<div class="grid-formulario-fichas">
    <div class="seccion-form-ficha seccion-deportiva">
        <h3><i class="fas fa-bag-shopping"></i> Datos del Producto</h3>

        <div class="campo-ficha">
            <label>Nombre</label>
            <input type="text" name="name" value="<?php echo e(old('name', $product?->name)); ?>" required class="input-ficha">
        </div>

        <div class="campo-ficha">
            <label>Precio</label>
            <input type="number" name="price" value="<?php echo e(old('price', $product?->price)); ?>" step="0.01" min="0" required class="input-ficha">
        </div>
    </div>

    <div class="seccion-form-ficha seccion-personal">
        <h3><i class="fas fa-align-left"></i> Descripcion</h3>

        <div class="campo-ficha">
            <label>Descripcion del producto</label>
            <textarea name="description" class="input-ficha" style="min-height: 180px;"><?php echo e(old('description', $product?->description)); ?></textarea>
        </div>
    </div>

    <div class="seccion-foto-ficha">
        <div style="text-align: center;">
            <label>Imagen actual</label>
            <div class="preview-foto">
                <?php if($product?->image_url): ?>
                    <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>">
                <?php else: ?>
                    <img src="<?php echo e(asset('img/basket.jpeg')); ?>" alt="Imagen por defecto">
                <?php endif; ?>
            </div>
        </div>

        <div style="flex: 1;">
            <label><?php echo e($product ? 'Subir nueva imagen' : 'Subir imagen'); ?></label>
            <input type="file" name="image" class="input-ficha" style="background: white;">
            <p style="margin: 10px 0 0; font-size: 0.8em; color: #718096;">Formatos permitidos: JPG, PNG o WEBP.</p>
        </div>
    </div>
</div>
<?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/admin/productos/partials/form.blade.php ENDPATH**/ ?>