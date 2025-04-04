<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$routeGroupConfig = app()->environment('testing')
    ? []
    : [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ];

Route::group($routeGroupConfig, function () {

    Route::middleware('check.setup')->group(function () {
        require __DIR__.'/setup.php';
        Route::get('/', function () {
            return Inertia::render('welcome');
        })->name('home');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('dashboard', function () {
                return Inertia::render('dashboard');
            })->name('dashboard');
        });

        require __DIR__.'/settings.php';
        require __DIR__.'/auth.php';
    });
});
