<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Support\Auth\TwoFactorAuthentication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecoveryCodeController extends Controller
{
    public function store(Request $request, TwoFactorAuthentication $twoFactor): RedirectResponse
    {
        abort_unless($request->user()->hasEnabledTwoFactorAuthentication(), 404);

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $codes = $twoFactor->generateRecoveryCodes();

        $request->user()->forceFill([
            'two_factor_recovery_codes' => $twoFactor->encryptRecoveryCodes($codes),
        ])->save();

        return back()->with('recovery_codes', $codes);
    }
}
