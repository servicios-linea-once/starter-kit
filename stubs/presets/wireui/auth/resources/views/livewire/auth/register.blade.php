<div class="kit-panel relative rounded-lg p-7 pt-12">
    <span class="kit-panel-tab">{{ __('kit.register_tab') }}</span>
    <h1 class="text-3xl font-black text-white">{{ __('kit.create_account') }}</h1>
    <p class="mt-2 text-sm text-slate-400">{{ __('kit.register_subtitle') }}</p>

    <x-errors class="mt-5" />

    <form wire:submit="register" class="mt-6 grid gap-5">
        <x-input label="{{ __('kit.name') }}" wire:model.blur="name" autocomplete="name" />
        <x-input label="{{ __('kit.email') }}" type="email" wire:model.blur="email" autocomplete="username" placeholder="{{ __('kit.email_placeholder') }}" />
        <x-password label="{{ __('kit.password') }}" wire:model.blur="password" autocomplete="new-password" />
        <x-password label="{{ __('kit.confirm_new_password') }}" wire:model.blur="password_confirmation" autocomplete="new-password" />

        <x-button type="submit" primary label="{{ __('kit.register') }}" spinner="register" />
    </form>

    <p class="mt-6 text-sm text-slate-400">
        {{ __('kit.already_account') }}
        <a href="{{ route('login') }}" wire:navigate class="kit-link">{{ __('kit.login_link') }}</a>
    </p>
</div>
