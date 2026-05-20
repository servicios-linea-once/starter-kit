<section class="mx-auto max-w-6xl px-6 py-10">
    <div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
        <div>
            <p class="text-sm font-bold uppercase text-cyan-200">{{ __('kit.profile_security') }}</p>
            <h1 class="mt-2 text-4xl font-black text-white">{{ __('kit.account') }}</h1>
            <p class="mt-3 text-slate-400">{{ __('kit.profile_body') }}</p>
        </div>
        <a href="{{ route('dashboard') }}" wire:navigate class="kit-link text-sm">{{ __('kit.back_dashboard') }}</a>
    </div>

    @if ($status)
        <div class="mb-6 rounded-lg border border-emerald-300/25 bg-emerald-300/10 p-4 text-sm font-semibold text-emerald-100">
            {{ __('kit.status')[$status] ?? __($status) }}
        </div>
    @endif

    <x-errors class="mb-6" />

    <div class="grid gap-5 lg:grid-cols-[1fr_1fr]">
        <form wire:submit="updateProfile" class="kit-panel rounded-lg p-6">
            <div>
                <p class="text-sm font-bold uppercase text-slate-400">{{ __('kit.data') }}</p>
                <h2 class="mt-2 text-xl font-bold text-white">{{ __('kit.profile_data') }}</h2>
            </div>

            <div class="mt-5 grid gap-4">
                <x-input label="{{ __('kit.name') }}" wire:model.blur="name" autocomplete="name" />
                <x-input label="{{ __('kit.email') }}" type="email" wire:model.blur="email" autocomplete="username" />

                @if ($mustVerifyEmail && ! request()->user()->hasVerifiedEmail())
                    <div class="rounded-lg border border-amber-300/25 bg-amber-300/10 p-3 text-sm text-amber-100">
                        {{ __('kit.email_verify_notice') }}
                    </div>
                @endif

                <x-button type="submit" primary label="{{ __('kit.save_profile') }}" spinner="updateProfile" />
            </div>
        </form>

        <form wire:submit="updatePassword" class="kit-panel rounded-lg p-6">
            <div>
                <p class="text-sm font-bold uppercase text-slate-400">{{ __('kit.credentials') }}</p>
                <h2 class="mt-2 text-xl font-bold text-white">{{ __('kit.change_password') }}</h2>
            </div>

            <div class="mt-5 grid gap-4">
                <x-password label="{{ __('kit.current_password') }}" wire:model.blur="current_password" autocomplete="current-password" />
                <x-password label="{{ __('kit.new_password') }}" wire:model.blur="password" autocomplete="new-password" />
                <x-password label="{{ __('kit.confirm_new_password') }}" wire:model.blur="password_confirmation" autocomplete="new-password" />

                <x-button type="submit" primary label="{{ __('kit.update_password') }}" spinner="updatePassword" />
            </div>
        </form>

        <div class="kit-panel rounded-lg p-6 lg:col-span-2">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">
                <div>
                    <p class="text-sm font-bold uppercase text-slate-400">2FA</p>
                    <h2 class="mt-2 text-xl font-bold text-white">{{ __('kit.two_factor') }}</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-400">
                        {{ __('kit.two_factor_body') }}
                    </p>
                </div>

                @if (request()->user()->hasEnabledTwoFactorAuthentication())
                    <span class="rounded-full border border-emerald-300/25 bg-emerald-300/10 px-3 py-1 text-xs font-bold text-emerald-100">{{ __('kit.enabled') }}</span>
                @else
                    <span class="rounded-full border border-amber-300/25 bg-amber-300/10 px-3 py-1 text-xs font-bold text-amber-100">{{ __('kit.disabled') }}</span>
                @endif
            </div>

            @if (! request()->user()->hasEnabledTwoFactorAuthentication())
                <div class="mt-5">
                    @if (! $showTwoFactorSetup)
                        <x-button type="button" wire:click="enableTwoFactor" primary label="{{ __('kit.generate_qr') }}" spinner="enableTwoFactor" />
                    @else
                        <div class="grid gap-5 lg:grid-cols-[260px_1fr]">
                            <div class="rounded-lg border border-white/10 bg-white p-4 text-slate-950">
                                {!! $qrCodeSvg !!}
                            </div>

                            <form wire:submit="confirmTwoFactor" class="grid content-start gap-4">
                                <div class="rounded-lg border border-cyan-300/25 bg-cyan-300/10 p-4 text-sm text-cyan-100">
                                    {{ __('kit.scan_qr') }}
                                    <code class="mt-2 block break-all rounded bg-slate-950/70 p-2 text-cyan-50">{{ $twoFactorSecret }}</code>
                                </div>

                                <x-input label="{{ __('kit.six_digit_code') }}" wire:model.blur="two_factor_code" autocomplete="one-time-code" />
                                <x-button type="submit" positive label="{{ __('kit.confirm_2fa') }}" spinner="confirmTwoFactor" />
                            </form>
                        </div>
                    @endif
                </div>
            @else
                <div class="mt-5 grid gap-5 lg:grid-cols-2">
                    <form wire:submit="regenerateRecoveryCodes" class="rounded-lg border border-white/10 bg-white/5 p-4">
                        <h3 class="font-bold text-white">{{ __('kit.recovery_codes') }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">{{ __('kit.recovery_codes_body') }}</p>

                        @if (! empty($recoveryCodes))
                            <div class="mt-4 grid gap-2 rounded-lg bg-slate-950/70 p-4 font-mono text-sm text-cyan-100">
                                @foreach ($recoveryCodes as $code)
                                    <span>{{ $code }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 grid gap-3">
                            <x-password label="{{ __('kit.current_password') }}" wire:model.blur="recovery_password" autocomplete="current-password" />
                            <x-button type="submit" secondary label="{{ __('kit.regenerate_codes') }}" spinner="regenerateRecoveryCodes" />
                        </div>
                    </form>

                    <form wire:submit="disableTwoFactor" class="rounded-lg border border-white/10 bg-white/5 p-4">
                        <h3 class="font-bold text-white">{{ __('kit.disable_2fa') }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">{{ __('kit.disable_2fa_body') }}</p>

                        <div class="mt-4 grid gap-3">
                            <x-password label="{{ __('kit.current_password') }}" wire:model.blur="disable_password" autocomplete="current-password" />
                            <x-button type="submit" negative label="{{ __('kit.disable_2fa') }}" spinner="disableTwoFactor" />
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <div class="kit-panel rounded-lg p-6">
            <div>
                <p class="text-sm font-bold uppercase text-slate-400">{{ __('kit.active_sessions') }}</p>
                <h2 class="mt-2 text-xl font-bold text-white">{{ __('kit.active_sessions') }}</h2>
            </div>

            <div class="mt-5 grid gap-3">
                @forelse ($sessions as $session)
                    <div class="rounded-lg border border-white/10 bg-white/5 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-white">{{ $session['ip_address'] ?: __('kit.unknown_ip') }}</p>
                            @if ($session['is_current_device'])
                                <span class="rounded-full bg-cyan-300/10 px-2 py-1 text-xs font-bold text-cyan-100">{{ __('kit.current') }}</span>
                            @endif
                        </div>
                        <p class="mt-1 line-clamp-2 text-xs text-slate-400">{{ $session['user_agent'] ?: __('kit.unknown_agent') }}</p>
                        <p class="mt-2 text-xs text-slate-500">{{ \Carbon\Carbon::createFromTimestamp($session['last_active'])->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">{{ __('kit.sessions_empty') }}</p>
                @endforelse
            </div>

            <form wire:submit="logoutOtherSessions" class="mt-5 grid gap-3">
                <x-password label="{{ __('kit.current_password') }}" wire:model.blur="sessions_password" autocomplete="current-password" />
                <x-button type="submit" secondary label="{{ __('kit.close_other_sessions') }}" spinner="logoutOtherSessions" />
            </form>
        </div>

        <form wire:submit="deleteAccount" class="kit-panel rounded-lg border-red-400/20 p-6">
            <div>
                <p class="text-sm font-bold uppercase text-red-200">{{ __('kit.risk_zone') }}</p>
                <h2 class="mt-2 text-xl font-bold text-white">{{ __('kit.delete_account') }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">
                    {{ __('kit.delete_account_body') }}
                </p>
            </div>

            <div class="mt-5 grid gap-3">
                <x-password label="{{ __('kit.current_password') }}" wire:model.blur="delete_password" autocomplete="current-password" />
                <x-button type="submit" negative label="{{ __('kit.delete_account') }}" spinner="deleteAccount" />
            </div>
        </form>
    </div>
</section>
