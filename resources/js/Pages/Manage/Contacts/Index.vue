<template>
    <Head :title="currentPageTitle" />

    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />

        <ComponentCard :title="currentPageTitle">
            <TableView
                :columns="columns"
                :data="contacts"
                :initial-search="searchTerm"
                :initial-limit="limit"
                route-name="manage.contacts.index"
                @edit="onEdit"
            >
                <template #cell-icon="{ value }">
                    <span
                        v-if="value"
                        v-html="normalizeSvg(value as string)"
                    ></span>
                    <span v-else>-</span>
                </template>

                <template #cell-slug="{ value }">
                    {{ $t(value as string) }}
                </template>

                <template #cell-value="{ value }">
                    <span
                        v-if="typeof value === 'object'"
                        class="block"
                        v-for="locale in locales"
                        :key="locale.code"
                    >
                        {{ (value as LocalizedText)[locale.code] }}
                    </span>
                    <span v-else>{{ value }}</span>
                </template>

                <template #cell-is_active="{ value }">
                    <span v-if="value">
                        <CheckCircleIcon class="h-4.5 w-4.5 text-green-700" />
                    </span>
                    <span v-else>
                        <CloseIcon class="h-4.5 w-4.5 text-red-700" />
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
                    </div>
                </template>
            </TableView>
        </ComponentCard>
    </AdminLayout>
</template>

<script setup lang="ts">
import ComponentCard from '@/Components/manage/common/ComponentCard.vue';
import PageBreadcrumb from '@/Components/manage/common/PageBreadcrumb.vue';
import {
    CheckCircleIcon,
    CloseIcon,
    PencilIcon,
} from '@/Components/manage/icons';
import TableView from '@/Components/manage/table/TableView.vue';
import { useLocales, useTranslatable } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import type { Contact, LocalizedText, PaginatedResponse } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';

const { t } = useTranslatable();
const { locales } = useLocales();

const currentPageTitle = wTrans('Contacts');

const columns = [
    { key: 'icon', label: wTrans('Icon'), sortable: false },
    { key: 'slug', label: wTrans('Slug'), sortable: true },
    { key: 'value', label: wTrans('Value'), sortable: false },
    { key: 'is_active', label: wTrans('Is active'), sortable: false },
];

defineProps<{
    contacts: PaginatedResponse<Contact>;
    limit: number;
    searchTerm: string;
}>();

function normalizeSvg(svg: string): string {
    return svg
        .replace(/width="[^"]*"/i, '')
        .replace(/height="[^"]*"/i, '')
        .replace('<svg', '<svg width="24" height="24"');
}

function onEdit(item: Record<string, unknown>): void {
    router.get(route('manage.contacts.edit', { contact: item.id }));
}
</script>
