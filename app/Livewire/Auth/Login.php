<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
        'remember' => 'boolean',
    ];
    public function login()
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'El correo electrónico o la contraseña son incorrectos.',
            ]);
        }

        if (Auth::user()->hasRole('agent')) {
            return redirect()->intended(route('agent.assigned-tickets'));
        } elseif (Auth::user()->hasRole('user')) {
            return redirect()->intended(route('user.my-tickets'));
        }

        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
