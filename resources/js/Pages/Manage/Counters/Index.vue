<template>
    <Head :title="currentPageTitle" />

    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />

        <ComponentCard :title="currentPageTitle">
            <template #action>
                <CreateButton :href="route('manage.counters.create')" />
            </template>

            <TableView
                :columns="columns"
                :data="counters"
                :initial-search="searchTerm"
                :initial-limit="limit"
                route-name="manage.counters.index"
                @edit="onEdit"
                @delete="onDelete"
            >
                <template #cell-title="{ value }">
                    <span
                        class="block"
                        v-for="locale in locales"
                        :key="locale.code"
                    >
                        {{ (value as LocalizedText)[locale.code] }}
                    </span>
                </template>

                <template #cell-description="{ value }">
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
import type { Counter, LocalizedText, PaginatedResponse } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans, wTrans } from 'laravel-vue-i18n';

const { t } = useTranslatable();
const { locales } = useLocales();

const currentPageTitle = wTrans('Counters');

const columns = [
    { key: 'title', label: wTrans('Title'), sortable: true },
    { key: 'description', label: wTrans('Description'), sortable: false },
    { key: 'number', label: wTrans('Number'), sortable: false },
    { key: 'symbol', label: wTrans('Symbol'), sortable: false },
];

defineProps<{
    counters: PaginatedResponse<Counter>;
    limit: number;
    searchTerm: string;
}>();

function onEdit(item: Record<string, unknown>): void {
    router.get(route('manage.counters.edit', { counter: item.id }));
}

function onDelete(item: Record<string, unknown>): void {
    if (
        !confirm(
            trans('Are you sure you want to delete: :title?', {
                title: t(item.title),
            }),
        )
    )
        router.delete(route('manage.counters.destroy', { counter: item.id }), {
            preserveScroll: true,
        });
}
</script>
