<?php

declare(strict_types=1);

use App\Models\User;

test('language settings page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/settings/language');

    $response->assertOk();
});

test('language locale can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/settings/language')
        ->post('/settings/language', [
            'locale' => 'fr',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/fr/settings/language');

    expect(session('locale'))->toBe('fr');
});
