<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class VerifyEmail extends Component
{
    public function mount()
    {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return null;
    }

    public function resend()
    {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        request()->user()->sendEmailVerificationNotification();
        session()->flash('status', 'verification-link-sent');

        return null;
    }

    public function render()
    {
        return view('livewire.auth.verify-email', [
            'status' => session('status'),
        ])->layout('layouts.guest', ['title' => __('kit.verify_title')]);
    }
}
