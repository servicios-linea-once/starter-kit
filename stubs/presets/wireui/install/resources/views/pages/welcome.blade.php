@extends('layouts.guest')

@section('content')
    <div class="kit-panel relative rounded-lg p-7 pt-12 text-center">
        <span class="kit-panel-tab">{{ __('kit.welcome_tab') }}</span>
        <span class="kit-brand-mark mx-auto h-20 w-20 rounded-lg">
            <span class="kit-brand-letter text-5xl">S</span>
        </span>

        <h2 class="mt-7 text-3xl font-black text-white">{{ __('kit.welcome_title') }}</h2>
        <p class="mt-3 leading-7 text-slate-400">{{ __('kit.welcome_body') }}</p>

        <div class="my-8">
            <div class="kit-orb-check">✓</div>
        </div>

        <p class="mx-auto max-w-sm text-sm leading-6 text-slate-400">{{ __('kit.welcome_note') }}</p>

        <div class="mt-7 flex flex-wrap justify-center gap-3">
            @auth
                <x-button href="{{ route('dashboard') }}" wire:navigate primary label="{{ __('kit.go_dashboard') }}" />
            @else
                <x-button href="{{ route('login') }}" wire:navigate primary label="{{ __('kit.login') }}" />
                @if (config('servicios-linea-once.auth.registration', true))
                    <x-button href="{{ route('register') }}" wire:navigate secondary label="{{ __('kit.register') }}" />
                @endif
            @endauth
        </div>
    </div>
@endsection
