<?php

use Illuminate\Support\Facades\Route;

// The lab has no public marketing page: the root is the sign-in screen.
// Visitors who already have a session go straight to the dashboard.
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Design lab — component catalog
    Route::livewire('components/forms', 'pages::components.forms')->name('components.forms');
    Route::livewire('components/actions', 'pages::components.actions')->name('components.actions');
    Route::livewire('components/data', 'pages::components.data')->name('components.data');
    Route::livewire('components/overlays', 'pages::components.overlays')->name('components.overlays');

    // Design lab — tokens and patterns
    Route::livewire('theme', 'pages::theme')->name('theme');
    Route::livewire('layouts', 'pages::layouts')->name('layouts');
});

require __DIR__.'/settings.php';
