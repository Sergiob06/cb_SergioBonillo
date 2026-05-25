# Revision de requisitos minimos

## Requisitos que ya cumplia el proyecto

- Inicio publico con presentacion del club, proximos partidos y contenido destacado de galeria.
- Equipos y jugadores con informacion basica visible para invitados.
- Partidos con equipo local y equipo visitante.
- Estadisticas publicas por partido y resumen/analisis por equipo local.
- Admin con login para gestionar equipos, jugadores, partidos, estadisticas, galeria y productos.
- Invitado puede consultar la parte publica sin login.
- Navegacion publica y admin clara.
- Diseno responsive con estilos existentes.
- Logo del club en cabecera, footer y panel admin.
- Enlace a redes sociales en el footer.
- Colores principales del club presentes en la interfaz: rojo, negro/blanco y tonos de apoyo.
- Base de datos coherente con usuarios, equipos, jugadores, categorias, posiciones, partidos, galeria, productos y estadisticas.

## Que faltaba

- No habia roles de usuario: cualquier usuario autenticado podia entrar a todo el panel.
- No existia un usuario entrenador/ayudante de ejemplo.
- El requisito "Entrenador o ayudante puede introducir estadisticas" estaba solo parcialmente cubierto porque dependia de usar el usuario admin.

## Cambios realizados

- Se ha anadido el campo `rol` a `users`.
- Se ha creado un middleware simple `role` sin paquetes externos.
- Se ha creado un usuario entrenador de ejemplo en seeders.
- Se han separado las rutas del panel:
  - Admin conserva la gestion completa.
  - Entrenador puede consultar equipos/jugadores/partidos y crear/editar solo estadisticas.
  - Entrenador no puede gestionar productos, galeria, equipos, jugadores ni datos generales de partidos.
- Se ha ajustado el menu admin para ocultar productos/galeria al entrenador.
- Se han ocultado botones de crear/editar/eliminar equipos y jugadores cuando entra un entrenador.
- Se han ocultado acciones de crear/editar/eliminar partidos y eliminar estadisticas al entrenador.
- Se ha anadido un formulario separado para editar solo estadisticas de un partido jugado.
- Se bloquea la introduccion de estadisticas en partidos proximos/no jugados con el mensaje: "Las estadisticas solo pueden anadirse cuando el partido ya ha sido jugado."
- Se sincroniza automaticamente el resultado final del partido desde los `puntos_anotados` de las estadisticas del local y del visitante.
- Se ha retirado del codigo la seccion CRUD de entrenadores porque el requisito pide rol de usuario entrenador, no fichas personales de entrenadores.
- Se ha anadido la tabla `estadisticas_equipos` para guardar estadisticas de los dos equipos de cada partido.
- El formulario de crear/editar partido permite introducir un bloque de estadisticas para el local y otro para el visitante.
- El detalle publico y admin del partido muestra estadisticas de ambos equipos.
- Los analisis acumulados de equipos usan solo registros de `estadisticas_equipos` cuyo equipo esta marcado como `es_local = true`.
- Los seeders generan partidos jugados con estadisticas para ambos equipos y partidos proximos sin estadisticas.

## Estadisticas de ambos equipos

- Implementado: si el partido esta jugado, puede tener dos filas en `estadisticas_equipos`, una para el equipo local y otra para el visitante.
- Tabla usada: `estadisticas_equipos`.
- Campos principales: `partido_id`, `equipo_id`, `es_local`, `puntos_anotados`, `t2_intentados`, `t3_intentados`, `tl_intentados`, `balones_perdidos`, `rebotes_ofensivos`, `tiros_anotados`, `rebotes_defensivos`, `asistencias`, `robos`, `tapones` y `faltas`.
- Se mantiene la estructura antigua en `partidos` y `estadisticas` por compatibilidad, pero el dato correcto por equipo/partido esta en `estadisticas_equipos`.

## Sincronizacion resultado - estadisticas

- La fuente principal para el marcador final son los campos `estadisticas_equipos.puntos_anotados`.
- Al guardar estadisticas de partido, se actualizan automaticamente:
  - `partidos.puntos_local` con `puntos_anotados` del registro marcado como local.
  - `partidos.puntos_visitante` con `puntos_anotados` del registro marcado como visitante.
- Si el entrenador modifica los puntos anotados de cualquiera de los dos equipos, el resultado mostrado en listado/detalle se corrige al guardar.
- El formulario especifico de estadisticas exige `puntos_anotados` para local y visitante, numericos y no negativos.
- Si el partido no tiene `estado = jugado`, no se permite guardar estadisticas ni actualizar el resultado.
- La sincronizacion solo toca el marcador (`puntos_local` y `puntos_visitante`); no abre permisos para modificar equipo local, visitante, fecha, lugar, categoria, temporada o estado.

## Eficiencias

- Eficiencia ofensiva: `puntos_anotados / (t2_intentados + t3_intentados + (tl_intentados / 2) + balones_perdidos - rebotes_ofensivos)`.
- Eficiencia defensiva: eficiencia ofensiva del rival en el mismo partido.
- No se guardan en base de datos; se calculan desde el modelo `EstadisticaEquipo`.
- Si falta algun dato opcional o el denominador no es valido, se muestra `-` y no rompe la vista.

## Analisis de equipos Bellreguard

- Solo se calculan para equipos con `equipos.es_local = true`.
- Usan exclusivamente filas de `estadisticas_equipos` donde `equipo_id` corresponde al equipo Bellreguard analizado.
- Funcionan si Bellreguard juega como local o visitante.
- Los rivales externos pueden tener estadisticas en el detalle del partido, pero no se incluyen como analisis acumulado propio.

## Usuario entrenador creado

- Email: `entrenador@basketbellreguard.es`
- Password: `password`
- Rol: `entrenador`

## Permisos del entrenador

Puede:

- Ver el dashboard del panel.
- Consultar equipos, jugadores y partidos.
- Consultar estadisticas y analisis de equipos Bellreguard.
- Anadir o editar estadisticas de local y visitante desde `GET /admin/partidos/{partido}/estadisticas`, solo si el partido tiene `estado = jugado`.
- Usar las rutas antiguas de `estadisticas` para consultar/actualizar estadisticas agregadas compatibles con el sistema existente.

No puede:

- Crear partidos.
- Editar datos generales de partidos.
- Eliminar partidos.
- Cambiar equipo local, visitante, fecha, lugar, categoria, temporada, estado o marcador general.
- Crear, editar o eliminar equipos.
- Crear, editar o eliminar jugadores.
- Gestionar galeria, productos, usuarios o configuracion general.

La edicion general de partidos queda protegida por rutas dentro de `role:admin`. Si un entrenador intenta acceder manualmente a `partidos.create`, `partidos.store`, `partidos.edit`, `partidos.update` o `partidos.destroy`, el middleware `role` responde con `403`.

La edicion de estadisticas de partido usa el campo existente `partidos.estado`. Solo se permite cuando el valor es `jugado`; para `proximo` o no jugado se muestra el mensaje de bloqueo y en backend se valida de nuevo antes de guardar.

Cuando el entrenador guarda estadisticas, el controlador valida que existan los dos equipos del partido y que ambos bloques incluyan `puntos_anotados`. Despues de guardar las filas de `estadisticas_equipos`, recalcula `partidos.puntos_local` y `partidos.puntos_visitante` desde esas estadisticas.

## Rutas que puede usar el entrenador

- `GET /admin`
- `GET /admin/equipos`
- `GET /admin/equipos/search`
- `GET /admin/equipos/{equipo}`
- `GET /admin/equipos/{equipo}/analisis`
- `GET /admin/jugadores`
- `GET /admin/jugadores/search`
- `GET /admin/jugadores/{jugador}`
- `GET /admin/partidos`
- `GET /admin/partidos/search`
- `GET /admin/partidos/{partido}`
- `GET /admin/partidos/{partido}/estadisticas`
- `PUT/PATCH /admin/partidos/{partido}/estadisticas`
- `GET /admin/estadisticas`
- `GET /admin/estadisticas/search`
- `GET /admin/estadisticas/create`
- `POST /admin/estadisticas`
- `GET /admin/estadisticas/{estadistica}`
- `GET /admin/estadisticas/{estadistica}/edit`
- `PUT/PATCH /admin/estadisticas/{estadistica}`

## Rutas restringidas solo al admin

- Crear, editar y eliminar equipos.
- Crear, editar y eliminar jugadores.
- Crear, editar y eliminar partidos.
- Eliminar estadisticas.
- Gestionar galeria.
- Gestionar productos y solicitudes de productos.

## Archivos tocados

- `app/Http/Middleware/EnsureUserHasRole.php`
- `app/Http/Controllers/BasketController.php`
- `app/Http/Controllers/EquipoController.php`
- `app/Http/Controllers/EstadisticaController.php`
- `app/Http/Controllers/PartidoController.php`
- `app/Models/Equipo.php`
- `app/Models/EstadisticaEquipo.php`
- `app/Models/Partido.php`
- `app/Models/User.php`
- `bootstrap/app.php`
- `.gitignore`
- `database/migrations/2026_05_25_000002_add_rol_to_users_table.php`
- `database/migrations/2026_05_25_000003_create_estadisticas_equipos_table.php`
- `database/seeders/DatabaseSeeder.php`
- `database/seeders/EstadisticaSeeder.php`
- `database/seeders/PartidoSeeder.php`
- `routes/web.php`
- `public/css/app.css`
- `resources/views/layouts/admin.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/equipos/index.blade.php`
- `resources/views/admin/equipos/show.blade.php`
- `resources/views/admin/equipos/analisis.blade.php`
- `resources/views/admin/estadisticas/show.blade.php`
- `resources/views/admin/jugadores/index.blade.php`
- `resources/views/admin/jugadores/show.blade.php`
- `resources/views/admin/partidos/create.blade.php`
- `resources/views/admin/partidos/edit.blade.php`
- `resources/views/admin/partidos/estadisticas.blade.php`
- `resources/views/admin/partidos/index.blade.php`
- `resources/views/admin/partidos/show.blade.php`
- `resources/views/basket/estadisticas.blade.php`
- `resources/views/basket/partido.blade.php`
- `resources/views/basket/partidos.blade.php`
- `resources/views/equipos/show.blade.php`
- `REVISION_REQUISITOS.md`

## Revision final critica

Comprobaciones ejecutadas:

- `git status --short`
- `git diff --stat`
- `rg` para buscar `dd`, `dump`, `console.log`, conflictos de merge y marcas de debug en `app`, `routes`, `resources`, `database` y `public/css`.
- `./vendor/bin/sail artisan route:list -v`
- `./vendor/bin/sail artisan migrate:status`
- `php -l` sobre controladores/modelos/seeders/rutas tocados.
- `./vendor/bin/sail artisan view:cache` y despues `./vendor/bin/sail artisan view:clear`.
- Verificacion por Tinker de partidos jugados/proximos, estadisticas por equipo, descuadres de marcador y Bellreguard como visitante.
- Prueba en transaccion con rollback de sincronizacion de resultado al modificar `puntos_anotados`.
- Prueba en transaccion con rollback de bloqueo de estadisticas en partido proximo.
- Prueba en transaccion con rollback de creacion admin de partido proximo sin estadisticas.
- `composer dump-autoload`
- `npm run build`
- `git diff --check`

Resultados relevantes:

- Rutas de edicion general de partidos (`partidos.create`, `partidos.store`, `partidos.edit`, `partidos.update`, `partidos.destroy`) quedan bajo `role:admin`.
- Rutas especificas de estadisticas (`partidos.estadisticas.edit`, `partidos.estadisticas.update`) quedan bajo `role:admin,entrenador`.
- `logout` existe como `POST /logout` con middleware `auth`.
- Los partidos jugados tienen dos filas en `estadisticas_equipos`.
- Los partidos proximos no tienen estadisticas.
- No se detectan descuadres entre `partidos.puntos_local/puntos_visitante` y `estadisticas_equipos.puntos_anotados` en los datos actuales.
- Hay partidos donde Bellreguard juega como visitante y sus estadisticas se contabilizan.
- Las vistas Blade compilan.
- `npm run build` funciona, pero genera un asset ignorado en `public/build/assets/app-D2Mk6NLz.css`. Se restauraron los artefactos versionados de `public/build` para evitar ruido en el diff.
- `composer dump-autoload` funciona, pero toca autoloads de `vendor` porque el repo tiene parte de `vendor` versionado. Se restauraron esos autoloads para evitar ruido en el diff.
- `git diff --check` solo falla por espacios finales dentro de `storage/logs/laravel.log`, que es un archivo generado/sospechoso y no deberia entrar en el commit.

Correccion encontrada durante la revision:

- Se detecto que `puntos_anotados` habia quedado obligatorio tambien en la validacion general de crear/editar partido. Eso podia romper partidos proximos. Se corrigio para que:
  - En crear/editar partido completo sea opcional.
  - En editar estadisticas de partido sea obligatorio para local y visitante.

## Riesgos antes del push

- `.env` esta versionado y modificado con valores locales (`APP_ENV=local`, `APP_DEBUG=true`, `DB_HOST=mysql`, usuario `sail`). Esto no deberia subirse a produccion salvo que sea intencionado.
- `storage/logs/laravel.log` esta versionado y contiene errores locales/produccion. No deberia entrar en un commit limpio.
- Hay cambios en `database/seeders/GaleriaSeeder.php` y ficheros de imagen/storage que parecen ajenos a estadisticas/roles. Revisarlos antes de incluirlos.
- `package-lock.json` aparece como no versionado. Es normal committear lockfile si se decide fijar dependencias npm, pero no parece parte directa de esta tarea.
- `public/jugadores/bruno-aleman-trejo-20260522150627-iglof5sf.jpeg` y nuevas imagenes de `storage/app/public/galeria` aparecen no versionadas. Revisar si son contenido real o pruebas locales.
- `public/build` esta ignorado, pero algunos artefactos estaban versionados previamente. Tras cada `npm run build` pueden aparecer cambios en `public/build/manifest.json` y CSS/JS versionados; si no se quieren subir assets compilados, restaurarlos antes del commit.
- `database/database.sqlite` esta versionado en el repo. No se ha tocado, pero conviene confirmar que es intencionado.

## Archivos sospechosos que no deberian subirse sin revisar

- `.env`
- `storage/logs/laravel.log`
- `storage/app/public/galeria/ntoeYQDZLNXsdWRV4RuN0eyY1vv2w54AhnVNkGqh.jpg` marcado como eliminado.
- `storage/app/public/galeria/3A8Sqp5z3oPyBmqbu7JYXT5u5AydadpYyrLAfppZ.jpg`
- `public/jugadores/bruno-aleman-trejo-20260522150627-iglof5sf.jpeg`
- `package-lock.json`
- Cambios de `database/seeders/GaleriaSeeder.php`

Se han anadido a `.gitignore` los patrones `*.sql` y `*.tar.gz` para evitar que se suban backups locales como `backup_produccion.sql`, `bellreguard_local.sql`, `galeria_storage.tar.gz` o `imagenes_bellreguard.tar.gz`.

## Migraciones nuevas creadas

- `database/migrations/2026_05_25_000002_add_rol_to_users_table.php`: anade el campo `rol` a `users`.
- `database/migrations/2026_05_25_000003_create_estadisticas_equipos_table.php`: crea `estadisticas_equipos` y hace backfill seguro desde partidos jugados existentes.

## Posibles puntos de produccion

- Si produccion tiene `.env` versionado, hay que evitar subir los valores locales actuales.
- Si hay partidos jugados antiguos sin `equipo_local_id` o `equipo_visitante_id`, el formulario de estadisticas devolvera error claro y no guardara hasta corregir esos equipos.
- Si se ejecuta `db:seed` en una base con datos reales, los seeders pueden modificar datos de ejemplo. Usarlo solo en entorno local/demo salvo que se quiera regenerar datos.
- El analisis de equipos esta filtrado a `equipos.es_local = true`; si en produccion hay equipos Bellreguard sin ese flag, no apareceran en analisis hasta corregir el dato.

## Revision manual antes de push

- Revisar `git diff` porque el repositorio ya tenia cambios previos sin confirmar en `.env`, `GaleriaSeeder.php`, imagenes, SQL/tar y `storage/logs/laravel.log`.
- Comprobar manualmente login con `admin@bellreguard.com` y con `entrenador@basketbellreguard.es`.
- Verificar que el entrenador recibe 403 si intenta entrar a productos, galeria, crear jugadores o crear equipos.
- Verificar que el entrenador recibe 403 si intenta entrar manualmente a crear o editar datos generales de un partido.
- Probar con admin crear un partido jugado desde el panel e introducir estadisticas del local y del visitante.
- Probar con entrenador editar solo estadisticas de un partido jugado desde el boton de estadisticas.
- Probar con entrenador abrir estadisticas de un partido proximo y comprobar que aparece el mensaje de bloqueo.
- Probar editar un partido y cambiarlo a proximo para confirmar que se eliminan/no muestran estadisticas.
- Revisar un analisis de equipo Bellreguard jugando como local y otro jugando como visitante.

## Comandos recomendados

```bash
php artisan migrate
php artisan db:seed
php artisan optimize:clear
php artisan route:list
npm run build
git status
git diff --stat
git diff
```

Si estas trabajando con Sail/Docker y `.env` mantiene `DB_HOST=mysql`, usa:

```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan optimize:clear
./vendor/bin/sail artisan route:list
npm run build
git status
git diff --stat
git diff
```
