<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TwoFactorChallengeRequest;
use App\Models\User;
use App\Support\Auth\TwoFactorAuthentication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorAuthenticatedSessionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (! session()->has('login.id')) {
            return redirect()->route('login');
        }

        return view('livewire.auth.two-factor-challenge');
    }

    public function store(TwoFactorChallengeRequest $request, TwoFactorAuthentication $twoFactor): RedirectResponse
    {
        $user = User::findOrFail($request->session()->get('login.id'));
        $secret = $user->getTwoFactorSecret();

        $valid = false;

        if ($request->filled('code') && $secret) {
            $valid = $twoFactor->verify($secret, $request->string('code')->toString());
        }

        if ($request->filled('recovery_code')) {
            $valid = $twoFactor->consumeRecoveryCode($user, $request->string('recovery_code')->toString());
        }

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => __('The provided two-factor authentication code was invalid.'),
            ]);
        }

        Auth::login($user, (bool) $request->session()->pull('login.remember', false));
        $request->session()->forget('login.id');
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget(['login.id', 'login.remember']);
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
