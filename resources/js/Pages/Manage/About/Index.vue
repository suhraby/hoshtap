<template>
    <Head :title="currentPageTitle" />

    <AdminLayout>
        <PageBreadcrumb :page-title="currentPageTitle" />

        <ComponentCard v-if="!about" :title="currentPageTitle">
            <template #action>
                <ActionButton :href="route('manage.about.create')" />
            </template>

            <div
                class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
            >
                {{ $t('No results found.') }}
            </div>
        </ComponentCard>

        <div
            class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800"
            v-else
        >
            <div
                class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between"
            >
                <div>
                    <h4
                        class="text-lg font-semibold text-gray-800 lg:mb-6 dark:text-white/90"
                    >
                        {{ $t('About us') }}
                    </h4>

                    <div
                        class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32"
                    >
                        <div>
                            <p
                                class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400"
                            >
                                {{ $t('About us image') }}
                            </p>
                            <img
                                :src="about.data.image"
                                alt="about us image"
                                class="h-auto w-36"
                            />
                        </div>
                        <div>
                            <p
                                class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400"
                            >
                                {{ $t('Title') }}
                            </p>
                            <p
                                class="text-sm font-medium text-gray-800 dark:text-white/90"
                                v-for="locale in locales"
                                :key="locale.code"
                            >
                                {{ about.data.title[locale.code] }}
                            </p>
                        </div>

                        <div class="sm:col-span-2">
                            <p
                                class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400"
                            >
                                {{ $t('Description') }}
                            </p>
                            <p
                                class="text-sm font-medium text-gray-800 dark:text-white/90"
                                v-for="locale in locales"
                                :key="locale.code"
                            >
                                {{ about.data.body[locale.code] }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400"
                            >
                                {{ $t('Market image') }}
                            </p>
                            <img
                                :src="about.data.market_image"
                                alt="market image"
                                class="h-auto w-36"
                            />
                        </div>
                        <div>
                            <p
                                class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400"
                            >
                                {{ $t('Market title') }}
                            </p>
                            <p
                                class="text-sm font-medium text-gray-800 dark:text-white/90"
                                v-for="locale in locales"
                                :key="locale.code"
                            >
                                {{ about.data.market_title[locale.code] }}
                            </p>
                        </div>
                    </div>
                </div>

                <Link
                    :href="route('manage.about.edit', about.data.id)"
                    class="edit-button"
                >
                    <PencilIcon />
                    {{ $t('Edit') }}
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import ActionButton from '@/Components/manage/common/ActionButton.vue';
import ComponentCard from '@/Components/manage/common/ComponentCard.vue';
import PageBreadcrumb from '@/Components/manage/common/PageBreadcrumb.vue';
import { PencilIcon } from '@/Components/manage/icons';
import { useLocales } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import { About, ResourceResponse } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';

const currentPageTitle = wTrans('About us');

const { locales } = useLocales();

defineProps<{
    about: ResourceResponse<About> | null;
}>();
</script>
