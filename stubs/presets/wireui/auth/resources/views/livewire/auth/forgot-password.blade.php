<div class="kit-panel relative rounded-lg p-7 pt-12">
    <span class="kit-panel-tab">{{ __('kit.forgot_password_tab') }}</span>
    <h1 class="text-3xl font-black text-white">{{ __('kit.forgot_title') }}</h1>
    <p class="mt-2 text-sm text-slate-400">{{ __('kit.forgot_subtitle') }}</p>

    @if ($status)
        <div class="mt-5 rounded-lg border border-emerald-300/25 bg-emerald-300/10 p-3 text-sm text-emerald-100">
            {{ __('kit.status')[$status] ?? __($status) }}
        </div>
    @endif

    <x-errors class="mt-5" />

    <form wire:submit="sendResetLink" class="mt-6 grid gap-5">
        <x-input label="{{ __('kit.email') }}" type="email" wire:model.blur="email" autocomplete="username" placeholder="{{ __('kit.email_placeholder') }}" />
        <x-button type="submit" primary label="{{ __('kit.send_link') }}" spinner="sendResetLink" />
    </form>

    <a href="{{ route('login') }}" wire:navigate class="kit-link mt-6 inline-block text-sm">{{ __('kit.back_to_login') }}</a>
</div>
