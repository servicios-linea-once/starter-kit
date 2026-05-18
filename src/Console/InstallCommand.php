<?php

namespace ServiciosLineaOnce\StarterKit\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

class InstallCommand extends Command
{
    protected $signature = 'kitlauncher:install
        {--auth : Install the authentication scaffolding}
        {--force : Overwrite existing frontend files}';

    protected $description = 'Install the KitLauncher Inertia, Vue, PrimeVue, Ziggy, and Tailwind CSS starter stack.';

    public function handle(Filesystem $files): int
    {
        $basePath = base_path();
        $force = (bool) $this->option('force');

        $this->updateComposer($basePath);
        $this->updatePackageJson($basePath);
        $this->copyStubs($files, $basePath, $force);
        $this->ensureInertiaMiddleware($files, $basePath);

        if ($this->option('auth')) {
            $this->installAuth($files, $basePath, $force);
        }

        $this->components->info('KitLauncher starter stack installed.');
        $this->components->warn('Run: composer update && npm install && php artisan migrate && npm run build');

        return self::SUCCESS;
    }

    private function updateComposer(string $basePath): void
    {
        $path = $basePath.'/composer.json';
        $json = $this->readJson($path);

        $json['require']['inertiajs/inertia-laravel'] = '^3.0';
        $json['require']['bacon/bacon-qr-code'] = '^3.1';
        $json['require']['pragmarx/google2fa'] = '^9.0';
        $json['require']['tightenco/ziggy'] = '^2.6';

        $this->writeJson($path, $json);
    }

    private function updatePackageJson(string $basePath): void
    {
        $path = $basePath.'/package.json';
        $json = $this->readJson($path);

        $json['dependencies'] = array_replace($json['dependencies'] ?? [], [
            '@inertiajs/vue3' => '^3.1.1',
            '@primeuix/themes' => '^2.0.0',
            'primeicons' => '^7.0.0',
            'primevue' => '^4.5.5',
            'vue' => '^3.5.0',
            'ziggy-js' => '^2.6.2',
        ]);

        $json['devDependencies'] = array_replace($json['devDependencies'] ?? [], [
            '@inertiajs/vite' => '^3.1.1',
            '@vitejs/plugin-vue' => '^6.0.0',
        ]);

        ksort($json['dependencies']);
        ksort($json['devDependencies']);

        $this->writeJson($path, $json);
    }

    private function copyStubs(Filesystem $files, string $basePath, bool $force): void
    {
        $stubPath = dirname(__DIR__, 2).'/stubs';

        $this->copyFile($files, $stubPath.'/routes/web.php', $basePath.'/routes/web.php', $force);
        $this->copyFile($files, $stubPath.'/resources/views/app.blade.php', $basePath.'/resources/views/app.blade.php', $force);
        $this->copyFile($files, $stubPath.'/resources/js/app.js', $basePath.'/resources/js/app.js', $force);
        $this->copyFile($files, $stubPath.'/resources/css/app.css', $basePath.'/resources/css/app.css', $force);
        $this->copyFile($files, $stubPath.'/resources/css/theme-effects.css', $basePath.'/resources/css/theme-effects.css', $force);
        $this->copyFile($files, $stubPath.'/vite.config.js', $basePath.'/vite.config.js', $force);

        $this->copyDirectory($files, $stubPath.'/resources/js/Layouts', $basePath.'/resources/js/Layouts', $force);
        $this->copyDirectory($files, $stubPath.'/resources/js/Pages', $basePath.'/resources/js/Pages', $force);
        $this->copyDirectory($files, $stubPath.'/resources/js/Components', $basePath.'/resources/js/Components', $force);
        $this->copyDirectory($files, $stubPath.'/resources/js/presets', $basePath.'/resources/js/presets', $force);
    }

    private function installAuth(Filesystem $files, string $basePath, bool $force): void
    {
        $stubPath = dirname(__DIR__, 2).'/stubs';

        $this->copyDirectory($files, $stubPath.'/app/Http/Controllers/Auth', $basePath.'/app/Http/Controllers/Auth', $force);
        $this->copyDirectory($files, $stubPath.'/app/Http/Controllers/Settings', $basePath.'/app/Http/Controllers/Settings', $force);
        $this->copyDirectory($files, $stubPath.'/app/Http/Middleware', $basePath.'/app/Http/Middleware', $force);
        $this->copyDirectory($files, $stubPath.'/app/Http/Requests/Auth', $basePath.'/app/Http/Requests/Auth', $force);
        $this->copyDirectory($files, $stubPath.'/app/Http/Requests/Settings', $basePath.'/app/Http/Requests/Settings', $force);
        $this->copyDirectory($files, $stubPath.'/app/Support/Auth', $basePath.'/app/Support/Auth', $force);
        $this->copyDirectory($files, $stubPath.'/database/migrations', $basePath.'/database/migrations', $force);
        $this->copyDirectory($files, $stubPath.'/resources/js/Pages/Auth', $basePath.'/resources/js/Pages/Auth', $force);
        $this->copyDirectory($files, $stubPath.'/resources/js/Pages/Settings', $basePath.'/resources/js/Pages/Settings', $force);
        $this->copyDirectory($files, $stubPath.'/tests/Feature/Auth', $basePath.'/tests/Feature/Auth', $force);

        $this->copyFile($files, $stubPath.'/app/Models/User.php', $basePath.'/app/Models/User.php', $force);
        $this->copyFile($files, $stubPath.'/config/servicios-linea-once.php', $basePath.'/config/servicios-linea-once.php', $force);
        $this->copyFile($files, $stubPath.'/routes/auth.php', $basePath.'/routes/auth.php', $force);
        $this->copyFile($files, $stubPath.'/routes/web.php', $basePath.'/routes/web.php', true);

        $this->ensureAuthRoutes($files, $basePath);
        $this->ensureAuthMiddleware($files, $basePath);
    }

    private function ensureAuthRoutes(Filesystem $files, string $basePath): void
    {
        $bootstrapPath = $basePath.'/bootstrap/app.php';
        $contents = $files->get($bootstrapPath);

        if (! str_contains($contents, "routes/auth.php")) {
            $contents = str_replace(
                "        health: '/up',\n    )",
                "        health: '/up',\n        then: function () {\n            Route::middleware('web')->group(base_path('routes/auth.php'));\n        },\n    )",
                $contents
            );
        }

        if (! str_contains($contents, 'use Illuminate\Support\Facades\Route;')) {
            $contents = preg_replace(
                '/use Illuminate\\\\Foundation\\\\Configuration\\\\Middleware;\R/',
                "use Illuminate\\Foundation\\Configuration\\Middleware;\nuse Illuminate\\Support\\Facades\\Route;\n",
                $contents,
                1
            );
        }

        $files->put($bootstrapPath, $contents);
    }

    private function ensureAuthMiddleware(Filesystem $files, string $basePath): void
    {
        $bootstrapPath = $basePath.'/bootstrap/app.php';
        $contents = $files->get($bootstrapPath);

        if (! str_contains($contents, 'App\Http\Middleware\EnsureTwoFactorChallengeIsComplete')) {
            $contents = preg_replace(
                '/use App\\\\Http\\\\Middleware\\\\HandleInertiaRequests;\R/',
                "use App\\Http\\Middleware\\HandleInertiaRequests;\nuse App\\Http\\Middleware\\EnsureTwoFactorChallengeIsComplete;\n",
                $contents,
                1
            );
        }

        if (! str_contains($contents, 'EnsureTwoFactorChallengeIsComplete::class')) {
            $contents = str_replace(
                '$middleware->web(append: [HandleInertiaRequests::class]);',
                '$middleware->web(append: [HandleInertiaRequests::class, EnsureTwoFactorChallengeIsComplete::class]);',
                $contents
            );
        }

        $files->put($bootstrapPath, $contents);
    }

    private function ensureInertiaMiddleware(Filesystem $files, string $basePath): void
    {
        $middlewarePath = $basePath.'/app/Http/Middleware/HandleInertiaRequests.php';

        if (! $files->exists($middlewarePath)) {
            $files->ensureDirectoryExists(dirname($middlewarePath));
            $files->copy(dirname(__DIR__, 2).'/stubs/app/Http/Middleware/HandleInertiaRequests.php', $middlewarePath);
        }

        $bootstrapPath = $basePath.'/bootstrap/app.php';
        $contents = $files->get($bootstrapPath);

        if (! str_contains($contents, 'App\Http\Middleware\HandleInertiaRequests')) {
            $contents = preg_replace(
                '/use Illuminate\\\\Foundation\\\\Configuration\\\\Middleware;\R/',
                "use Illuminate\\Foundation\\Configuration\\Middleware;\nuse App\\Http\\Middleware\\HandleInertiaRequests;\n",
                $contents,
                1
            );
        }

        if (! str_contains($contents, '$middleware->web(append: [HandleInertiaRequests::class]);')) {
            $contents = str_replace(
                "    ->withMiddleware(function (Middleware \$middleware): void {\n        //\n    })",
                "    ->withMiddleware(function (Middleware \$middleware): void {\n        \$middleware->web(append: [HandleInertiaRequests::class]);\n    })",
                $contents
            );
        }

        $files->put($bootstrapPath, $contents);
    }

    private function copyFile(Filesystem $files, string $from, string $to, bool $force): void
    {
        if ($files->exists($to) && ! $force) {
            $this->components->warn("Skipped existing file: {$to}");

            return;
        }

        $files->ensureDirectoryExists(dirname($to));
        $files->copy($from, $to);
    }

    private function copyDirectory(Filesystem $files, string $from, string $to, bool $force): void
    {
        if ($files->exists($to) && $force) {
            $files->deleteDirectory($to);
        }

        if ($files->exists($to) && ! $force) {
            $this->components->warn("Skipped existing directory: {$to}");

            return;
        }

        $files->ensureDirectoryExists($to);
        $files->copyDirectory($from, $to);
    }

    /**
     * @return array<string, mixed>
     */
    private function readJson(string $path): array
    {
        $json = json_decode(file_get_contents($path), true);

        if (! is_array($json)) {
            throw new RuntimeException("Unable to read JSON from [{$path}].");
        }

        return $json;
    }

    /**
     * @param  array<string, mixed>  $json
     */
    private function writeJson(string $path, array $json): void
    {
        file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);
    }
}
