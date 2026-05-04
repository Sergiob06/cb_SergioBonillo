<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $__env->yieldContent('title', 'Bellreguard Club de Basket'); ?></title>
    
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php echo $__env->make('partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main>
        <?php echo $__env->yieldContent('contenido'); ?>
    </main>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuButton = document.querySelector('#botonMenu');
            const mobileMenu = document.querySelector('#menuNavegacion');
            const overlay = document.querySelector('#overlayMenu');
            const mobileLinks = document.querySelectorAll('#menuNavegacion a');
            const mobileMediaQuery = window.matchMedia('(max-width: 768px)');

            if (!menuButton || !mobileMenu || !overlay) {
                console.error('Menu movil no inicializado: faltan elementos del navbar.', {
                    menuButton,
                    mobileMenu,
                    overlay,
                });
                return;
            }

            const isMobileView = () => mobileMediaQuery.matches;
            const isMenuOpen = () => mobileMenu.classList.contains('is-open');

            const closeMenu = () => {
                mobileMenu.classList.remove('is-open');
                overlay.classList.remove('is-open');
                document.body.classList.remove('menu-abierto');
                menuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
                overlay.setAttribute('aria-hidden', 'true');
                overlay.hidden = true;
            };

            const openMenu = () => {
                if (!isMobileView()) {
                    return;
                }

                overlay.hidden = false;
                mobileMenu.classList.add('is-open');
                overlay.classList.add('is-open');
                document.body.classList.add('menu-abierto');
                menuButton.setAttribute('aria-expanded', 'true');
                mobileMenu.setAttribute('aria-hidden', 'false');
                overlay.setAttribute('aria-hidden', 'false');
            };

            const toggleMenu = () => {
                if (isMenuOpen()) {
                    closeMenu();
                    return;
                }

                openMenu();
            };

            menuButton.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', closeMenu);

            mobileLinks.forEach((link) => {
                link.addEventListener('click', () => {
                    if (isMobileView()) {
                        closeMenu();
                    }
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && isMenuOpen()) {
                    closeMenu();
                }
            });

            const handleViewportChange = (event) => {
                if (!event.matches) {
                    closeMenu();
                }
            };

            if (typeof mobileMediaQuery.addEventListener === 'function') {
                mobileMediaQuery.addEventListener('change', handleViewportChange);
            } else {
                mobileMediaQuery.addListener(handleViewportChange);
            }
        });
    </script>
</body>
</html>
<?php /**PATH /home/sergio/proyecto2DAW_SergioBonillo_PauVila/BellreguardBasket/resources/views/layouts/app.blade.php ENDPATH**/ ?>