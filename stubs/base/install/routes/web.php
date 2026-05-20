<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::post('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['es', 'en'], true), 404);

    session(['locale' => $locale]);
    app()->setLocale($locale);

    return request()->expectsJson() ? response()->noContent() : back();
})->name('locale.update');

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
