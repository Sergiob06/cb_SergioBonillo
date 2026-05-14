<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Bellreguard CB</title>
   <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-panel">
    <div class="contenedor-admin">
        <button type="button" class="admin-overlay" data-admin-nav-close hidden aria-hidden="true" aria-label="Cerrar menu lateral"></button>

        <aside class="sidebar-admin" id="adminSidebar" aria-label="Navegacion del panel de administracion" tabindex="-1">
            <div class="admin-sidebar-header">
                <div class="admin-perfil">
                    <img src="{{ asset('img/basket.jpeg') }}" alt="Logo Admin">
                    <div class="admin-info">
                        <h4>Panel Admin</h4>
                        <span>Bellreguard CB</span>
                    </div>
                </div>

                <button type="button" class="admin-sidebar-close" data-admin-nav-close aria-label="Cerrar navegacion">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <nav class="menu-admin">
                <a href="{{ route('dashboard') }}" class="item-admin {{ request()->routeIs('dashboard') ? 'activo' : '' }}">
                    <i class="fas fa-home"></i> Inicio
                </a>

                <a href="{{ route('jugadores.index') }}" class="item-admin {{ request()->is('admin/jugadores*') ? 'activo' : '' }}">
                    <i class="fas fa-users"></i> Jugadores
                </a>

                <a href="{{ route('equipos.index') }}" class="item-admin {{ request()->is('admin/equipos*') ? 'activo' : '' }}">
                    <i class="fas fa-tshirt"></i> Equipos
                </a>

                <a href="{{ route('partidos.index') }}" class="item-admin {{ request()->is('admin/partidos*') ? 'activo' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Partidos
                </a>

                <a href="{{ route('estadisticas.index') }}" class="item-admin {{ request()->is('admin/estadisticas*') ? 'activo' : '' }}">
                    <i class="fas fa-chart-line"></i> Estadísticas
                </a>

                <a href="{{ route('clasificaciones.index') }}" class="item-admin {{ request()->is('admin/clasificaciones*') ? 'activo' : '' }}">
                    <i class="fas fa-trophy"></i> Clasificación
                </a>

                <a href="{{ route('galerias.index') }}" class="item-admin {{ request()->is('admin/galerias*') ? 'activo' : '' }}">
                    <i class="fas fa-camera"></i> Galería
                </a>

                <a href="{{ route('productos.index') }}" class="item-admin {{ request()->is('admin/productos*') ? 'activo' : '' }}">
                    <i class="fas fa-bag-shopping"></i> Productos
                </a>

                <div class="separador-admin"></div>
    
                <a href="{{ url('/') }}" class="item-admin volver-web">
                    <i class="fas fa-arrow-left"></i> Ver Web
                </a>
            </nav>
        </aside>

        <main class="contenido-admin">
            <header class="admin-mobile-bar">
                <button type="button" class="admin-sidebar-toggle" data-admin-nav-toggle aria-controls="adminSidebar" aria-expanded="false">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                    <span>Menu</span>
                </button>

                <a href="{{ route('dashboard') }}" class="admin-mobile-brand" aria-label="Ir al dashboard del admin">
                    <img src="{{ asset('img/basket.jpeg') }}" alt="">
                    <span>Panel Admin</span>
                </a>
            </header>

            <div class="admin-page">
                @yield('contenido_admin')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const sidebar = document.getElementById('adminSidebar');
            const content = document.querySelector('.contenido-admin');
            const overlay = document.querySelector('.admin-overlay');
            const toggleButtons = document.querySelectorAll('[data-admin-nav-toggle]');
            const closeButtons = document.querySelectorAll('[data-admin-nav-close]');
            const navigationLinks = document.querySelectorAll('.menu-admin a');
            const mobileBreakpoint = window.matchMedia('(max-width: 1024px)');
            const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';
            let lastTrigger = null;

            if (!sidebar || !overlay || !toggleButtons.length) {
                return;
            }

            const getFocusableElements = () => Array.from(sidebar.querySelectorAll(focusableSelector));

            const syncExpandedState = (isOpen) => {
                toggleButtons.forEach((button) => {
                    button.setAttribute('aria-expanded', String(isOpen));
                });
            };

            const setSidebarState = (isOpen) => {
                const shouldOpen = mobileBreakpoint.matches && isOpen;

                body.classList.toggle('admin-nav-open', shouldOpen);
                overlay.hidden = !shouldOpen;
                overlay.setAttribute('aria-hidden', String(!shouldOpen));
                syncExpandedState(shouldOpen);

                if (mobileBreakpoint.matches) {
                    sidebar.setAttribute('aria-hidden', String(!shouldOpen));
                    sidebar.toggleAttribute('inert', !shouldOpen);
                } else {
                    sidebar.removeAttribute('aria-hidden');
                    sidebar.removeAttribute('inert');
                }

                if (content) {
                    content.toggleAttribute('inert', shouldOpen);
                }

                if (shouldOpen) {
                    const firstFocusable = getFocusableElements()[0];
                    window.requestAnimationFrame(() => {
                        (firstFocusable || sidebar).focus();
                    });
                } else if (lastTrigger && typeof lastTrigger.focus === 'function') {
                    lastTrigger.focus();
                }
            };

            toggleButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    lastTrigger = button;
                    setSidebarState(!body.classList.contains('admin-nav-open'));
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    setSidebarState(false);
                });
            });

            navigationLinks.forEach((link) => {
                link.addEventListener('click', function () {
                    if (mobileBreakpoint.matches) {
                        setSidebarState(false);
                    }
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Tab' && body.classList.contains('admin-nav-open') && mobileBreakpoint.matches) {
                    const focusableElements = getFocusableElements();

                    if (!focusableElements.length) {
                        return;
                    }

                    const firstFocusable = focusableElements[0];
                    const lastFocusable = focusableElements[focusableElements.length - 1];

                    if (event.shiftKey && document.activeElement === firstFocusable) {
                        event.preventDefault();
                        lastFocusable.focus();
                    } else if (!event.shiftKey && document.activeElement === lastFocusable) {
                        event.preventDefault();
                        firstFocusable.focus();
                    }
                }

                if (event.key === 'Escape') {
                    setSidebarState(false);
                }
            });

            if (typeof mobileBreakpoint.addEventListener === 'function') {
                mobileBreakpoint.addEventListener('change', function (event) {
                    setSidebarState(false);

                    if (!event.matches) {
                        syncExpandedState(false);
                    }
                });
            } else if (typeof mobileBreakpoint.addListener === 'function') {
                mobileBreakpoint.addListener(function (event) {
                    setSidebarState(false);

                    if (!event.matches) {
                        syncExpandedState(false);
                    }
                });
            }

            window.addEventListener('orientationchange', function () {
                setSidebarState(false);
            });

            setSidebarState(false);
        });
    </script>
</body>
</html>
