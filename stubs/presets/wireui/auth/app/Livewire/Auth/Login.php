<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function authenticate()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        $user = Auth::user();
        $remember = $this->remember;

        Auth::logout();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            request()->session()->put([
                'login.id' => $user->getKey(),
                'login.remember' => $remember,
            ]);

            return redirect()->route('two-factor.login');
        }

        Auth::login($user, $remember);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function render()
    {
        return view('livewire.auth.login', [
            'canRegister' => (bool) config('servicios-linea-once.auth.registration', true),
            'canResetPassword' => true,
            'status' => session('status'),
        ])->layout('layouts.guest', ['title' => __('kit.login')]);
    }

    private function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('Too many login attempts. Please try again in :seconds seconds.', [
                'seconds' => $seconds,
            ]),
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
