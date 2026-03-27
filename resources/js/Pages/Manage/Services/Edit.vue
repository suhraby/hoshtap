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

                <div
                    class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800"
                >
                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <div v-for="locale in locales" :key="locale.code">
                            <InputLabel
                                :for="'description-' + locale.code"
                                required
                            >
                                {{ $t('Description') }} ({{ locale.label }})
                            </InputLabel>
                            <InputField
                                :id="'description-' + locale.code"
                                type="text"
                                v-model="form.description[locale.code]"
                                :multiline="true"
                                :error="
                                    form.errors[`description.${locale.code}`]
                                "
                                :placeholder="`${$t('Service description')} — ${locale.label}`"
                            />
                            <InputError
                                :message="
                                    form.errors[`description.${locale.code}`]
                                "
                            />
                        </div>
                    </div>
                </div>

                <div class="icon-field">
                    <InputLabel for="icon" :value="$t('Icon')" />
                    <InputField
                        id="icon"
                        type="text"
                        name="icon"
                        :multiline="true"
                        v-model="form.icon"
                        :error="form.errors.icon"
                        :placeholder="$t('Enter the icon')"
                        autocomplete="icon"
                    />
                    <InputError :message="form.errors.icon" />
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <Link
                        :href="route('manage.services.index')"
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
import type { ResourceResponse, Service } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const { locales } = useLocales();
const { lang } = useLocale();

const props = defineProps<{
    service: ResourceResponse<Service>;
}>();

const currentPageTitle = computed(() =>
    trans('Edit service: :title', {
        title:
            props.service.data.title[lang.value] ??
            props.service.data.title['en'],
    }),
);

const form = useForm({
    title: Object.fromEntries(
        locales.value.map((l) => [
            l.code,
            props.service.data.title[l.code] ?? '',
        ]),
    ) as Record<string, string>,
    description: Object.fromEntries(
        locales.value.map((l) => [
            l.code,
            props.service.data.description[l.code] ?? '',
        ]),
    ) as Record<string, string>,
    icon: props.service.data.icon as string,
});

function onSubmit(): void {
    form.patch(route('manage.services.update', props.service.data.id), {
        preserveScroll: true,
    });
}
</script>

<style scoped>
.icon-field textarea {
    height: 180px;
}
</style>
