<template>
    <Head :title="currentPageTitle" />

    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />

        <ComponentCard :title="currentPageTitle">
            <template #action>
                <CreateButton :href="route('manage.services.create')" />
            </template>

            <TableView
                :columns="columns"
                :data="services"
                :initial-search="searchTerm"
                :initial-limit="limit"
                route-name="manage.services.index"
                @edit="onEdit"
                @delete="onDelete"
            >
                <template #cell-icon="{ value }">
                    <span v-html="normalizeSvg(value as string)"></span>
                </template>

                <template #cell-title="{ value }">
                    <span
                        class="block"
                        v-if="typeof value === 'object'"
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
import type { LocalizedText, PaginatedResponse, Service } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';

const { t } = useTranslatable();
const { locales } = useLocales();

const currentPageTitle = wTrans('Service');

const columns = [
    { key: 'icon', label: wTrans('Icon'), sortable: false },
    { key: 'title', label: wTrans('Title'), sortable: true },
];

defineProps<{
    services: PaginatedResponse<Service>;
    limit: number;
    searchTerm: string;
}>();

function onEdit(item: Record<string, unknown>): void {
    router.get(route('manage.services.edit', { service: item.id }));
}

function onDelete(item: Record<string, unknown>): void {
    if (!confirm(`Are you sure you want to delete ${t(item.title)}?`)) return;

    router.delete(route('manage.services.destroy', { service: item.id }), {
        preserveScroll: true,
    });
}

function normalizeSvg(svg: string): string {
    return svg
        .replace(/width="[^"]*"/i, '')
        .replace(/height="[^"]*"/i, '')
        .replace('<svg', '<svg width="50" height="50"');
}
</script>
