<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Servicio Linea Once') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
        <x-notifications />
        <x-dialog />

        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset

        @wireUiScripts
        @livewireScripts
    </body>
</html>
