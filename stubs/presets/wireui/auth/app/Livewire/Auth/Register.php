<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        abort_unless(config('servicios-linea-once.auth.registration', true), 404);
    }

    public function register()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create($validated);

        event(new Registered($user));

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.guest', ['title' => __('kit.register')]);
    }
}
