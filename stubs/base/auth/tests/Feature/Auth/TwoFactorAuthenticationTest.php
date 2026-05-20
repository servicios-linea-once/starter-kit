<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Support\Auth\TwoFactorAuthentication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_authentication_can_be_enabled(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/settings/two-factor')
            ->assertSessionHas('two_factor_setup');

        $this->assertNotEmpty(session('two_factor.secret'));
    }

    public function test_two_factor_authentication_can_be_confirmed(): void
    {
        $user = User::factory()->create();
        $secret = app(TwoFactorAuthentication::class)->generateSecret();
        $code = app(TwoFactorAuthentication::class)->currentOtp($secret);

        $response = $this->actingAs($user)
            ->withSession([
                'auth.password_confirmed_at' => time(),
                'two_factor.secret' => $secret,
            ])
            ->post('/settings/two-factor/confirm', [
                'code' => $code,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('recovery_codes');

        $user->refresh();

        $this->assertTrue($user->hasEnabledTwoFactorAuthentication());
        $this->assertSame($secret, $user->getTwoFactorSecret());
    }

    public function test_two_factor_challenge_accepts_valid_totp_code(): void
    {
        $secret = app(TwoFactorAuthentication::class)->generateSecret();
        $user = User::factory()->create([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_recovery_codes' => app(TwoFactorAuthentication::class)->encryptRecoveryCodes(['ABCDE-FGHIJ']),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('two-factor.login', absolute: false));

        $this->post('/two-factor-challenge', [
            'code' => app(TwoFactorAuthentication::class)->currentOtp($secret),
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_two_factor_challenge_accepts_recovery_code_once(): void
    {
        $secret = app(TwoFactorAuthentication::class)->generateSecret();
        $user = User::factory()->create([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_recovery_codes' => app(TwoFactorAuthentication::class)->encryptRecoveryCodes(['ABCDE-FGHIJ']),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->post('/two-factor-challenge', [
            'recovery_code' => 'ABCDE-FGHIJ',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
        $this->assertCount(0, $user->fresh()->getRecoveryCodes());
    }

    public function test_two_factor_authentication_can_be_disabled(): void
    {
        $user = User::factory()->create([
            'two_factor_secret' => Crypt::encryptString('secret-key'),
            'two_factor_recovery_codes' => app(TwoFactorAuthentication::class)->encryptRecoveryCodes(['ABCDE-FGHIJ']),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)->delete('/settings/two-factor', [
            'password' => 'password',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertFalse($user->fresh()->hasEnabledTwoFactorAuthentication());
    }
}
