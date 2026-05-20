<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' - '.config('app.name', 'Servicio Linea Once') : config('app.name', 'Servicio Linea Once') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
        <x-notifications />
        <x-dialog />

        <main class="kit-auth-shell min-h-screen text-slate-100">
            <div class="kit-content mx-auto flex min-h-screen max-w-7xl flex-col px-5 py-7">
                <header class="kit-showcase-header">
                    <div class="flex w-full justify-end gap-1" aria-label="{{ __('kit.language') }}">
                        <form method="POST" action="{{ route('locale.update', 'es') }}">
                            @csrf
                            <button type="submit" class="rounded-lg px-3 py-1 text-xs font-bold {{ app()->getLocale() === 'es' ? 'kit-gradient-button' : 'border border-white/10 bg-white/5 text-slate-300' }}">ES</button>
                        </form>
                        <form method="POST" action="{{ route('locale.update', 'en') }}">
                            @csrf
                            <button type="submit" class="rounded-lg px-3 py-1 text-xs font-bold {{ app()->getLocale() === 'en' ? 'kit-gradient-button' : 'border border-white/10 bg-white/5 text-slate-300' }}">EN</button>
                        </form>
                    </div>

                    <a href="{{ route('home') }}" wire:navigate class="kit-brand-lockup">
                        <span class="kit-brand-mark h-14 w-14 rounded-lg">
                            <span class="kit-brand-letter text-3xl">S</span>
                        </span>
                        <span class="kit-brand-title">{{ __('kit.brand_full') }}</span>
                    </a>
                    <p class="kit-brand-subtitle">{{ __('kit.tagline') }}</p>
                    <span class="kit-brand-underline"></span>
                </header>

                <section class="grid flex-1 items-center gap-5 py-8 lg:grid-cols-[0.95fr_1.05fr]">
                    <div>
                        @isset($slot)
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endisset
                    </div>

                    <aside class="kit-visual-card hidden lg:block">
                        <div class="kit-portal"></div>
                    </aside>
                </section>
            </div>
        </main>

        @wireUiScripts
        @livewireScripts
    </body>
</html>
