<?php

declare(strict_types=1);

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->request = new LoginRequest();
    $this->request->merge([
        'email' => $this->user->email,
        'password' => 'password',
    ]);
});

test('it generates correct throttle key', function () {
    $request = new LoginRequest();
    $request->merge(['email' => 'test@example.com']);
    $request->setUserResolver(fn () => null);

    $key = $request->throttleKey();

    expect($key)->toBe('test@example.com|'.$request->ip());
});

test('it enforces rate limiting', function () {
    RateLimiter::shouldReceive('tooManyAttempts')->andReturn(true);
    RateLimiter::shouldReceive('availableIn')->andReturn(60);

    try {
        $this->request->ensureIsNotRateLimited();
        $this->fail('Expected ValidationException was not thrown');
    } catch (ValidationException $e) {
        expect($e->errors()['email'][0])->toContain('Too many login attempts');
    }
});

test('it clears rate limit on successful authentication', function () {
    RateLimiter::shouldReceive('clear')->once();
    RateLimiter::shouldReceive('tooManyAttempts')->andReturn(false);

    // Simulate successful authentication
    $this->request->setUserResolver(fn () => $this->user);
    $this->request->merge(['password' => 'password']);

    // Simulate the authentication logic
    $this->request->authenticate();
});
