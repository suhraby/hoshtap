<template>
    <Head :title="String(currentPageTitle)" />

    <AdminLayout>
        <PageBreadcrumb :page-title="String(currentPageTitle)" />

        <ComponentCard>
            <form @submit.prevent="onSubmit">
                <div
                    class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800"
                >
                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <div v-for="locale in locales" :key="locale.code">
                            <InputLabel :for="'title-' + locale.code" required>
                                {{ $t('Title') }} ({{ locale.label }})
                            </InputLabel>
                            <InputField
                                :id="'title-' + locale.code"
                                type="text"
                                v-model="form.title[locale.code]"
                                :error="form.errors[`title.${locale.code}`]"
                                :placeholder="`${$t('Counter title')} — ${locale.label}`"
                            />
                            <InputError
                                :message="form.errors[`title.${locale.code}`]"
                            />
                        </div>
                    </div>
                </div>

                <div class="space-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <InputLabel
                            for="number"
                            :value="$t('Number')"
                            required
                        />
                        <InputField
                            id="number"
                            type="number"
                            name="number"
                            required
                            v-model="form.number"
                            :error="form.errors.number"
                            :placeholder="$t('Enter counter number')"
                            autocomplete="number"
                            min="0"
                        />
                        <InputError :message="form.errors.number" />
                    </div>

                    <div>
                        <InputLabel
                            for="symbol"
                            :value="$t('Symbol after number')"
                        />
                        <InputField
                            id="symbol"
                            type="text"
                            name="symbol"
                            v-model="form.symbol"
                            :error="form.errors.symbol"
                            :placeholder="$t('Enter counter symbol')"
                            autocomplete="symbol"
                        />
                        <InputError :message="form.errors.symbol" />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <Link
                        :href="route('manage.banners.index')"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5"
                    >
                        {{ $t('Cancel') }}
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-brand-500 hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            form.processing ? $t('Saving') + '...' : $t('Save')
                        }}
                    </button>
                </div>
            </form>
        </ComponentCard>
    </AdminLayout>
</template>

<script setup lang="ts">
import ComponentCard from '@/Components/manage/common/ComponentCard.vue';
import PageBreadcrumb from '@/Components/manage/common/PageBreadcrumb.vue';
import InputError from '@/Components/manage/forms/InputError.vue';
import InputField from '@/Components/manage/forms/InputField.vue';
import InputLabel from '@/Components/manage/forms/InputLabel.vue';
import { useLocale, useLocales } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import type { Counter, ResourceResponse } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const { locales } = useLocales();
const { lang } = useLocale();

const props = defineProps<{
    counter: ResourceResponse<Counter>;
}>();

const currentPageTitle = computed(() =>
    trans('Edit counter: :title', {
        title:
            props.counter.data.title[lang.value] ??
            props.counter.data.title['en'],
    }),
);

const form = useForm({
    title: Object.fromEntries(
        locales.value.map((l) => [
            l.code,
            props.counter.data.title[l.code] ?? '',
        ]),
    ) as Record<string, string>,
    number: props.counter.data.number as number,
    symbol: props.counter.data.symbol as string | null,
});

function onSubmit(): void {
    form.patch(route('manage.counters.update', props.counter.data.id), {
        preserveScroll: true,
    });
}
</script>
