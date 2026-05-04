<?php $__env->startSection('title', 'Inicio - Bellreguard Club de Basket'); ?>

<?php $__env->startSection('contenido'); ?>
<section class="contenedor-login">
    <div class="caja-login">
        
        
        <div class="login-formulario">
            
            
            <div class="login-header">
                <img src="<?php echo e(asset('img/logo.png')); ?>" alt="Logo Bellreguard CB" class="logo-login">
                <h1>Bienvenido de nuevo</h1>
                <p>Introduce tus credenciales para acceder al panel</p>
            </div>

            
            
            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?> 

                
                <div class="grupo-input">
                    <label for="email">Email del Administrador</label>
                    <div class="input-icono">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="admin@bellreguard.com" value="<?php echo e(old('email')); ?>" required autofocus>
                    </div>
                    
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color: #e63000; font-size: 0.8rem; margin-top: 5px; display: block;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="grupo-input">
                    <label for="password">Contraseña</label>
                    <div class="input-icono">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                
                <button type="submit" class="btn-acceder">
                    Iniciar Sesión <i class="fas fa-sign-in-alt"></i>
                </button>
            </form>

            
            <div class="login-footer">
                <p>Si no tienes cuenta, contacta con el administrador del club.</p>
            </div>
        </div>

        
        <div class="login-decoracion">
            <div class="decoracion-contenido">
                <h2>Bellreguard CB</h2>
                <p>Gestión interna y panel de control</p>
            </div>
        </div>

    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/auth/login.blade.php ENDPATH**/ ?>