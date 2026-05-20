<div class="kit-panel relative rounded-lg p-7 pt-12">
    <span class="kit-panel-tab">{{ __('kit.login_tab') }}</span>
    <div class="mb-7 flex items-center gap-3">
        <span class="kit-brand-mark h-10 w-10 rounded-lg"><span class="kit-brand-letter text-xl">S</span></span>
        <div>
            <h1 class="text-3xl font-black text-white">{{ __('kit.welcome_back') }}</h1>
            <p class="mt-1 text-sm text-slate-400">{{ __('kit.login_subtitle') }}</p>
        </div>
    </div>

    @if ($status)
        <div class="mt-5 rounded-lg border border-emerald-300/25 bg-emerald-300/10 p-3 text-sm text-emerald-100">
            {{ __('kit.status')[$status] ?? __($status) }}
        </div>
    @endif

    <x-errors class="mt-5" />

    <form wire:submit="authenticate" class="mt-6 grid gap-5">
        <x-input label="{{ __('kit.email') }}" type="email" wire:model.blur="email" autocomplete="username" placeholder="{{ __('kit.email_placeholder') }}" />
        <x-password label="{{ __('kit.password') }}" wire:model.blur="password" autocomplete="current-password" />

        <label class="flex items-center gap-3 text-sm text-slate-300">
            <input type="checkbox" wire:model="remember" class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-cyan-400">
            {{ __('kit.remember') }}
        </label>

        <x-button type="submit" primary label="{{ __('kit.login') }}" spinner="authenticate" />
    </form>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 text-sm">
        @if ($canResetPassword)
            <a href="{{ route('password.request') }}" wire:navigate class="kit-link">{{ __('kit.forgot_password') }}</a>
        @endif

        @if ($canRegister)
            <a href="{{ route('register') }}" wire:navigate class="kit-link">{{ __('kit.register_link') }}</a>
        @endif
    </div>
</div>
