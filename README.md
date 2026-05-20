# Servicio Linea Once Starter Kit

`servicioslineaonce/starter-kit` es un paquete instalable para Laravel 13 que genera una base de aplicacion lista para autenticacion, panel inicial, internacionalizacion, frontend moderno y multiples presets de UI.

Version actual: `1.0.0`.

El paquete registra el comando:

```bash
php artisan kitlauncher:install
```

Su objetivo es evitar repetir el mismo scaffolding en cada proyecto: rutas, controladores, requests, vistas, componentes, configuracion frontend, dependencias Composer/NPM, autenticacion web, verificacion de email y 2FA TOTP.

## Tabla de Contenido

- [Que instala](#que-instala)
- [Stacks y presets disponibles](#stacks-y-presets-disponibles)
- [Requisitos](#requisitos)
- [Instalacion](#instalacion)
- [Uso del comando](#uso-del-comando)
- [Autenticacion incluida](#autenticacion-incluida)
- [Idiomas e internacionalizacion](#idiomas-e-internacionalizacion)
- [Archivos que modifica](#archivos-que-modifica)
- [Comandos posteriores](#comandos-posteriores)
- [Agregar un nuevo preset UI](#agregar-un-nuevo-preset-ui)
- [Troubleshooting](#troubleshooting)
- [Desarrollo del paquete](#desarrollo-del-paquete)
- [Licencia](#licencia)

## Que instala

El paquete instala una base completa para iniciar una aplicacion Laravel con experiencia de usuario ya armada:

- Comando interactivo bilingue en consola.
- Seleccion de stack entre Inertia y Livewire.
- Seleccion de UI compatible con el stack elegido.
- Configuracion de Composer segun el preset.
- Configuracion de `package.json` segun el preset.
- Configuracion de Vite y Tailwind CSS 4.
- Rutas web iniciales.
- Layouts, paginas, componentes y estilos frontend.
- Autenticacion web opcional.
- Registro de usuarios opcional.
- Login/logout.
- Recuperacion de password.
- Confirmacion de password.
- Verificacion de email.
- Perfil de usuario.
- Actualizacion de password.
- Cierre de otras sesiones.
- Eliminacion de cuenta.
- Autenticacion de dos factores TOTP.
- Codigos de recuperacion 2FA.
- Middleware de locale `SetLocale`.
- Traducciones base en `lang/es/kit.php` y `lang/en/kit.php`.
- Tests feature para el flujo de auth.

## Stacks y Presets Disponibles

El comando organiza los presets en dos stacks.

| Stack | Preset UI | Frontend | Backend |
| --- | --- | --- | --- |
| Inertia | `primevue` | Vue 3, PrimeVue, Ziggy, Tailwind CSS 4 | Laravel controllers + Inertia |
| Inertia | `naive` | Vue 3, Naive UI, Ziggy, Tailwind CSS 4 | Laravel controllers + Inertia |
| Inertia | `react` | React, MUI, componentes UI propios, Ziggy, Tailwind CSS 4 | Laravel controllers + Inertia |
| Livewire | `wireui` | Blade, Livewire, WireUI, Tailwind CSS 4 | Laravel controllers + Livewire |

### Preset `primevue`

Instala una base Inertia + Vue con PrimeVue:

- `@inertiajs/vue3`
- `primevue`
- `@primeuix/themes`
- `primeicons`
- `vue`
- `ziggy-js`
- `@vitejs/plugin-vue`
- `@inertiajs/vite`

Incluye un preset visual en `resources/js/presets` y estilos adicionales en `resources/css/theme-effects.css`.

### Preset `naive`

Instala una base Inertia + Vue con Naive UI:

- `@inertiajs/vue3`
- `naive-ui`
- `@lucide/vue`
- `vfonts`
- `vue`
- `ziggy-js`
- `@vitejs/plugin-vue`
- `@inertiajs/vite`

Cuando se usa con `--force`, limpia archivos visuales propios de PrimeVue que ya no aplican.

### Preset `react`

Instala una base Inertia + React con MUI y componentes propios:

- `@inertiajs/react`
- `react`
- `react-dom`
- `@mui/material`
- `@mui/icons-material`
- `@emotion/react`
- `@emotion/styled`
- `lucide-react`
- `class-variance-authority`
- `clsx`
- `tailwind-merge`
- `@vitejs/plugin-react`
- `@inertiajs/vite`

Incluye componentes UI base como `button`, `input`, `card`, `badge`, `alert`, `checkbox`, `label`, `textarea`.

### Preset `wireui`

Instala una base Livewire + WireUI:

- `livewire/livewire`
- `wireui/wireui`
- `@tailwindcss/forms`

Este preset no usa Inertia ni Ziggy. Al instalarlo con `--force`, el comando limpia middleware y archivos frontend propios de Inertia para evitar estados mixtos.

## Requisitos

- PHP `^8.3`
- Laravel `^13.0`
- Composer
- Node.js y NPM
- Base de datos configurada para correr migraciones

Para el proyecto Docker de este repositorio, los comandos se ejecutan dentro de los servicios:

```bash
docker compose exec app php artisan kitlauncher:install
docker compose exec app composer install
docker compose exec vite npm install
docker compose exec vite npm run build
```

## Instalacion

### Como paquete local por path repository

En este repositorio el paquete se usa como dependencia local:

```json
{
    "repositories": [
        {
            "name": "servicioslineaonce",
            "type": "path",
            "url": "packages/kitlauncher/starter-kit"
        }
    ],
    "require-dev": {
        "servicioslineaonce/starter-kit": "^1.0"
    }
}
```

Luego:

```bash
composer install
php artisan package:discover
```

### Como paquete Composer

Si el paquete se publica en un repositorio Composer o Packagist:

```bash
composer require servicioslineaonce/starter-kit --dev
```

Laravel descubre automaticamente el provider:

```php
ServiciosLineaOnce\StarterKit\StarterKitServiceProvider::class
```

## Uso del Comando

### Modo interactivo

Ejecuta:

```bash
php artisan kitlauncher:install
```

El wizard pregunta:

1. Idioma del instalador: `English` o `Español`.
2. Stack: `Inertia` o `Livewire`.
3. UI compatible con el stack elegido.
4. Si instala autenticacion.
5. Si sobrescribe archivos existentes.
6. Si ejecuta comandos posteriores.

La primera pregunta es bilingue porque el idioma aun no ha sido elegido:

```text
Choose installer language / Elige el idioma del instalador
```

Despues de elegir idioma, todas las preguntas siguientes se muestran en el idioma seleccionado.

### Modo con flags

Para automatizacion, CI o Docker, puedes pasar opciones:

```bash
php artisan kitlauncher:install --lang=es --ui=wireui --auth --force
php artisan kitlauncher:install --lang=en --ui=primevue --auth --force
php artisan kitlauncher:install --ui=react --auth
```

### Opciones disponibles

| Opcion | Descripcion |
| --- | --- |
| `--lang=en\|es` | Define idioma de los mensajes del comando. Si no se pasa en modo interactivo, pregunta primero. |
| `--ui=primevue` | Instala preset Inertia + Vue + PrimeVue. |
| `--ui=naive` | Instala preset Inertia + Vue + Naive UI. |
| `--ui=react` | Instala preset Inertia + React + MUI. |
| `--ui=wireui` | Instala preset Livewire + WireUI. |
| `--auth` | Copia scaffolding de autenticacion, perfil, password, email verification y 2FA. |
| `--force` | Sobrescribe archivos existentes y limpia archivos obsoletos del preset anterior. |
| `--no-interaction` | No hace preguntas. Si no pasas `--ui`, usa `primevue`; si no pasas `--auth`, no instala auth. |

## Autenticacion Incluida

Con `--auth`, el paquete instala un flujo completo sin Fortify:

- Registro de usuarios.
- Login y logout.
- Remember me.
- Recuperacion de password por email.
- Reset de password por token.
- Verificacion de email.
- Confirmacion de password para acciones sensibles.
- Perfil de usuario.
- Cambio de password.
- Cierre de otras sesiones.
- Eliminacion de cuenta.
- Two-factor authentication TOTP.
- QR para app autenticadora.
- Confirmacion de codigo TOTP.
- Codigos de recuperacion.
- Regeneracion de codigos.
- Challenge 2FA antes de completar login.

La configuracion principal queda en:

```php
config/servicios-linea-once.php
```

Variables relevantes:

```env
SLO_AUTH_REGISTRATION=true
SLO_AUTH_TWO_FACTOR=true
```

El paquete agrega columnas 2FA a `users` con una migracion:

```text
database/migrations/2026_01_01_000100_add_two_factor_columns_to_users_table.php
```

## Idiomas e Internacionalizacion

El paquete maneja idioma en dos niveles:

### Idioma del comando

El instalador puede mostrar preguntas en ingles o espanol:

```bash
php artisan kitlauncher:install --lang=es
php artisan kitlauncher:install --lang=en
```

Si no se pasa `--lang`, el wizard pregunta el idioma antes de cualquier otra pregunta.

### Idioma de la aplicacion instalada

El scaffolding copia:

```text
app/Http/Middleware/SetLocale.php
lang/es/kit.php
lang/en/kit.php
```

`SetLocale` lee `locale` desde la sesion y aplica `App::setLocale()` si el valor es `es` o `en`.

Los presets Inertia incluyen tambien un helper frontend con persistencia en `localStorage`:

```text
resources/js/lib/i18n.js
resources/js/lib/i18n.jsx
```

## Archivos Que Modifica

El comando puede crear o sobrescribir archivos en estas areas:

```text
app/Http/Controllers/Auth
app/Http/Controllers/Settings
app/Http/Middleware
app/Http/Requests/Auth
app/Http/Requests/Settings
app/Livewire
app/Models/User.php
app/Support/Auth
bootstrap/app.php
config/servicios-linea-once.php
database/migrations
lang
package.json
composer.json
resources/css
resources/js
resources/views
routes/auth.php
routes/web.php
tests/Feature/Auth
vite.config.js
```

Sin `--force`, el comando omite archivos y directorios existentes para no pisar trabajo del proyecto.

Con `--force`, sobrescribe stubs y limpia archivos obsoletos del preset anterior.

## Comandos Posteriores

Al terminar, el comando recomienda:

```bash
composer install
npm install
php artisan migrate
npm run build
```

En modo interactivo tambien puede ejecutarlos si el usuario confirma.

En Docker, ejecuta Composer dentro de `app` y NPM dentro de `vite`:

```bash
docker compose exec app composer install
docker compose exec vite npm install
docker compose exec app php artisan migrate
docker compose exec vite npm run build
```

## Agregar un Nuevo Preset UI

Los presets viven en `InstallCommand::UI_PRESETS`.

Para agregar uno nuevo:

1. Crear una carpeta `stubs/presets/{nombre}/install` con el frontend del preset (CSS, `app.{js,jsx}`, `vite.config.js`, `Components`, `Layouts`, `Pages`, etc.). Si el preset cambia rutas, controladores o vistas backend, crear también `stubs/presets/{nombre}/auth` con esos overrides.
2. Agregar entrada en `UI_PRESETS`.
3. Definir:
   - `label`
   - `stack`
   - `extension`
   - `inertia`
   - `composer`
   - `dependencies`
   - `devDependencies`
4. Agregar dependencias propias del preset.
5. Agregar rutas/vistas/componentes necesarias.
6. Probar instalacion con:

```bash
php artisan kitlauncher:install --lang=es --ui={nombre} --auth --force
composer install
npm install
php artisan migrate
npm run build
php artisan test
```

## Troubleshooting

### `Class "Livewire\LivewireServiceProvider" not found`

El proyecto probablemente tiene `composer.json` actualizado, pero `vendor` no esta sincronizado.

Solucion local:

```bash
composer install
php artisan optimize:clear
```

Solucion Docker:

```bash
docker compose exec app composer install
docker compose exec app php artisan optimize:clear
```

### `Class "Inertia\Middleware" not found`

Ocurre cuando la app quedo con middleware Inertia registrado pero el preset actual es Livewire/WireUI.

Solucion:

```bash
php artisan kitlauncher:install --ui=wireui --auth --force --no-interaction
composer install
php artisan optimize:clear
```

### Tailwind no encuentra `@tailwindcss/forms`

En WireUI, NPM debe estar sincronizado:

```bash
npm install
npm run build
```

En Docker:

```bash
docker compose exec vite npm install
docker compose exec vite npm run build
```

### Cambie de preset y quedan dependencias viejas

Usa `--force` para que el instalador limpie archivos obsoletos y actualice `composer.json` / `package.json`:

```bash
php artisan kitlauncher:install --ui=react --auth --force
composer install
npm install
```

## Desarrollo del Paquete

Estructura principal:

```text
src/
  StarterKitServiceProvider.php
  Console/InstallCommand.php
stubs/
  base/
    install/
    auth/
    middleware/
  presets/
    primevue/
      install/
      auth/
    naive/
      install/
      auth/
    react/
      install/
      auth/
    wireui/
      install/
      auth/
composer.json
LICENSE
```

`StarterKitServiceProvider` registra el comando cuando Laravel corre en consola.

`InstallCommand` contiene:

- Definicion de presets UI.
- Wizard bilingue.
- Actualizacion de Composer.
- Actualizacion de NPM.
- Copia de stubs.
- Registro de middleware.
- Limpieza de archivos obsoletos.
- Ejecucion opcional de comandos posteriores.

Validaciones recomendadas:

```bash
php -l src/Console/InstallCommand.php
composer validate --no-check-publish
php artisan test
npm run build
```

## Licencia

MIT. Ver `LICENSE`.
