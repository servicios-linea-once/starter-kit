@extends('layouts.app')

@section('content')
    <section class="mx-auto max-w-6xl px-6 py-10">
        <div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase text-cyan-200">{{ __('kit.dashboard_tab') }}</p>
                <h1 class="mt-2 text-4xl font-black text-white">{{ __('kit.dashboard_greeting', ['name' => auth()->user()->name]) }}</h1>
                <p class="mt-3 text-slate-400">{{ __('kit.dashboard_body') }}</p>
            </div>
            <x-button href="{{ route('profile.edit') }}" wire:navigate primary label="{{ __('kit.profile_security') }}" />
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="kit-stat-card p-5">
                <p class="text-sm font-bold text-slate-400">{{ __('kit.total_sales') }}</p>
                <h2 class="mt-3 text-2xl font-bold text-white">S/ 45,680.00</h2>
                <p class="mt-2 text-xs text-emerald-300">+12.6%</p>
            </div>
            <div class="kit-stat-card p-5">
                <p class="text-sm font-bold text-slate-400">{{ __('kit.orders') }}</p>
                <h2 class="mt-3 text-2xl font-bold text-white">1,250</h2>
                <p class="mt-2 text-xs text-emerald-300">+8.2%</p>
            </div>
            <div class="kit-stat-card p-5">
                <p class="text-sm font-bold text-slate-400">{{ __('kit.customers') }}</p>
                <h2 class="mt-3 text-2xl font-bold text-white">850</h2>
                <p class="mt-2 text-xs text-emerald-300">+6.4%</p>
            </div>
            <div class="kit-stat-card p-5">
                <p class="text-sm font-bold text-slate-400">{{ __('kit.conversion') }}</p>
                <h2 class="mt-3 text-2xl font-bold text-white">3.24%</h2>
                <p class="mt-2 text-xs text-emerald-300">+1.2%</p>
            </div>
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-[1.4fr_0.8fr]">
            <div class="kit-panel rounded-lg p-6">
                <h2 class="font-bold text-white">{{ __('kit.sales') }}</h2>
                <div class="kit-chart-line mt-4"></div>
            </div>

            <div class="kit-panel rounded-lg p-6">
                <h2 class="font-bold text-white">{{ __('kit.sales_channel') }}</h2>
                <div class="mt-5 flex items-center justify-center">
                    <div class="kit-donut"></div>
                </div>
            </div>
        </div>

        <div class="mt-5 grid gap-5 md:grid-cols-3">
            <div class="kit-panel rounded-lg p-6">
                <p class="text-sm font-bold uppercase text-slate-400">Email</p>
                <h2 class="mt-3 text-lg font-bold text-white">{{ auth()->user()->email }}</h2>
                <span class="mt-4 inline-flex rounded-full border border-emerald-300/25 bg-emerald-300/10 px-3 py-1 text-xs font-bold text-emerald-100">
                    {{ __('kit.verified') }}
                </span>
            </div>

            <div class="kit-panel rounded-lg p-6">
                <p class="text-sm font-bold uppercase text-slate-400">2FA</p>
                <h2 class="mt-3 text-lg font-bold text-white">{{ __('kit.two_factor') }}</h2>
                <span class="mt-4 inline-flex rounded-full border px-3 py-1 text-xs font-bold {{ auth()->user()->hasEnabledTwoFactorAuthentication() ? 'border-emerald-300/25 bg-emerald-300/10 text-emerald-100' : 'border-amber-300/25 bg-amber-300/10 text-amber-100' }}">
                    {{ auth()->user()->hasEnabledTwoFactorAuthentication() ? __('kit.enabled') : __('kit.disabled') }}
                </span>
            </div>

            <div class="kit-panel rounded-lg p-6">
                <p class="text-sm font-bold uppercase text-slate-400">{{ __('kit.recent_activity') }}</p>
                <h2 class="mt-3 text-lg font-bold text-white">{{ __('kit.profile_security') }}</h2>
                <p class="mt-3 text-sm text-slate-400">{{ __('kit.profile_body') }}</p>
            </div>
        </div>
    </section>
@endsection
