<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

final class UpdateLanguageAction
{
    /**
     * Update the user's language settings.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): void
    {
        /** @var string $locale */
        $locale = $attributes['locale'];
        App::setLocale($locale);
        Session::put('locale', $locale);
    }
}
