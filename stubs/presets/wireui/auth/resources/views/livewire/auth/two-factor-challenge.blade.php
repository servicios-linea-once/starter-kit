<div class="kit-panel relative rounded-lg p-7 pt-12">
    <span class="kit-panel-tab">{{ __('kit.two_factor_tab') }}</span>
    <h1 class="text-3xl font-black text-white">{{ __('kit.two_factor_title') }}</h1>
    <p class="mt-2 text-sm text-slate-400">{{ __('kit.two_factor_subtitle') }}</p>

    <x-errors class="mt-5" />

    <form wire:submit="verify" class="mt-6 grid gap-5">
        @if ($useRecoveryCode)
            <x-input label="{{ __('kit.recovery_codes') }}" wire:model.blur="recovery_code" autocomplete="one-time-code" />
        @else
            <x-input label="{{ __('kit.two_factor') }}" wire:model.blur="code" autocomplete="one-time-code" />
        @endif

        <x-button type="submit" primary label="{{ __('kit.verify_code') }}" spinner="verify" />
    </form>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
        <button type="button" wire:click="$toggle('useRecoveryCode')" class="kit-link text-sm">
            {{ $useRecoveryCode ? __('kit.two_factor') : __('kit.recovery_codes') }}
        </button>

        <button type="button" wire:click="cancel" class="text-sm font-bold text-slate-400 hover:text-white">
            {{ __('kit.cancel') }}
        </button>
    </div>
</div>
