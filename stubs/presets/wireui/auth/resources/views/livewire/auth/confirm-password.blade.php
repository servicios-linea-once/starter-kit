<div class="kit-panel relative rounded-lg p-7 pt-12">
    <span class="kit-panel-tab">{{ __('kit.confirm_password_tab') }}</span>
    <h1 class="text-3xl font-black text-white">{{ __('kit.confirm_title') }}</h1>
    <p class="mt-2 text-sm text-slate-400">{{ __('kit.confirm_subtitle') }}</p>

    <x-errors class="mt-5" />

    <form wire:submit="confirm" class="mt-6 grid gap-5">
        <x-password label="{{ __('kit.current_password') }}" wire:model.blur="password" autocomplete="current-password" />
        <x-button type="submit" primary label="{{ __('kit.confirm') }}" spinner="confirm" />
    </form>
</div>
