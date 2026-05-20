<?php

namespace ServiciosLineaOnce\StarterKit\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    protected $signature = 'kitlauncher:install
        {--auth : Install the authentication scaffolding}
        {--force : Overwrite existing frontend files}
        {--ui= : Frontend UI preset to install: primevue, naive, react, or wireui}
        {--lang= : Prompt language: en or es}';

    protected $description = 'Install the Servicio Linea Once starter kit.';

    private const INERTIA_COMPOSER_REQUIREMENTS = [
        'inertiajs/inertia-laravel' => '^3.0',
        'tightenco/ziggy' => '^2.6',
    ];

    private const AUTH_COMPOSER_REQUIREMENTS = [
        'bacon/bacon-qr-code' => '^3.1',
        'pragmarx/google2fa' => '^9.0',
    ];

    private const UI_PRESETS = [
        'primevue' => [
            'label' => 'PrimeVue',
            'stack' => 'inertia',
            'extension' => 'js',
            'inertia' => true,
            'composer' => self::INERTIA_COMPOSER_REQUIREMENTS,
            'dependencies' => [
                '@inertiajs/vue3' => '^3.1.1',
                '@primeuix/themes' => '^2.0.0',
                'primeicons' => '^7.0.0',
                'primevue' => '^4.5.5',
                'vue' => '^3.5.0',
                'ziggy-js' => '^2.6.2',
            ],
            'devDependencies' => [
                '@inertiajs/vite' => '^3.1.1',
                '@vitejs/plugin-vue' => '^6.0.0',
            ],
        ],
        'naive' => [
            'label' => 'Naive UI',
            'stack' => 'inertia',
            'extension' => 'js',
            'inertia' => true,
            'composer' => self::INERTIA_COMPOSER_REQUIREMENTS,
            'dependencies' => [
                '@inertiajs/vue3' => '^3.1.1',
                '@lucide/vue' => '^1.16.0',
                'naive-ui' => '^2.42.0',
                'vfonts' => '^0.0.3',
                'vue' => '^3.5.0',
                'ziggy-js' => '^2.6.2',
            ],
            'devDependencies' => [
                '@inertiajs/vite' => '^3.1.1',
                '@vitejs/plugin-vue' => '^6.0.0',
            ],
        ],
        'react' => [
            'label' => 'React + MUI',
            'stack' => 'inertia',
            'extension' => 'jsx',
            'inertia' => true,
            'composer' => self::INERTIA_COMPOSER_REQUIREMENTS,
            'dependencies' => [
                '@emotion/react' => '^11.14.0',
                '@emotion/styled' => '^11.14.1',
                '@inertiajs/react' => '^3.1.1',
                '@mui/icons-material' => '^9.0.1',
                '@mui/material' => '^9.0.1',
                '@radix-ui/react-label' => '^2.1.8',
                '@radix-ui/react-slot' => '^1.2.4',
                'class-variance-authority' => '^0.7.1',
                'clsx' => '^2.1.1',
                'lucide-react' => '^1.16.0',
                'react' => '^19.2.6',
                'react-dom' => '^19.2.6',
                'tailwind-merge' => '^3.6.0',
                'ziggy-js' => '^2.6.2',
            ],
            'devDependencies' => [
                '@inertiajs/vite' => '^3.1.1',
                '@vitejs/plugin-react' => '^5.2.0',
            ],
        ],
        'wireui' => [
            'label' => 'WireUI',
            'stack' => 'livewire',
            'extension' => 'js',
            'inertia' => false,
            'composer' => [
                'livewire/livewire' => '^3.6',
                'wireui/wireui' => '^2.4',
            ],
            'dependencies' => [],
            'devDependencies' => [
                '@tailwindcss/forms' => '^0.5.7',
            ],
        ],
    ];

    private const MANAGED_INSTALL_PATHS = [
        'resources/js/Components',
        'resources/js/Layouts',
        'resources/js/Pages',
        'resources/js/lib',
        'resources/js/presets',
        'resources/views/layouts',
        'resources/views/pages',
        'resources/css/theme-effects.css',
        'tests/Feature/ExampleTest.php',
    ];

    private const MANAGED_AUTH_PATHS = [
        'app/Livewire',
        'resources/views/livewire',
    ];

    private const LEGACY_DEPENDENCIES = [
        'lucide-vue-next',
    ];

    private const DEFAULT_LANGUAGE = 'en';

    private const MESSAGES = [
        'en' => [
            'auth' => 'Install authentication scaffolding?',
            'failed_command' => 'Command failed: :command',
            'force' => 'Overwrite existing frontend files?',
            'invalid_language' => 'Invalid language. Supported values: en, es.',
            'invalid_ui' => 'Invalid UI preset. Supported values: :presets.',
            'language' => 'Choose the installer language',
            'language_en' => 'English',
            'language_es' => 'Español',
            'post_commands' => 'Run composer install, npm install, migrations, and build now?',
            'recommended_commands' => 'Recommended commands: composer install && npm install && php artisan migrate && npm run build',
            'run_command' => 'Running: :command',
            'skipped_file' => 'Skipped existing file: :path',
            'stack' => 'Which stack do you want to install?',
            'stack_summary' => 'Stack: :value',
            'summary_auth' => 'Auth: :value',
            'summary_force' => 'Overwrite files: :value',
            'summary_no' => 'no',
            'summary_ui' => 'UI: :value',
            'summary_yes' => 'yes',
            'ui' => 'Which UI preset do you want to use?',
            'success' => 'Servicio Linea Once starter kit installed with [:ui].',
        ],
        'es' => [
            'auth' => '¿Instalar scaffolding de autenticación?',
            'failed_command' => 'Falló el comando: :command',
            'force' => '¿Sobrescribir archivos frontend existentes?',
            'invalid_language' => 'Idioma inválido. Valores soportados: en, es.',
            'invalid_ui' => 'Preset UI inválido. Valores soportados: :presets.',
            'language' => 'Elige el idioma del instalador',
            'language_en' => 'English',
            'language_es' => 'Español',
            'post_commands' => '¿Ejecutar ahora composer install, npm install, migraciones y build?',
            'recommended_commands' => 'Comandos recomendados: composer install && npm install && php artisan migrate && npm run build',
            'run_command' => 'Ejecutando: :command',
            'skipped_file' => 'Archivo existente omitido: :path',
            'stack' => '¿Qué stack quieres instalar?',
            'stack_summary' => 'Stack: :value',
            'summary_auth' => 'Auth: :value',
            'summary_force' => 'Sobrescribir archivos: :value',
            'summary_no' => 'no',
            'summary_ui' => 'UI: :value',
            'summary_yes' => 'sí',
            'ui' => '¿Qué preset UI quieres usar?',
            'success' => 'Starter kit de Servicio Linea Once instalado con [:ui].',
        ],
    ];

    private string $language = self::DEFAULT_LANGUAGE;

    /** @var array<string, true> Relative paths the installer wrote during this run. */
    private array $writtenFiles = [];

    public function handle(Filesystem $files): int
    {
        $basePath = base_path();

        try {
            $this->language = $this->resolveLanguage();
            $options = $this->resolveInstallOptions();
        } catch (RuntimeException) {
            return self::FAILURE;
        }

        $force = $options['force'];
        $withAuth = $options['auth'];
        $runPostInstallCommands = $options['runPostInstallCommands'];
        $ui = $options['ui'];

        if (! isset(self::UI_PRESETS[$ui])) {
            $this->components->error($this->message('invalid_ui', ['presets' => $this->supportedUiPresets()]));

            return self::FAILURE;
        }

        $this->updateComposer($basePath, $withAuth, $ui);
        $this->updatePackageJson($basePath, $ui);
        $this->copyStubs($files, $basePath, $force, $ui);

        if (! $this->presetUsesInertia($ui)) {
            $this->removeInertiaMiddleware($files, $basePath);
        }

        if ($this->presetUsesInertia($ui)) {
            $this->ensureInertiaMiddleware($files, $basePath);
        }

        $this->ensureLocaleMiddleware($files, $basePath);

        if ($withAuth) {
            $this->installAuth($files, $basePath, $force, $ui);
        }

        if (! $this->presetUsesInertia($ui)) {
            $this->removeInertiaMiddleware($files, $basePath);
        }

        $this->components->info($this->message('success', ['ui' => $ui]));
        $this->summarizeInstall($ui, $withAuth, $force);

        if ($runPostInstallCommands && ! $this->runPostInstallCommands($basePath)) {
            return self::FAILURE;
        }

        $this->components->warn($this->message('recommended_commands'));

        return self::SUCCESS;
    }

    /**
     * @return array{ui: string, auth: bool, force: bool, runPostInstallCommands: bool}
     */
    private function resolveInstallOptions(): array
    {
        $uiOption = $this->option('ui');
        $uiWasProvided = $this->optionWasProvided('ui') && filled($uiOption);
        $authWasProvided = $this->optionWasProvided('auth');
        $forceWasProvided = $this->optionWasProvided('force');

        if (! $this->input->isInteractive()) {
            return [
                'ui' => $uiWasProvided ? $this->normalizeUiOption((string) $uiOption) : 'primevue',
                'auth' => $authWasProvided,
                'force' => $forceWasProvided,
                'runPostInstallCommands' => false,
            ];
        }

        $ui = $uiWasProvided
            ? $this->normalizeUiOption((string) $uiOption)
            : $this->askForUi($this->askForStack());

        return [
            'ui' => $ui,
            'auth' => $authWasProvided ? true : $this->confirm($this->message('auth'), true),
            'force' => $forceWasProvided ? true : $this->confirm($this->message('force'), false),
            'runPostInstallCommands' => $this->confirm($this->message('post_commands'), false),
        ];
    }

    private function resolveLanguage(): string
    {
        $languageOption = $this->option('lang');

        if ($this->optionWasProvided('lang') && filled($languageOption)) {
            return $this->normalizeLanguageOption((string) $languageOption);
        }

        if (! $this->input->isInteractive()) {
            return self::DEFAULT_LANGUAGE;
        }

        return $this->askForLanguage();
    }

    private function askForLanguage(): string
    {
        $labels = [
            self::MESSAGES[self::DEFAULT_LANGUAGE]['language_en'] => 'en',
            self::MESSAGES[self::DEFAULT_LANGUAGE]['language_es'] => 'es',
        ];

        $choice = $this->choice(
            'Choose installer language / Elige el idioma del instalador',
            array_keys($labels),
            self::MESSAGES[self::DEFAULT_LANGUAGE]['language_en']
        );

        return $labels[$choice];
    }

    private function normalizeLanguageOption(string $language): string
    {
        $language = strtolower($language);

        if (! isset(self::MESSAGES[$language])) {
            $this->components->error(self::MESSAGES[self::DEFAULT_LANGUAGE]['invalid_language']);

            throw new RuntimeException("Invalid language [{$language}].");
        }

        return $language;
    }

    private function askForStack(): string
    {
        $stackLabels = [
            'Inertia' => 'inertia',
            'Livewire' => 'livewire',
        ];

        $choice = $this->choice(
            $this->message('stack'),
            array_keys($stackLabels),
            'Inertia'
        );

        return $stackLabels[$choice];
    }

    private function askForUi(string $stack): string
    {
        $uiLabels = [];

        foreach ($this->uiPresetsForStack($stack) as $ui) {
            $uiLabels[$this->presetLabel($ui)] = $ui;
        }

        $choice = $this->choice(
            $this->message('ui'),
            array_keys($uiLabels),
            array_key_first($uiLabels)
        );

        return $uiLabels[$choice];
    }

    private function normalizeUiOption(string $ui): string
    {
        $ui = strtolower($ui);

        if (! isset(self::UI_PRESETS[$ui])) {
            $this->components->error($this->message('invalid_ui', ['presets' => $this->supportedUiPresets()]));

            throw new RuntimeException("Invalid UI preset [{$ui}].");
        }

        return $ui;
    }

    private function optionWasProvided(string $option): bool
    {
        return $this->input->hasParameterOption('--'.$option);
    }

    private function summarizeInstall(string $ui, bool $withAuth, bool $force): void
    {
        $this->line($this->message('stack_summary', ['value' => $this->presetStackLabel($ui)]));
        $this->line($this->message('summary_ui', ['value' => $this->presetLabel($ui)]));
        $this->line($this->message('summary_auth', ['value' => $this->yesNo($withAuth)]));
        $this->line($this->message('summary_force', ['value' => $this->yesNo($force)]));
    }

    private function runPostInstallCommands(string $basePath): bool
    {
        foreach ($this->postInstallCommands() as $command) {
            $this->components->info($this->message('run_command', ['command' => implode(' ', $command)]));

            $process = new Process($command, $basePath);
            $process->setTimeout(null);
            $process->run(function (string $type, string $buffer): void {
                $this->output->write($buffer);
            });

            if (! $process->isSuccessful()) {
                $this->components->error($this->message('failed_command', ['command' => implode(' ', $command)]));

                return false;
            }
        }

        return true;
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function postInstallCommands(): array
    {
        return [
            ['composer', 'install'],
            ['npm', 'install'],
            ['php', 'artisan', 'migrate'],
            ['npm', 'run', 'build'],
        ];
    }

    private function updateComposer(string $basePath, bool $withAuth, string $ui): void
    {
        $path = $basePath.'/composer.json';
        $json = $this->readJson($path);

        $json['require'] = $json['require'] ?? [];

        foreach ($this->obsoleteComposerRequirements($ui) as $dependency) {
            unset($json['require'][$dependency]);
        }

        $json['require'] = array_replace($json['require'], $this->presetComposerRequirements($ui));

        if ($withAuth) {
            $json['require'] = array_replace($json['require'], self::AUTH_COMPOSER_REQUIREMENTS);
        }

        $this->writeJson($path, $json);
    }

    private function updatePackageJson(string $basePath, string $ui): void
    {
        $path = $basePath.'/package.json';
        $json = $this->readJson($path);

        $json['dependencies'] = $json['dependencies'] ?? [];

        foreach ($this->obsoleteUiDependencies($ui) as $dependency) {
            unset($json['dependencies'][$dependency]);
        }

        $json['dependencies'] = array_replace($json['dependencies'], $this->presetDependencies($ui));

        $json['devDependencies'] = $json['devDependencies'] ?? [];

        foreach ($this->obsoleteDevDependencies($ui) as $dependency) {
            unset($json['devDependencies'][$dependency]);
        }

        $json['devDependencies'] = array_replace($json['devDependencies'], $this->presetDevDependencies($ui));

        ksort($json['dependencies']);
        ksort($json['devDependencies']);

        $this->writeJson($path, $json);
    }

    /**
     * @return array<int, string>
     */
    private function obsoleteUiDependencies(string $ui): array
    {
        return array_values(array_diff($this->knownDependencies(), array_keys($this->presetDependencies($ui))));
    }

    /**
     * @return array<int, string>
     */
    private function obsoleteDevDependencies(string $ui): array
    {
        return array_values(array_diff($this->knownDevDependencies(), array_keys($this->presetDevDependencies($ui))));
    }

    private function copyStubs(Filesystem $files, string $basePath, bool $force, string $ui): void
    {
        if ($force) {
            foreach ($this->obsoleteFrontendExtensions($this->frontendExtension($ui)) as $obsoleteExtension) {
                $files->delete($basePath.'/resources/js/app.'.$obsoleteExtension);
            }

            $this->cleanupPaths($files, $basePath, self::MANAGED_INSTALL_PATHS);
        }

        $this->layeredCopy($files, [
            $this->baseStubPath().'/install',
            $this->presetStubPath($ui).'/install',
        ], $basePath, $force);
    }

    private function installAuth(Filesystem $files, string $basePath, bool $force, string $ui): void
    {
        if ($force) {
            $this->cleanupPaths($files, $basePath, self::MANAGED_AUTH_PATHS);
        }

        $this->layeredCopy($files, [
            $this->baseStubPath().'/auth',
            $this->presetStubPath($ui).'/auth',
        ], $basePath, $force);

        $webRouteStub = $files->exists($this->presetStubPath($ui).'/install/routes/web.php')
            ? $this->presetStubPath($ui).'/install/routes/web.php'
            : $this->baseStubPath().'/install/routes/web.php';

        $files->ensureDirectoryExists(dirname($basePath.'/routes/web.php'));
        $files->copy($webRouteStub, $basePath.'/routes/web.php');
        $this->writtenFiles['routes/web.php'] = true;

        $this->ensureAuthRoutes($files, $basePath);
        $this->ensureAuthMiddleware($files, $basePath);
    }

    private function baseStubPath(): string
    {
        return dirname(__DIR__, 2).'/stubs/base';
    }

    private function presetStubPath(string $ui): string
    {
        return dirname(__DIR__, 2).'/stubs/presets/'.$ui;
    }

    /**
     * @param  array<int, string>  $layers
     */
    private function layeredCopy(Filesystem $files, array $layers, string $basePath, bool $force): void
    {
        foreach ($layers as $layer) {
            if (! $files->isDirectory($layer)) {
                continue;
            }

            foreach ($files->allFiles($layer) as $file) {
                $relative = str_replace('\\', '/', $file->getRelativePathname());
                $target = $basePath.'/'.$relative;
                $writtenByUs = isset($this->writtenFiles[$relative]);

                if ($files->exists($target) && ! $writtenByUs && ! $force) {
                    $this->components->warn($this->message('skipped_file', ['path' => $target]));

                    continue;
                }

                $files->ensureDirectoryExists(dirname($target));
                $files->copy($file->getPathname(), $target);
                $this->writtenFiles[$relative] = true;
            }
        }
    }

    private function frontendExtension(string $ui): string
    {
        return $this->preset($ui)['extension'];
    }

    private function presetLabel(string $ui): string
    {
        return $this->preset($ui)['label'];
    }

    private function presetStack(string $ui): string
    {
        return $this->preset($ui)['stack'];
    }

    private function presetStackLabel(string $ui): string
    {
        return match ($this->presetStack($ui)) {
            'livewire' => 'Livewire',
            default => 'Inertia',
        };
    }

    /**
     * @param  array<string, string>  $replace
     */
    private function message(string $key, array $replace = []): string
    {
        $message = self::MESSAGES[$this->language][$key] ?? self::MESSAGES[self::DEFAULT_LANGUAGE][$key] ?? $key;

        foreach ($replace as $placeholder => $value) {
            $message = str_replace(':'.$placeholder, $value, $message);
        }

        return $message;
    }

    private function yesNo(bool $value): string
    {
        return $this->message($value ? 'summary_yes' : 'summary_no');
    }

    /**
     * @return array<int, string>
     */
    private function uiPresetsForStack(string $stack): array
    {
        return array_values(array_filter(
            array_keys(self::UI_PRESETS),
            fn (string $ui): bool => $this->presetStack($ui) === $stack
        ));
    }

    /**
     * @return array<int, string>
     */
    private function obsoleteFrontendExtensions(string $activeExtension): array
    {
        return array_values(array_diff(['js', 'jsx'], [$activeExtension]));
    }

    private function presetUsesInertia(string $ui): bool
    {
        return $this->preset($ui)['inertia'];
    }

    /**
     * @return array<string, string>
     */
    private function presetComposerRequirements(string $ui): array
    {
        return $this->preset($ui)['composer'];
    }

    /**
     * @return array<string, string>
     */
    private function presetDependencies(string $ui): array
    {
        return $this->preset($ui)['dependencies'];
    }

    /**
     * @return array<string, string>
     */
    private function presetDevDependencies(string $ui): array
    {
        return $this->preset($ui)['devDependencies'];
    }

    /**
     * @return array<string, mixed>
     */
    private function preset(string $ui): array
    {
        return self::UI_PRESETS[$ui];
    }

    /**
     * @return array<int, string>
     */
    private function obsoleteComposerRequirements(string $ui): array
    {
        return array_values(array_diff($this->knownComposerRequirements(), array_keys($this->presetComposerRequirements($ui))));
    }

    /**
     * @return array<int, string>
     */
    private function knownComposerRequirements(): array
    {
        $requirements = [];

        foreach (self::UI_PRESETS as $preset) {
            $requirements = array_merge($requirements, array_keys($preset['composer']));
        }

        return array_values(array_unique($requirements));
    }

    /**
     * @return array<int, string>
     */
    private function knownDependencies(): array
    {
        $dependencies = self::LEGACY_DEPENDENCIES;

        foreach (self::UI_PRESETS as $preset) {
            $dependencies = array_merge($dependencies, array_keys($preset['dependencies']));
        }

        return array_values(array_unique($dependencies));
    }

    /**
     * @return array<int, string>
     */
    private function knownDevDependencies(): array
    {
        $dependencies = [];

        foreach (self::UI_PRESETS as $preset) {
            $dependencies = array_merge($dependencies, array_keys($preset['devDependencies']));
        }

        return array_values(array_unique($dependencies));
    }

    private function supportedUiPresets(): string
    {
        return implode(', ', array_keys(self::UI_PRESETS));
    }

    /**
     * @param  array<int, string>  $relativePaths
     */
    private function cleanupPaths(Filesystem $files, string $basePath, array $relativePaths): void
    {
        foreach ($relativePaths as $relativePath) {
            $path = $basePath.'/'.$relativePath;

            if ($files->isDirectory($path)) {
                $files->deleteDirectory($path);

                continue;
            }

            $files->delete($path);
        }
    }

    private function ensureAuthRoutes(Filesystem $files, string $basePath): void
    {
        $bootstrapPath = $basePath.'/bootstrap/app.php';
        $contents = $files->get($bootstrapPath);

        if (! str_contains($contents, 'routes/auth.php')) {
            $contents = preg_replace(
                "/(health:\s*'\/up',\R)(\s*\))/",
                "$1        then: function (): void {\n            Route::middleware('web')->group(base_path('routes/auth.php'));\n        },\n$2",
                $contents,
                1
            );
        }

        $contents = $this->ensureUseStatement($contents, 'use Illuminate\Support\Facades\Route;');

        $files->put($bootstrapPath, $contents);
    }

    private function ensureAuthMiddleware(Filesystem $files, string $basePath): void
    {
        $bootstrapPath = $basePath.'/bootstrap/app.php';
        $contents = $files->get($bootstrapPath);

        $contents = $this->ensureUseStatement($contents, 'use App\Http\Middleware\EnsureTwoFactorChallengeIsComplete;');
        $contents = $this->appendWebMiddleware($contents, 'EnsureTwoFactorChallengeIsComplete');

        $files->put($bootstrapPath, $contents);
    }

    private function ensureInertiaMiddleware(Filesystem $files, string $basePath): void
    {
        $middlewarePath = $basePath.'/app/Http/Middleware/HandleInertiaRequests.php';

        if (! $files->exists($middlewarePath)) {
            $files->ensureDirectoryExists(dirname($middlewarePath));
            $files->copy($this->baseStubPath().'/middleware/HandleInertiaRequests.php', $middlewarePath);
        }

        $this->writtenFiles['app/Http/Middleware/HandleInertiaRequests.php'] = true;

        $bootstrapPath = $basePath.'/bootstrap/app.php';
        $contents = $files->get($bootstrapPath);

        $contents = $this->ensureUseStatement($contents, 'use App\Http\Middleware\HandleInertiaRequests;');
        $contents = $this->appendWebMiddleware($contents, 'HandleInertiaRequests');

        $files->put($bootstrapPath, $contents);
    }

    private function ensureLocaleMiddleware(Filesystem $files, string $basePath): void
    {
        $bootstrapPath = $basePath.'/bootstrap/app.php';
        $contents = $files->get($bootstrapPath);

        $contents = $this->ensureUseStatement($contents, 'use App\Http\Middleware\SetLocale;');
        $contents = $this->appendWebMiddleware($contents, 'SetLocale');

        $files->put($bootstrapPath, $contents);
    }

    private function removeInertiaMiddleware(Filesystem $files, string $basePath): void
    {
        $bootstrapPath = $basePath.'/bootstrap/app.php';

        if (! $files->exists($bootstrapPath)) {
            return;
        }

        $contents = $files->get($bootstrapPath);
        $contents = preg_replace('/^use App\\\\Http\\\\Middleware\\\\HandleInertiaRequests;\R/m', '', $contents) ?? $contents;
        $contents = preg_replace('/,\s*HandleInertiaRequests::class/', '', $contents) ?? $contents;
        $contents = preg_replace('/HandleInertiaRequests::class,\s*/', '', $contents) ?? $contents;
        $contents = preg_replace('/HandleInertiaRequests::class/', '', $contents) ?? $contents;
        $contents = preg_replace("/\s*\\\$middleware->web\(append:\s*\[\s*\]\);\R/", "\n", $contents) ?? $contents;

        $files->put($bootstrapPath, $contents);

        $files->delete($basePath.'/app/Http/Middleware/HandleInertiaRequests.php');
    }

    private function ensureUseStatement(string $contents, string $statement): string
    {
        if (str_contains($contents, $statement)) {
            return $contents;
        }

        return preg_replace('/<\?php\R+/', "<?php\n\n{$statement}\n", $contents, 1) ?? $contents;
    }

    private function appendWebMiddleware(string $contents, string $class): string
    {
        if (str_contains($contents, "{$class}::class")) {
            return $contents;
        }

        if (preg_match('/\$middleware->web\(append:\s*\[(?<classes>[^\]]*)\]\);/', $contents)) {
            return preg_replace_callback(
                '/\$middleware->web\(append:\s*\[(?<classes>[^\]]*)\]\);/',
                function (array $matches) use ($class): string {
                    $classes = array_filter(array_map('trim', explode(',', $matches['classes'])));
                    $classes[] = $class.'::class';

                    return '$middleware->web(append: ['.implode(', ', $classes).']);';
                },
                $contents,
                1
            ) ?? $contents;
        }

        return preg_replace(
            "/->withMiddleware\(function \(Middleware \$middleware\): void \{\R\s*\/\/\R\s*\}\)/",
            "->withMiddleware(function (Middleware \$middleware): void {\n        \$middleware->web(append: [{$class}::class]);\n    })",
            $contents,
            1
        ) ?? $contents;
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
        $contents = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;

        if (file_exists($path) && file_get_contents($path) === $contents) {
            return;
        }

        file_put_contents($path, $contents);
    }
}
