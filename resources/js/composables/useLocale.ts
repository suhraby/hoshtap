import type { AppLocale, LocaleKey } from '@/types/index';
import { usePage } from '@inertiajs/vue3';
import { getActiveLanguage } from 'laravel-vue-i18n';
import { computed, ref } from 'vue';

const lang = ref<LocaleKey>(getActiveLanguage() as LocaleKey);

export function useLocale() {
    const setLang = (code: LocaleKey) => {
        lang.value = code;
    };

    return { lang, setLang };
}

export function useLocales() {
    const page = usePage();
    const locales = computed(() => page.props.locales as AppLocale[]);
    return { locales };
}

export function useTranslatable() {
    function t(value: unknown): string {
        if (!value || typeof value !== 'object') {
            return String(value ?? '—');
        }
        const translations = value as Record<string, string>;

        return (
            translations[lang.value] ??
            translations['en'] ??
            Object.values(translations)[0] ??
            '—'
        );
    }

    return { t };
}
