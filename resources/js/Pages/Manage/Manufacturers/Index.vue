<template>
    <Head :title="currentPageTitle" />

    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />

        <ComponentCard :title="currentPageTitle">
            <template #action>
                <ActionButton
                    v-show="manufacturers.data.length > 1"
                    :href="route('manage.manufacturers.sortOrder.form')"
                    :label="$t('Sort order')"
                    :default-icon="false"
                >
                    <SortIcon class="h-5 w-5" />
                </ActionButton>
                <ActionButton :href="route('manage.manufacturers.create')" />
            </template>

            <TableView
                :columns="columns"
                :data="manufacturers"
                :initial-search="searchTerm"
                :initial-limit="limit"
                route-name="manage.manufacturers.index"
                @edit="onEdit"
                @delete="onDelete"
            >
                <template #cell-image="{ value }">
                    <img
                        :src="String(value)"
                        class="h-14 w-20 rounded object-cover"
                        alt="manufacturer"
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
import ActionButton from '@/Components/manage/common/ActionButton.vue';
import ComponentCard from '@/Components/manage/common/ComponentCard.vue';
import PageBreadcrumb from '@/Components/manage/common/PageBreadcrumb.vue';
import { PencilIcon, SortIcon, TrashBinIcon } from '@/Components/manage/icons';
import TableView from '@/Components/manage/table/TableView.vue';
import { useLocales, useTranslatable } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import type { LocalizedText, Manufacturer, PaginatedResponse } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans, wTrans } from 'laravel-vue-i18n';

const { t } = useTranslatable();
const { locales } = useLocales();

const currentPageTitle = wTrans('Manufacturer');

const columns = [
    { key: 'image', label: wTrans('Image'), sortable: false },
    { key: 'title', label: wTrans('Title'), sortable: true },
    { key: 'sort_order', label: wTrans('Sort order'), sortable: true },
];

defineProps<{
    manufacturers: PaginatedResponse<Manufacturer>;
    limit: number;
    searchTerm: string;
}>();

function onEdit(item: Record<string, unknown>): void {
    router.get(route('manage.manufacturers.edit', { manufacturer: item.id }));
}

function onDelete(item: Record<string, unknown>): void {
    if (
        !confirm(
            trans('Are you sure you want to delete: :title?', {
                title: t(item.title),
            }),
        )
    )
        return;

    router.delete(
        route('manage.manufacturers.destroy', { manufacturer: item.id }),
        {
            preserveScroll: true,
        },
    );
}
</script>
