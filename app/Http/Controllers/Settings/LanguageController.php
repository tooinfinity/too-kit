<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Settings\UpdateLanguageAction;
use App\Http\Requests\Settings\LanguageUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

final class LanguageController
{
    /**
     * Update the user's language settings.
     */
    public function update(LanguageUpdateRequest $request, UpdateLanguageAction $action): RedirectResponse
    {
        $action->handle($request->validated());
        $locale = $request->string('locale')->value();
        $localizedUrl = LaravelLocalization::getLocalizedURL($locale, route('language'));

        return redirect($localizedUrl !== false ? $localizedUrl : route('language'));
    }
}
