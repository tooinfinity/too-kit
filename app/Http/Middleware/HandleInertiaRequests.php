<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Override;
use Tighten\Ziggy\Ziggy;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    #[Override]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function share(Request $request): array
    {

        /** @var array<string, array<string, mixed>>|null $supportedLocales */
        $supportedLocales = config('laravellocalization.supportedLocales');

        $currentLocale = LaravelLocalization::getCurrentLocale();

        $locales = collect($supportedLocales ?? [])
            ->map(function (array $locale, string $code): array {
                $url = LaravelLocalization::getLocalizedURL($code, null, [], true);

                return [
                    'code' => $code,
                    'name' => $locale['native'],
                    'url' => $url !== false ? (string) $url : '',
                ];
            })
            ->keyBy('code')
            ->toArray();

        $translations = [];
        $translationFiles = ['messages', 'dashboard', 'auth', 'pagination', 'passwords', 'validation'];

        foreach ($translationFiles as $file) {
            $translations[$file] = trans($file, [], $currentLocale);
        }
        /** @var array<string, mixed> $shared */
        $shared = [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user() ? new AuthResource($request->user()) : null,
            ],
            'locale' => $currentLocale,
            'locales' => $locales,
            'translations' => $translations,
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'warning' => $request->session()->get('warning'),
                'info' => $request->session()->get('info'),
            ],
            'ziggy' => fn (): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];

        return $shared;
    }
}
