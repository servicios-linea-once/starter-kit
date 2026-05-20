<div class="kit-panel relative rounded-lg p-7 pt-12">
    <span class="kit-panel-tab">{{ __('kit.reset_password_tab') }}</span>
    <h1 class="text-3xl font-black text-white">{{ __('kit.reset_title') }}</h1>
    <p class="mt-2 text-sm text-slate-400">{{ __('kit.reset_subtitle') }}</p>

    <x-errors class="mt-5" />

    <form wire:submit="resetPassword" class="mt-6 grid gap-5">
        <x-input label="{{ __('kit.email') }}" type="email" wire:model.blur="email" autocomplete="username" />
        <x-password label="{{ __('kit.new_password') }}" wire:model.blur="password" autocomplete="new-password" />
        <x-password label="{{ __('kit.confirm_new_password') }}" wire:model.blur="password_confirmation" autocomplete="new-password" />

        <x-button type="submit" primary label="{{ __('kit.update_password') }}" spinner="resetPassword" />
    </form>
</div>
