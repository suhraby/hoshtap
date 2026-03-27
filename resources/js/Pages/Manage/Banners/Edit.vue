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
                                :placeholder="`${$t('Banner title')} — ${locale.label}`"
                            />
                            <InputError
                                :message="form.errors[`title.${locale.code}`]"
                            />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <InputLabel :value="$t('Banner image')" />

                        <div v-if="banner.data.thumbnail" class="mb-3">
                            <img
                                :src="banner.data.thumbnail"
                                class="h-24 w-36 rounded-lg object-cover"
                                alt="current banner"
                            />
                            <p
                                class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                            >
                                {{
                                    $t(
                                        'Current image — upload a new one to replace it',
                                    )
                                }}
                            </p>
                        </div>

                        <FileInput
                            accept="image/*"
                            :error="form.errors.file"
                            @change="form.file = $event"
                        />
                        <InputError :message="form.errors.file" />
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
import FileInput from '@/Components/manage/forms/FileInput.vue';
import InputError from '@/Components/manage/forms/InputError.vue';
import InputField from '@/Components/manage/forms/InputField.vue';
import InputLabel from '@/Components/manage/forms/InputLabel.vue';
import { useLocale, useLocales } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import type { Banner, ResourceResponse } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const { locales } = useLocales();
const { lang } = useLocale();

const props = defineProps<{
    banner: ResourceResponse<Banner>;
}>();

const currentPageTitle = computed(() =>
    trans('Edit banner: :title', {
        title:
            props.banner.data.title[lang.value] ??
            props.banner.data.title['en'],
    }),
);

const form = useForm({
    title: Object.fromEntries(
        locales.value.map((l) => [
            l.code,
            props.banner.data.title[l.code] ?? '',
        ]),
    ) as Record<string, string>,
    file: null as File | null,
});

function onSubmit(): void {
    form.patch(route('manage.banners.update', props.banner.data.id), {
        preserveScroll: true,
    });
}
</script>
