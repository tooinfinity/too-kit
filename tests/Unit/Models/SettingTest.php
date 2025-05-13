<?php

declare(strict_types=1);

use App\Models\Setting;

test('to array', function () {
    $setting = Setting::factory()->create()->refresh();

    expect(array_keys($setting->toArray()))->toBe([
        'id',
        'key',
        'value',
        'created_at',
        'updated_at',
    ]);
});
