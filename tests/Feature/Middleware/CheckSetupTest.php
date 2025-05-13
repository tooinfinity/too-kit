<?php

declare(strict_types=1);

use App\Http\Middleware\CheckSetup;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

test('setup page cannot be accessed when setup is completed', function () {

    app()->detectEnvironment(function () {
        return 'production';
    });

    Setting::query()->delete();
    Setting::markSetupCompleted();

    expect(Setting::setupCompleted())->toBeTrue();

    $response = $this->get(route('setup.index'));
    $response->assertRedirect(route('login'));

    app()->detectEnvironment(function () {
        return 'testing';
    });
});

test('non-setup pages redirect to setup when setup is not completed', function () {
    Route::get('/test-route', function () {
        return 'Test Route';
    })->middleware(CheckSetup::class);

    app()->detectEnvironment(function () {
        return 'production';
    });

    Setting::query()->delete();

    expect(Setting::setupCompleted())->toBeFalse();

    $response = $this->get('/test-route');

    $response->assertRedirect(route('setup.index'));

    app()->detectEnvironment(function () {
        return 'testing';
    });
});

test('non-setup pages can be accessed when setup is completed', function () {
    Route::get('/dashboard', function () {
        return 'Test Route';
    })->middleware(CheckSetup::class);

    app()->detectEnvironment(function () {
        return 'production';
    });

    Setting::query()->delete();
    Setting::markSetupCompleted();

    expect(Setting::setupCompleted())->toBeTrue();

    $response = $this->get('/dashboard');
    $response->assertStatus(200);

    app()->detectEnvironment(function () {
        return 'testing';
    });
});
