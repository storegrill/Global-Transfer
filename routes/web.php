<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/inbox', function () {
        return Inertia::render('Inbox');
    })->name('inbox');

    Route::get('/card', function () {
        return Inertia::render('Card');
    })->name('card');

    Route::get('/Statistics', function () {
        return Inertia::render('Statistics');
    })->name('statistics');

    Route::get('/Recipients', function () {
        return Inertia::render('Recipients');
    })->name('recipients');

    Route::get('/Transactions', function () {
        return Inertia::render('Transactions');
    })->name('transactions');

    Route::get('/Referral', function () {
        return Inertia::render('Referral');
    })->name('referral');
});

Route::get('/login', function () {
    return Inertia::render('Login');
})->name('login');

Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

require __DIR__.'/auth.php';
