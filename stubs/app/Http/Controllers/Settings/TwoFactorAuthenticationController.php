<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Support\Auth\TwoFactorAuthentication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticationController extends Controller
{
    public function store(Request $request, TwoFactorAuthentication $twoFactor): RedirectResponse
    {
        abort_unless(config('servicios-linea-once.auth.two_factor.enabled', true), 404);

        $secret = $twoFactor->generateSecret();

        $request->session()->put('two_factor.secret', $secret);

        return back()->with('two_factor_setup', [
            'qr_code_svg' => $twoFactor->qrCodeSvg($request->user(), $secret),
            'secret' => $secret,
        ]);
    }

    public function confirm(Request $request, TwoFactorAuthentication $twoFactor): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $secret = $request->session()->get('two_factor.secret');

        if (! $secret || ! $twoFactor->verify($secret, $request->string('code')->toString())) {
            throw ValidationException::withMessages([
                'code' => __('The provided two-factor authentication code was invalid.'),
            ]);
        }

        $recoveryCodes = $twoFactor->generateRecoveryCodes();

        $request->user()->forceFill([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_recovery_codes' => $twoFactor->encryptRecoveryCodes($recoveryCodes),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('two_factor.secret');

        return back()->with('status', 'two-factor-enabled')->with('recovery_codes', $recoveryCodes);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $request->session()->forget('two_factor.secret');

        return back()->with('status', 'two-factor-disabled');
    }
}
