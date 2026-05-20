<?php

namespace App\Livewire\Settings;

use App\Support\Auth\TwoFactorAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $two_factor_code = '';

    public string $recovery_password = '';

    public string $disable_password = '';

    public string $sessions_password = '';

    public string $delete_password = '';

    public array $recoveryCodes = [];

    public array $sessions = [];

    public ?string $qrCodeSvg = null;

    public ?string $twoFactorSecret = null;

    public bool $showTwoFactorSetup = false;

    public function mount(): void
    {
        $user = request()->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->recoveryCodes = [];
        $this->loadSessions();
    }

    public function updateProfile(): void
    {
        $user = request()->user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.$user::class.',email,'.$user->getKey()],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        session()->flash('status', 'profile-updated');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        request()->user()->update([
            'password' => $this->password,
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('status', 'password-updated');
    }

    public function enableTwoFactor(TwoFactorAuthentication $twoFactor): void
    {
        abort_unless(config('servicios-linea-once.auth.two_factor.enabled', true), 404);

        if (request()->user()->hasEnabledTwoFactorAuthentication()) {
            session()->flash('status', 'two-factor-already-enabled');

            return;
        }

        $this->twoFactorSecret = $twoFactor->generateSecret();
        $this->qrCodeSvg = $twoFactor->qrCodeSvg(request()->user(), $this->twoFactorSecret);
        $this->showTwoFactorSetup = true;
    }

    public function confirmTwoFactor(TwoFactorAuthentication $twoFactor): void
    {
        $this->validate([
            'two_factor_code' => ['required', 'string'],
        ]);

        if (! $this->twoFactorSecret) {
            throw ValidationException::withMessages([
                'two_factor_code' => __('The provided two-factor authentication code was invalid.'),
            ]);
        }

        if (! $twoFactor->verify($this->twoFactorSecret, $this->two_factor_code)) {
            throw ValidationException::withMessages([
                'two_factor_code' => __('The provided two-factor authentication code was invalid.'),
            ]);
        }

        $recoveryCodes = $twoFactor->generateRecoveryCodes();

        request()->user()->forceFill([
            'two_factor_secret' => Crypt::encryptString($this->twoFactorSecret),
            'two_factor_recovery_codes' => $twoFactor->encryptRecoveryCodes($recoveryCodes),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->recoveryCodes = $recoveryCodes;
        $this->two_factor_code = '';
        $this->twoFactorSecret = null;
        $this->showTwoFactorSetup = false;

        session()->flash('status', 'two-factor-enabled');
        session()->flash('recovery_codes', $recoveryCodes);
    }

    public function regenerateRecoveryCodes(TwoFactorAuthentication $twoFactor): void
    {
        abort_unless(request()->user()->hasEnabledTwoFactorAuthentication(), 404);

        $this->validate([
            'recovery_password' => ['required', 'current_password'],
        ]);

        $codes = $twoFactor->generateRecoveryCodes();

        request()->user()->forceFill([
            'two_factor_recovery_codes' => $twoFactor->encryptRecoveryCodes($codes),
        ])->save();

        $this->recoveryCodes = $codes;
        $this->recovery_password = '';
        session()->flash('recovery_codes', $codes);
    }

    public function disableTwoFactor(): void
    {
        abort_unless(request()->user()->hasEnabledTwoFactorAuthentication(), 404);

        $this->validate([
            'disable_password' => ['required', 'current_password'],
        ]);

        request()->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->recoveryCodes = [];
        $this->disable_password = '';
        $this->showTwoFactorSetup = false;
        $this->twoFactorSecret = null;
        $this->qrCodeSvg = null;

        session()->flash('status', 'two-factor-disabled');
    }

    public function logoutOtherSessions(): void
    {
        $this->validate([
            'sessions_password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($this->sessions_password);

        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', request()->user()->getAuthIdentifier())
                ->where('id', '!=', request()->session()->getId())
                ->delete();
        }

        $this->sessions_password = '';
        $this->loadSessions();
        session()->flash('status', 'sessions-logged-out');
    }

    public function deleteAccount()
    {
        $this->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = request()->user();

        Auth::logout();
        $user->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function loadSessions(): void
    {
        $this->sessions = $this->sessions();
    }

    public function sessions(): array
    {
        if (config('session.driver') !== 'database') {
            return [];
        }

        return collect(DB::table(config('session.table', 'sessions'))
            ->where('user_id', request()->user()->getAuthIdentifier())
            ->orderByDesc('last_activity')
            ->get())
            ->map(fn ($session): array => [
                'id' => $session->id,
                'ip_address' => $session->ip_address,
                'user_agent' => $session->user_agent,
                'last_active' => (int) $session->last_activity,
                'is_current_device' => $session->id === request()->session()->getId(),
            ])
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.settings.profile', [
            'mustVerifyEmail' => true,
            'status' => session('status'),
            'sessions' => $this->sessions(),
            'recoveryCodes' => $this->recoveryCodes,
            'showTwoFactorSetup' => $this->showTwoFactorSetup,
            'qrCodeSvg' => $this->qrCodeSvg,
        ])->layout('layouts.app', ['title' => __('kit.profile_security')]);
    }
}
