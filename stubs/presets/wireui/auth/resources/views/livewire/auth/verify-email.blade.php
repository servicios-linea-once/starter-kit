<div class="kit-panel relative rounded-lg p-7 pt-12">
    <span class="kit-panel-tab">{{ __('kit.verify_email_tab') }}</span>
    <h1 class="text-3xl font-black text-white">{{ __('kit.verify_title') }}</h1>
    <p class="mt-2 text-sm text-slate-400">{{ __('kit.verify_subtitle') }}</p>

    @if ($status === 'verification-link-sent')
        <div class="mt-5 rounded-lg border border-emerald-300/25 bg-emerald-300/10 p-3 text-sm text-emerald-100">
            {{ __('kit.status')['verification-link-sent'] }}
        </div>
    @endif

    <div class="mt-6 flex flex-wrap gap-3">
        <x-button type="button" wire:click="resend" primary label="{{ __('kit.resend_email') }}" spinner="resend" />

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button type="submit" secondary label="{{ __('kit.logout') }}" />
        </form>
    </div>
</div>
