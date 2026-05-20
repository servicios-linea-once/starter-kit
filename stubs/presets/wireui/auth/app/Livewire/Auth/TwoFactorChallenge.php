<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Support\Auth\TwoFactorAuthentication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    public string $code = '';

    public string $recovery_code = '';

    public bool $useRecoveryCode = false;

    public function mount()
    {
        if (! session()->has('login.id')) {
            return redirect()->route('login');
        }

        return null;
    }

    public function verify(TwoFactorAuthentication $twoFactor)
    {
        $this->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $user = User::findOrFail(session('login.id'));
        $secret = $user->getTwoFactorSecret();
        $valid = false;

        if ($this->code !== '' && $secret) {
            $valid = $twoFactor->verify($secret, $this->code);
        }

        if ($this->recovery_code !== '') {
            $valid = $twoFactor->consumeRecoveryCode($user, $this->recovery_code);
        }

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => __('The provided two-factor authentication code was invalid.'),
            ]);
        }

        Auth::login($user, (bool) session()->pull('login.remember', false));
        session()->forget('login.id');
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function cancel()
    {
        session()->forget(['login.id', 'login.remember']);
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')
            ->layout('layouts.guest', ['title' => __('kit.two_factor_title')]);
    }
}
