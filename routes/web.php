<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('test');
});

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// User
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/my-tickets', function () {
        return view('livewire.user.my-tickets'); 
    })->name('user.my-tickets');

       Route::get('/user/crear-ticket', function () {
        return view('livewire.user.crear-ticket'); 
    })->name('user.crear-ticket');
    Route::get('/user/tickets/{ticket}', function ($ticket) {
        return view('livewire.user.editar-ticket', ['ticketId' => $ticket]);
    })->name('user.tickets.show');
});

// Agente
Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/assigned-tickets', function () {
        return view('livewire.agent.assigned-tickets'); 
    })->name('agent.assigned-tickets');
       Route::get('/agent/tickets/{ticket}', function ($ticket) {
        return view('livewire.agent.detalle-ticket', ['ticketId' => $ticket]);
    })->name('agent.tickets.show');
});


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');