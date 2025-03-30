import { type SharedData } from '@/types';
import { router, usePage } from '@inertiajs/react';
import { useCallback, useEffect, useState } from 'react';

export type Locale = string;

const applyLanguage = (locale: Locale) => {
    router.post(
        route('language.update'),
        { locale: locale },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                // Refresh the page to apply new locale
                window.location.reload();
            },
        },
    );
};
export function useTranslation() {
    const { locale: pageLocale, translations, locales } = usePage<SharedData>().props;
    const [locale, setLocale] = useState<Locale>(() => {
        return pageLocale || 'en';
    });

    const switchLocale = useCallback(
        (newLocale: Locale) => {
            if (newLocale === locale) return;

            if (locales && locales[newLocale]) {
                // Store in localStorage for persistence
                localStorage.setItem('locale', newLocale);

                if (locales[newLocale].url) {
                    window.location.href = locales[newLocale].url;
                    return;
                }

                // Update server-side locale
                applyLanguage(newLocale);

                setLocale(newLocale);
            }
        },
        [locale, locales],
    );

    const __ = useCallback(
        (key: string, replacements: Record<string, string | number> = {}) => {
            const keys = key.split('.');

            if (keys.length === 1) {
                for (const fileKey in translations) {
                    const fileTranslations = translations[fileKey];
                    if (fileTranslations && typeof fileTranslations === 'object' && key in fileTranslations) {
                        const translation = fileTranslations[key];
                        if (typeof translation === 'string') {
                            let result = translation;
                            Object.entries(replacements).forEach(([placeholder, value]) => {
                                result = result.replace(new RegExp(`:${placeholder}`, 'g'), String(value));
                            });
                            return result;
                        }
                    }
                }
                return key;
            }

            const [file, ...restKeys] = keys;

            const fileTranslations = translations[file];
            if (!fileTranslations || typeof fileTranslations !== 'object') {
                return key;
            }

            let translation = fileTranslations as Record<string, unknown>;
            for (const k of restKeys) {
                if (!translation || typeof translation !== 'object') {
                    return key;
                }
                translation = translation[k] as Record<string, unknown>;
            }

            if (!translation || typeof translation !== 'string') {
                return key;
            }

            let result = translation as string;
            Object.entries(replacements).forEach(([placeholder, value]) => {
                result = result.replace(new RegExp(`:${placeholder}`, 'g'), String(value));
            });

            return result;
        },
        [translations],
    );

    useEffect(() => {
        const savedLocale = localStorage.getItem('locale') as Locale;
        if (savedLocale && savedLocale !== locale) {
            switchLocale(savedLocale);
        }
    }, [locale, switchLocale]);

    return { locale, switchLocale, locales, __ };
}
