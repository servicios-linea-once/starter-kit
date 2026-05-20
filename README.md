# Servicio Linea Once Starter Kit

Starter kit instalable para Laravel que crea una base moderna con frontend, rutas, layouts, internacionalizacion y autenticacion lista para usar.

El paquete registra el comando:

```bash
php artisan kitlauncher:install
```

Con ese comando puedes elegir entre presets Inertia o Livewire, instalar autenticacion completa y preparar las dependencias Composer/NPM del proyecto.

> Version actual: `1.0.0`
> Paquete Composer: `servicioslineaonce/starter-kit`

## Contenido

- [Descripcion](#descripcion)
- [Caracteristicas](#caracteristicas)
- [Presets disponibles](#presets-disponibles)
- [Requisitos](#requisitos)
- [Instalacion](#instalacion)
- [Uso rapido](#uso-rapido)
- [Opciones del comando](#opciones-del-comando)
- [Que archivos instala](#que-archivos-instala)
- [Autenticacion](#autenticacion)
- [Two-Factor Authentication](#two-factor-authentication)
- [Internacionalizacion](#internacionalizacion)
- [Flujo recomendado despues de instalar](#flujo-recomendado-despues-de-instalar)
- [Cambiar de preset](#cambiar-de-preset)
- [Estructura del paquete](#estructura-del-paquete)
- [Crear un nuevo preset](#crear-un-nuevo-preset)
- [Troubleshooting](#troubleshooting)
- [Desarrollo](#desarrollo)
- [Licencia](#licencia)

## Descripcion

`servicioslineaonce/starter-kit` evita repetir el mismo scaffolding cada vez que se inicia una aplicacion Laravel. El instalador copia archivos base, actualiza `composer.json`, actualiza `package.json`, configura Vite, registra middleware y opcionalmente instala un sistema de autenticacion completo.

Esta pensado para proyectos nuevos o proyectos donde quieres reemplazar el frontend inicial por uno de los presets soportados.

El objetivo es dejar una aplicacion con:

- Rutas web iniciales.
- Pantalla de bienvenida.
- Dashboard inicial.
- Layout principal.
- Cambio de idioma entre espanol e ingles.
- Frontend con Vite y Tailwind CSS 4.
- Presets UI listos para desarrollar.
- Autenticacion opcional.
- Verificacion de email.
- Perfil de usuario.
- Cambio de password.
- Eliminacion de cuenta.
- Cierre de otras sesiones.
- Autenticacion de dos factores con TOTP.
- Tests feature para flujos de auth.

## Caracteristicas

- Comando de instalacion interactivo.
- Instalador bilingue en `en` y `es`.
- Soporte para modo no interactivo.
- Presets separados por stack: Inertia y Livewire.
- Actualizacion automatica de dependencias Composer.
- Actualizacion automatica de dependencias NPM.
- Copia de stubs por capas: base + preset.
- Registro automatico de middleware en `bootstrap/app.php`.
- Limpieza de archivos obsoletos al usar `--force`.
- Auth propia sin depender de Fortify.
- 2FA TOTP con QR y codigos de recuperacion.
- Traducciones base en `lang/es` y `lang/en`.

## Presets disponibles

| UI | Stack | Frontend | Backend |
| --- | --- | --- | --- |
| `primevue` | Inertia | Vue 3, PrimeVue, Ziggy, Tailwind CSS 4 | Controllers + Inertia |
| `naive` | Inertia | Vue 3, Naive UI, Ziggy, Tailwind CSS 4 | Controllers + Inertia |
| `react` | Inertia | React, MUI, componentes UI, Ziggy, Tailwind CSS 4 | Controllers + Inertia |
| `wireui` | Livewire | Blade, Livewire, WireUI, Tailwind CSS 4 | Controllers + Livewire |

### PrimeVue

Preset Inertia + Vue 3 con PrimeVue.

Dependencias principales:

- `@inertiajs/vue3`
- `@inertiajs/vite`
- `@primeuix/themes`
- `primeicons`
- `primevue`
- `vue`
- `ziggy-js`
- `@vitejs/plugin-vue`

Incluye:

- `resources/js/app.js`
- `resources/js/Pages/Welcome.vue`
- `resources/js/Pages/Dashboard.vue`
- `resources/js/Layouts/AppLayout.vue`
- componentes compartidos
- preset visual en `resources/js/presets`
- estilos extra en `resources/css/theme-effects.css`

### Naive UI

Preset Inertia + Vue 3 con Naive UI.

Dependencias principales:

- `@inertiajs/vue3`
- `@inertiajs/vite`
- `@lucide/vue`
- `naive-ui`
- `vfonts`
- `vue`
- `ziggy-js`
- `@vitejs/plugin-vue`

Incluye:

- providers globales de Naive UI
- tema oscuro base
- componentes de formulario
- selector de idioma
- paginas Inertia para welcome, dashboard y auth

### React + MUI

Preset Inertia + React con Material UI y componentes propios.

Dependencias principales:

- `@inertiajs/react`
- `@inertiajs/vite`
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
- `ziggy-js`
- `@vitejs/plugin-react`

Incluye componentes base:

- `Button`
- `Input`
- `Label`
- `Checkbox`
- `Card`
- `Badge`
- `Alert`
- `Textarea`

Tambien incluye helpers en:

```text
resources/js/lib/i18n.jsx
resources/js/lib/muiTheme.js
resources/js/lib/utils.js
```

### WireUI

Preset Livewire + WireUI para aplicaciones Blade.

Dependencias principales:

- `livewire/livewire`
- `wireui/wireui`
- `@tailwindcss/forms`

Incluye:

- layouts Blade
- paginas Blade
- componentes Livewire para auth
- vistas Livewire
- configuracion WireUI
- Vite sin Inertia

Cuando instalas `wireui`, el comando elimina el middleware de Inertia si estaba registrado anteriormente.

## Requisitos

- PHP `^8.3`
- Laravel `^13.0`
- Composer
- Node.js y NPM
- Base de datos configurada
- Proyecto Laravel con `composer.json`, `package.json` y `bootstrap/app.php`

## Instalacion

Instala el paquete desde la raiz de tu proyecto Laravel:

```bash
composer require servicioslineaonce/starter-kit --dev
```

Laravel descubre automaticamente este service provider:

```php
ServiciosLineaOnce\StarterKit\StarterKitServiceProvider::class
```

Despues de instalar el paquete, ejecuta el instalador:

```bash
php artisan kitlauncher:install
```

Tambien puedes instalar un preset directamente:

```bash
php artisan kitlauncher:install --lang=es --ui=primevue --auth --force --no-interaction
```

Al terminar, sincroniza dependencias y compila los assets:

```bash
composer install
npm install
php artisan migrate
npm run build
```

## Uso rapido

### Instalacion interactiva

```bash
php artisan kitlauncher:install
```

El asistente pregunta:

1. Idioma del instalador.
2. Stack: `Inertia` o `Livewire`.
3. UI compatible con el stack.
4. Si quieres instalar autenticacion.
5. Si quieres sobrescribir archivos existentes.
6. Si quieres ejecutar comandos posteriores.

### Instalacion no interactiva

PrimeVue con autenticacion:

```bash
php artisan kitlauncher:install --lang=es --ui=primevue --auth --force --no-interaction
```

Naive UI con autenticacion:

```bash
php artisan kitlauncher:install --lang=es --ui=naive --auth --force --no-interaction
```

React + MUI con autenticacion:

```bash
php artisan kitlauncher:install --lang=es --ui=react --auth --force --no-interaction
```

Livewire + WireUI con autenticacion:

```bash
php artisan kitlauncher:install --lang=es --ui=wireui --auth --force --no-interaction
```

Solo frontend, sin auth:

```bash
php artisan kitlauncher:install --lang=es --ui=primevue --force --no-interaction
```

## Opciones del comando

| Opcion | Descripcion |
| --- | --- |
| `--lang=en` | Muestra los mensajes del instalador en ingles. |
| `--lang=es` | Muestra los mensajes del instalador en espanol. |
| `--ui=primevue` | Instala Inertia + Vue + PrimeVue. |
| `--ui=naive` | Instala Inertia + Vue + Naive UI. |
| `--ui=react` | Instala Inertia + React + MUI. |
| `--ui=wireui` | Instala Livewire + WireUI. |
| `--auth` | Instala rutas, controladores, requests, vistas y tests de autenticacion. |
| `--force` | Sobrescribe archivos existentes y limpia archivos administrados por presets anteriores. |
| `--no-interaction` | Ejecuta el comando sin preguntas. Si no indicas `--ui`, usa `primevue`. |

Ejemplo para CI o scripts:

```bash
php artisan kitlauncher:install --lang=en --ui=react --auth --force --no-interaction
```

## Que archivos instala

El comando puede crear o modificar archivos en estas rutas:

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
composer.json
config/servicios-linea-once.php
database/migrations
lang/en/kit.php
lang/es/kit.php
package.json
resources/css
resources/js
resources/views
routes/auth.php
routes/web.php
tests/Feature/Auth
tests/Feature/ExampleTest.php
vite.config.js
```

Sin `--force`, los archivos existentes se omiten para proteger cambios del proyecto.

Con `--force`, el comando sobrescribe stubs administrados por el starter kit y limpia rutas frontend obsoletas, por ejemplo:

```text
resources/js/Components
resources/js/Layouts
resources/js/Pages
resources/js/lib
resources/js/presets
resources/views/layouts
resources/views/pages
resources/css/theme-effects.css
tests/Feature/ExampleTest.php
```

## Autenticacion

Al usar `--auth`, el starter kit instala un flujo completo de autenticacion web.

Incluye:

- Registro de usuario.
- Login.
- Logout.
- Remember me.
- Recuperacion de password.
- Reset de password por token.
- Verificacion de email.
- Reenvio de email de verificacion.
- Confirmacion de password.
- Dashboard protegido.
- Perfil de usuario.
- Actualizacion de informacion personal.
- Cambio de password.
- Cierre de otras sesiones.
- Eliminacion de cuenta.
- Tests feature para los flujos principales.

Rutas principales:

```text
GET    /login
POST   /login
POST   /logout
GET    /register
POST   /register
GET    /forgot-password
POST   /forgot-password
GET    /reset-password/{token}
POST   /reset-password
GET    /verify-email
GET    /verify-email/{id}/{hash}
POST   /email/verification-notification
GET    /confirm-password
POST   /confirm-password
GET    /dashboard
GET    /settings/profile
PATCH  /settings/profile
DELETE /settings/profile
PUT    /settings/password
DELETE /settings/sessions
```

La configuracion queda en:

```text
config/servicios-linea-once.php
```

Variables disponibles:

```env
SLO_AUTH_REGISTRATION=true
SLO_AUTH_TWO_FACTOR=true
```

## Two-Factor Authentication

El starter kit incluye 2FA basado en TOTP.

Paquetes usados:

- `pragmarx/google2fa`
- `bacon/bacon-qr-code`

Incluye:

- Activacion de 2FA desde perfil.
- Confirmacion con codigo TOTP.
- QR para apps autenticadoras.
- Codigos de recuperacion.
- Regeneracion de codigos.
- Challenge de segundo factor durante login.
- Middleware para completar el challenge antes de acceder.

Rutas principales:

```text
GET    /two-factor-challenge
POST   /two-factor-challenge
DELETE /two-factor-challenge
POST   /settings/two-factor
POST   /settings/two-factor/confirm
DELETE /settings/two-factor
POST   /settings/two-factor/recovery-codes
```

Migracion instalada:

```text
database/migrations/2026_01_01_000100_add_two_factor_columns_to_users_table.php
```

Configuracion base:

```php
return [
    'auth' => [
        'registration' => env('SLO_AUTH_REGISTRATION', true),

        'two_factor' => [
            'enabled' => env('SLO_AUTH_TWO_FACTOR', true),
            'required' => false,
            'issuer' => env('APP_NAME', 'Servicio Linea Once'),
            'window' => 1,
            'recovery_codes' => 8,
        ],
    ],
];
```

## Internacionalizacion

El paquete trabaja con dos idiomas:

- `es`
- `en`

### Idioma del instalador

El idioma del comando se controla con:

```bash
php artisan kitlauncher:install --lang=es
php artisan kitlauncher:install --lang=en
```

Si no pasas `--lang` y el comando es interactivo, el instalador pregunta:

```text
Choose installer language / Elige el idioma del instalador
```

### Idioma de la aplicacion

El scaffolding instala:

```text
app/Http/Middleware/SetLocale.php
lang/es/kit.php
lang/en/kit.php
```

Tambien registra la ruta:

```text
POST /locale/{locale}
```

El middleware lee el idioma desde la sesion:

```php
$locale = $request->session()->get('locale', config('app.locale', 'es'));
```

Los presets Inertia incluyen helpers frontend:

```text
resources/js/lib/i18n.js
resources/js/lib/i18n.jsx
```

## Flujo recomendado despues de instalar

El instalador puede modificar `composer.json` y `package.json` segun el preset elegido. Por eso, despues de ejecutarlo, corre:

```bash
composer install
npm install
php artisan migrate
npm run build
```

Para iniciar el entorno de desarrollo:

```bash
npm run dev
php artisan serve
```

Para validar el proyecto:

```bash
php artisan test
```

## Cambiar de preset

Puedes cambiar de preset ejecutando el instalador otra vez con `--force`.

De PrimeVue a React:

```bash
php artisan kitlauncher:install --lang=es --ui=react --auth --force --no-interaction
composer install
npm install
npm run build
```

De Inertia a WireUI:

```bash
php artisan kitlauncher:install --lang=es --ui=wireui --auth --force --no-interaction
composer install
npm install
npm run build
```

Al cambiar de preset, el comando:

- Actualiza dependencias Composer.
- Actualiza dependencias NPM.
- Elimina dependencias UI obsoletas conocidas.
- Limpia archivos frontend administrados.
- Cambia `vite.config.js`.
- Elimina `HandleInertiaRequests` si el preset nuevo no usa Inertia.

## Estructura del paquete

```text
starter-kit/
|-- src/
|   |-- StarterKitServiceProvider.php
|   `-- Console/
|       `-- InstallCommand.php
|-- stubs/
|   |-- base/
|   |   |-- install/
|   |   |-- auth/
|   |   `-- middleware/
|   `-- presets/
|       |-- primevue/
|       |-- naive/
|       |-- react/
|       `-- wireui/
|-- composer.json
|-- LICENSE
`-- README.md
```

### `StarterKitServiceProvider`

Registra el comando cuando Laravel corre en consola:

```php
$this->commands([
    InstallCommand::class,
]);
```

### `InstallCommand`

Contiene la logica principal:

- definicion de presets UI
- validacion de opciones
- wizard bilingue
- actualizacion de `composer.json`
- actualizacion de `package.json`
- copia de stubs
- registro de middleware
- instalacion de auth
- limpieza de archivos obsoletos
- ejecucion opcional de comandos posteriores

### `stubs/base`

Archivos comunes para todos los presets:

- rutas base
- middleware de locale
- middleware Inertia
- configuracion de auth
- controladores
- requests
- modelo `User`
- soporte de 2FA
- migracion de 2FA
- tests de auth
- traducciones

### `stubs/presets`

Archivos especificos por UI:

```text
stubs/presets/primevue
stubs/presets/naive
stubs/presets/react
stubs/presets/wireui
```

Cada preset puede tener:

- `install`: archivos instalados siempre.
- `auth`: archivos instalados solo con `--auth`.

## Crear un nuevo preset

Los presets se registran en `InstallCommand::UI_PRESETS`.

Pasos recomendados:

1. Crear una carpeta para el preset:

```text
stubs/presets/{nombre}/install
```

2. Agregar los archivos frontend necesarios:

```text
resources/css/app.css
resources/js/app.js
resources/js/Pages
resources/js/Layouts
resources/js/Components
resources/views/app.blade.php
vite.config.js
```

3. Si el preset tiene pantallas de auth propias, crear:

```text
stubs/presets/{nombre}/auth
```

4. Registrar el preset en `UI_PRESETS`:

```php
'nombre' => [
    'label' => 'Nombre UI',
    'stack' => 'inertia',
    'extension' => 'js',
    'inertia' => true,
    'composer' => self::INERTIA_COMPOSER_REQUIREMENTS,
    'dependencies' => [
        // npm dependencies
    ],
    'devDependencies' => [
        // npm dev dependencies
    ],
],
```

5. Probar instalacion:

```bash
php artisan kitlauncher:install --lang=es --ui=nombre --auth --force --no-interaction
composer install
npm install
php artisan migrate
npm run build
php artisan test
```

## Troubleshooting

### `Class "Livewire\LivewireServiceProvider" not found`

El proyecto tiene `composer.json` actualizado, pero `vendor` todavia no tiene las dependencias nuevas.

Solucion:

```bash
composer install
php artisan optimize:clear
```

### `Class "Inertia\Middleware" not found`

Suele ocurrir si la aplicacion quedo con middleware Inertia registrado, pero ya no tiene instalado `inertiajs/inertia-laravel`.

Solucion al usar WireUI:

```bash
php artisan kitlauncher:install --lang=es --ui=wireui --auth --force --no-interaction
composer install
php artisan optimize:clear
```

### Tailwind no encuentra `@tailwindcss/forms`

El preset WireUI necesita instalar dependencias NPM.

```bash
npm install
npm run build
```

### Vite no encuentra paginas Inertia

Verifica que el preset y la extension coincidan:

- Vue usa `resources/js/app.js` y paginas `.vue`.
- React usa `resources/js/app.jsx` y paginas `.jsx`.
- WireUI no usa paginas Inertia.

Si cambiaste de preset, reinstala con `--force`:

```bash
php artisan kitlauncher:install --lang=es --ui=react --auth --force --no-interaction
npm install
npm run build
```

### La ruta `/dashboard` no existe

Instala auth con `--auth`. El dashboard protegido se registra dentro de `routes/auth.php`.

```bash
php artisan kitlauncher:install --lang=es --ui=primevue --auth --force --no-interaction
```

### El instalador omite archivos existentes

Es el comportamiento esperado cuando no usas `--force`.

Para sobrescribir los archivos administrados por el starter kit:

```bash
php artisan kitlauncher:install --lang=es --ui=primevue --auth --force
```

### No se ven cambios despues de instalar

Limpia caches y recompila:

```bash
php artisan optimize:clear
npm run build
```

## Desarrollo

Comandos utiles para mantener el paquete:

```bash
composer validate --no-check-publish
php -l src/StarterKitServiceProvider.php
php -l src/Console/InstallCommand.php
```

Validacion recomendada dentro de una app Laravel de prueba:

```bash
php artisan kitlauncher:install --lang=es --ui=primevue --auth --force --no-interaction
composer install
npm install
php artisan migrate
npm run build
php artisan test
```

Repetir la validacion para cada preset:

```bash
php artisan kitlauncher:install --lang=es --ui=naive --auth --force --no-interaction
php artisan kitlauncher:install --lang=es --ui=react --auth --force --no-interaction
php artisan kitlauncher:install --lang=es --ui=wireui --auth --force --no-interaction
```

## Licencia

Este paquete esta publicado bajo licencia MIT. Ver [LICENSE](LICENSE).
