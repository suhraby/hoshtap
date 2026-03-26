<template>
    <Head :title="currentPageTitle" />

    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />

        <ComponentCard :title="currentPageTitle">
            <template #action>
                <CreateButton :href="route('manage.banners.create')" />
            </template>

            <TableView
                :columns="columns"
                :data="banners"
                :initial-search="searchTerm"
                :initial-limit="limit"
                route-name="manage.banners.index"
                @edit="onEdit"
                @delete="onDelete"
            >
                <template #cell-thumbnail="{ value }">
                    <img
                        :src="String(value)"
                        class="h-14 w-20 rounded object-cover"
                        alt="banner"
                    />
                </template>

                <template #cell-title="{ value }">
                    <span
                        class="block"
                        v-for="locale in locales"
                        :key="locale.code"
                    >
                        {{ (value as LocalizedText)[locale.code] }}
                    </span>
                </template>

                <template #actions="{ item }">
                    <div class="flex items-center gap-2">
                        <button
                            class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                            @click="onEdit(item)"
                        >
                            <PencilIcon />
                        </button>
                        <button
                            class="hover:text-error-500 dark:hover:text-error-500 text-gray-500 dark:text-gray-400"
                            @click="onDelete(item)"
                        >
                            <TrashBinIcon />
                        </button>
                    </div>
                </template>
            </TableView>
        </ComponentCard>
    </AdminLayout>
</template>

<script setup lang="ts">
import ComponentCard from '@/Components/manage/common/ComponentCard.vue';
import CreateButton from '@/Components/manage/common/CreateButton.vue';
import PageBreadcrumb from '@/Components/manage/common/PageBreadcrumb.vue';
import { PencilIcon, TrashBinIcon } from '@/Components/manage/icons';
import TableView from '@/Components/manage/table/TableView.vue';
import { useLocales, useTranslatable } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import type { Banner, LocalizedText, PaginatedResponse } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';

const { t } = useTranslatable();
const { locales } = useLocales();

const currentPageTitle = wTrans('Banners');

const columns = [
    { key: 'thumbnail', label: wTrans('Image'), sortable: false },
    { key: 'title', label: wTrans('Title'), sortable: false },
];

defineProps<{
    banners: PaginatedResponse<Banner>;
    limit: number;
    searchTerm: string;
}>();

function onEdit(item: Record<string, unknown>): void {
    router.get(route('manage.banners.edit', { banner: item.id }));
}

function onDelete(item: Record<string, unknown>): void {
    if (!confirm(`Are you sure you want to delete ${t(item.title)}?`)) return;

    router.delete(route('manage.banners.destroy', { banner: item.id }), {
        preserveScroll: true,
    });
}
</script>
