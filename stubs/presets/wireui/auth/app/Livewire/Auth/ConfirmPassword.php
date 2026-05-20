<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ConfirmPassword extends Component
{
    public string $password = '';

    public function confirm()
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($this->password, request()->user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ]);
        }

        request()->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function render()
    {
        return view('livewire.auth.confirm-password')
            ->layout('layouts.guest', ['title' => __('kit.confirm_title')]);
    }
}
