<?php

namespace App\Support\Auth;

use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthentication
{
    public function __construct(private readonly Google2FA $google2fa)
    {
    }

    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function qrCodeSvg(User $user, string $secret): string
    {
        $uri = $this->google2fa->getQRCodeUrl(
            config('servicios-linea-once.auth.two_factor.issuer'),
            $user->email,
            $secret
        );

        return (new Writer(
            new ImageRenderer(new RendererStyle(220), new SvgImageBackEnd)
        ))->writeString($uri);
    }

    public function verify(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey(
            $secret,
            preg_replace('/\s+/', '', $code),
            (int) config('servicios-linea-once.auth.two_factor.window', 1)
        );
    }

    public function currentOtp(string $secret): string
    {
        return $this->google2fa->getCurrentOtp($secret);
    }

    /**
     * @return array<int, string>
     */
    public function generateRecoveryCodes(): array
    {
        return collect(range(1, (int) config('servicios-linea-once.auth.two_factor.recovery_codes', 8)))
            ->map(fn (): string => Str::upper(Str::random(5).'-'.Str::random(5)))
            ->all();
    }

    /**
     * @param  array<int, string>  $codes
     */
    public function encryptRecoveryCodes(array $codes): string
    {
        return Crypt::encryptString(json_encode(array_map(
            fn (string $code): string => hash('sha256', $this->normalizeRecoveryCode($code)),
            $codes
        )));
    }

    public function consumeRecoveryCode(User $user, string $code): bool
    {
        $hash = hash('sha256', $this->normalizeRecoveryCode($code));
        $codes = $user->getRecoveryCodes();

        if (! in_array($hash, $codes, true)) {
            return false;
        }

        $user->forceFill([
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode(array_values(array_diff($codes, [$hash])))),
        ])->save();

        return true;
    }

    private function normalizeRecoveryCode(string $code): string
    {
        return Str::upper(str_replace(' ', '', trim($code)));
    }
}
