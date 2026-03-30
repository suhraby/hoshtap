<template>
    <Head :title="String(currentPageTitle)" />

    <AdminLayout>
        <PageBreadcrumb :page-title="String(currentPageTitle)" />

        <ComponentCard>
            <form @submit.prevent="onSubmit">
                <div
                    v-if="contact.data.slug === 'address'"
                    class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800"
                >
                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <div v-for="locale in locales" :key="locale.code">
                            <InputLabel
                                :for="'locale-value-' + locale.code"
                                required
                            >
                                {{ $t('Value') }} ({{ locale.label }})
                            </InputLabel>
                            <InputField
                                :id="'locale-value-' + locale.code"
                                type="text"
                                v-model="form.locale_value[locale.code]"
                                :error="
                                    form.errors[`locale_value.${locale.code}`]
                                "
                                :placeholder="`${$t('Contact value')} — ${locale.label}`"
                            />
                            <InputError
                                :message="
                                    form.errors[`locale_value.${locale.code}`]
                                "
                            />
                        </div>
                    </div>
                </div>

                <div v-else class="mb-6">
                    <InputLabel for="value" :value="$t('Value')" />
                    <InputField
                        id="value"
                        type="text"
                        name="value"
                        v-model="form.value"
                        :error="form.errors.value"
                        :placeholder="$t('Enter the contact value')"
                        autocomplete="value"
                    />
                    <InputError :message="form.errors.value" />
                </div>

                <div
                    v-if="
                        contact.data.slug === 'instagram' ||
                        contact.data.slug === 'tiktok'
                    "
                    class="icon-field"
                >
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

                <div class="flex items-center justify-between">
                    <div>
                        <Checkbox
                            name="is_active"
                            v-model:checked="form.is_active"
                        >
                            <span>{{ $t('Keep as active contact') }}</span>
                        </Checkbox>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <Link
                        :href="route('manage.contacts.index')"
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
import Checkbox from '@/Components/manage/forms/Checkbox.vue';
import InputError from '@/Components/manage/forms/InputError.vue';
import InputField from '@/Components/manage/forms/InputField.vue';
import InputLabel from '@/Components/manage/forms/InputLabel.vue';
import { useLocales } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import type { Contact, ResourceResponse } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const { locales } = useLocales();

const props = defineProps<{
    contact: ResourceResponse<Contact>;
}>();

const currentPageTitle = computed(
    () => trans('Edit contact: ') + trans(props.contact.data.slug),
);

const form = useForm({
    icon: props.contact.data.icon,
    value: props.contact.data.value as string,
    locale_value: Object.fromEntries(
        locales.value.map((l) => [
            l.code,
            props.contact.data.locale_value[l.code] ?? '',
        ]),
    ) as Record<string, string>,
    is_active: props.contact.data.is_active,
});

function onSubmit(): void {
    form.patch(route('manage.contacts.update', props.contact.data.id), {
        preserveScroll: true,
    });
}
</script>

<style scoped>
.icon-field textarea {
    height: 180px;
}
</style>
