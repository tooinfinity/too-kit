<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $key
 * @property-read string $value
 * @property-read CarbonImmutable$created_at
 * @property-read CarbonImmutable $updated_at
 */
final class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    public static function getValue(string $key, string $default = 'false'): string
    {
        $setting = self::query()->where('key', $key)->first();

        return $setting->value ?? $default;
    }

    public static function setValue(string $key, string $value): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function setupCompleted(): bool
    {
        $isSetupCompleted = self::getValue('setup_completed');

        return $isSetupCompleted === 'true';
    }

    public static function markSetupCompleted(): void
    {
        self::setValue('setup_completed', 'true');
    }
}
