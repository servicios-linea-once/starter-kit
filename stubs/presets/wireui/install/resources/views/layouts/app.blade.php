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

        <div class="kit-shell text-slate-100">
            <header class="relative z-10 border-b border-white/10 bg-slate-950/80 backdrop-blur">
                <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-4">
                    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3">
                        <span class="kit-brand-mark h-9 w-9 rounded-lg">
                            <span class="kit-brand-letter text-lg">S</span>
                        </span>
                        <span class="font-semibold">{{ __('kit.brand') }}</span>
                    </a>

                    <nav class="hidden items-center gap-5 md:flex">
                        @auth
                            <a href="{{ route('dashboard') }}" wire:navigate class="text-sm text-slate-300 hover:text-white">{{ __('kit.dashboard') }}</a>
                            <a href="{{ route('profile.edit') }}" wire:navigate class="text-sm text-slate-300 hover:text-white">{{ __('kit.profile_security') }}</a>
                        @endauth
                        <span class="rounded-full border border-cyan-300/25 bg-cyan-300/10 px-3 py-1 text-xs font-bold text-cyan-100">WireUI</span>
                        <span class="rounded-full border border-emerald-300/25 bg-emerald-300/10 px-3 py-1 text-xs font-bold text-emerald-100">Livewire</span>
                    </nav>

                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('locale.update', 'es') }}">
                            @csrf
                            <button type="submit" class="rounded-lg px-3 py-1 text-xs font-bold {{ app()->getLocale() === 'es' ? 'kit-gradient-button' : 'border border-white/10 bg-white/5 text-slate-300' }}">ES</button>
                        </form>
                        <form method="POST" action="{{ route('locale.update', 'en') }}">
                            @csrf
                            <button type="submit" class="rounded-lg px-3 py-1 text-xs font-bold {{ app()->getLocale() === 'en' ? 'kit-gradient-button' : 'border border-white/10 bg-white/5 text-slate-300' }}">EN</button>
                        </form>
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-button type="submit" sm slate label="{{ __('kit.logout') }}" />
                            </form>
                        @else
                            <x-button href="{{ route('login') }}" wire:navigate sm cyan label="{{ __('kit.login') }}" />
                        @endauth
                    </div>
                </div>
            </header>

            <main class="kit-content">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>

        @wireUiScripts
        @livewireScripts
    </body>
</html>
