<template>
    <Head :title="currentPageTitle" />

    <AdminLayout>
        <PageBreadcrumb :page-title="currentPageTitle" />

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
                                :placeholder="`${$t('Title')} — ${locale.label}`"
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
                                :multiline="true"
                                v-model="form.body[locale.code]"
                                :error="form.errors[`body.${locale.code}`]"
                                :placeholder="`${$t('About us description')} — ${locale.label}`"
                            />
                            <InputError
                                :message="form.errors[`body.${locale.code}`]"
                            />
                        </div>
                    </div>
                </div>

                <div
                    class="t-inputs mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800"
                >
                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <div v-for="locale in locales" :key="locale.code">
                            <InputLabel
                                :for="'context-' + locale.code"
                                required
                            >
                                {{ $t('Context') }} ({{ locale.label }})
                            </InputLabel>
                            <InputField
                                :id="'context-' + locale.code"
                                type="text"
                                :multiline="true"
                                v-model="form.context[locale.code]"
                                :error="form.errors[`context.${locale.code}`]"
                                :placeholder="`${$t('About us context')} — ${locale.label}`"
                            />
                            <InputError
                                :message="form.errors[`context.${locale.code}`]"
                            />
                        </div>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <InputLabel
                            for="file"
                            :value="$t('About us image')"
                            required
                        />
                        <FileInput
                            id="file"
                            accept="image/*"
                            :error="form.errors.file"
                            @change="form.file = $event"
                        />
                        <InputError :message="form.errors.file" />
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
                                :for="'market-title-' + locale.code"
                                required
                            >
                                {{ $t('Market title') }} ({{ locale.label }})
                            </InputLabel>
                            <InputField
                                :id="'market-title-' + locale.code"
                                type="text"
                                v-model="form.market_title[locale.code]"
                                :error="
                                    form.errors[`market_title.${locale.code}`]
                                "
                                :placeholder="`${$t('Market title')} — ${locale.label}`"
                            />
                            <InputError
                                :message="
                                    form.errors[`market_title.${locale.code}`]
                                "
                            />
                        </div>
                    </div>
                </div>

                <div
                    class="t-inputs mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800"
                >
                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <div v-for="locale in locales" :key="locale.code">
                            <InputLabel
                                :for="'product_range-' + locale.code"
                                required
                            >
                                {{ $t('Product range') }} ({{ locale.label }})
                            </InputLabel>
                            <InputField
                                :id="'product_range-' + locale.code"
                                type="text"
                                :multiline="true"
                                v-model="form.product_range[locale.code]"
                                :error="
                                    form.errors[`product_range.${locale.code}`]
                                "
                                :placeholder="`${$t('Product range')} — ${locale.label}`"
                            />
                            <InputError
                                :message="
                                    form.errors[`product_range.${locale.code}`]
                                "
                            />
                        </div>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <InputLabel
                            for="market_file"
                            :value="$t('Market image')"
                            required
                        />
                        <FileInput
                            id="market_file"
                            accept="image/*"
                            :error="form.errors.market_file"
                            @change="form.market_file = $event"
                        />
                        <InputError :message="form.errors.market_file" />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <Link
                        :href="route('manage.about.index')"
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
import { useLocales } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const { locales } = useLocales();

const currentPageTitle = computed(() => trans('Create about us'));

const form = useForm({
    title: Object.fromEntries(locales.value.map((l) => [l.code, ''])) as Record<
        string,
        string
    >,
    market_title: Object.fromEntries(
        locales.value.map((l) => [l.code, '']),
    ) as Record<string, string>,
    body: Object.fromEntries(locales.value.map((l) => [l.code, ''])) as Record<
        string,
        string
    >,
    context: Object.fromEntries(
        locales.value.map((l) => [l.code, '']),
    ) as Record<string, string>,
    product_range: Object.fromEntries(
        locales.value.map((l) => [l.code, '']),
    ) as Record<string, string>,
    file: null as File | null,
    market_file: null as File | null,
});

function onSubmit(): void {
    form.post(route('manage.about.store'), {
        preserveScroll: true,
    });
}
</script>

<style scoped>
.t-inputs textarea {
    height: 120px;
}
</style>
