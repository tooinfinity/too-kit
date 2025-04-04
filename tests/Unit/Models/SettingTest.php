<?php

declare(strict_types=1);

use App\Models\Setting;

test('to array', function () {
    $user = Setting::factory()->create()->refresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'key',
        'value',
        'created_at',
        'updated_at',
    ]);
});
