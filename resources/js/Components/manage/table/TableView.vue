<template>
    <div class="overflow-hidden rounded-xl bg-white dark:bg-white/3">
        <div
            class="flex flex-col gap-2 rounded-t-xl border border-b-0 border-gray-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-white/5"
        >
            <div class="flex items-center gap-3">
                <span class="text-gray-500 dark:text-gray-400">{{
                    $t('Show')
                }}</span>
                <div class="relative z-20 bg-transparent">
                    <select
                        v-model.number="localLimit"
                        @change="onLimitChange"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-2 pr-8 pl-3 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    >
                        <option
                            v-for="val in [5, 8, 10, 25, 50]"
                            :key="val"
                            :value="val"
                            class="text-gray-500 dark:bg-gray-900 dark:text-gray-400"
                        >
                            {{ val }}
                        </option>
                    </select>
                    <span
                        class="pointer-events-none absolute top-1/2 right-2 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400"
                    >
                        <ChevronDownIcon class="h-4 w-4" />
                    </span>
                </div>
                <span class="text-gray-500 dark:text-gray-400">{{
                    $t('entries')
                }}</span>
            </div>

            <div class="relative">
                <span
                    class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400"
                >
                    <SearchIcon />
                </span>
                <input
                    v-model="localSearch"
                    type="text"
                    :placeholder="$t('Search') + '...'"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-75 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                />
            </div>
        </div>

        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-t border-gray-100 dark:border-white/5">
                    <tr>
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            :class="[
                                'border border-gray-100 px-4 py-3 dark:border-white/5',
                                isSortable(col)
                                    ? 'cursor-pointer'
                                    : 'cursor-default',
                            ]"
                            @click="isSortable(col) && handleSort(col.key)"
                        >
                            <div class="flex items-center justify-between">
                                <p
                                    class="text-theme-xs font-medium text-gray-700 dark:text-gray-400"
                                >
                                    {{ col.label }}
                                </p>
                                <!-- Sort arrows — only for sortable columns -->
                                <button
                                    v-if="isSortable(col)"
                                    class="flex flex-col gap-0.5"
                                    @click.stop="handleSort(col.key)"
                                >
                                    <svg
                                        :class="[
                                            'transition-colors',
                                            localSortKey === col.key &&
                                            localSortOrder === 'asc'
                                                ? 'text-brand-500'
                                                : 'text-gray-300 dark:text-gray-700',
                                        ]"
                                        width="8"
                                        height="5"
                                        viewBox="0 0 8 5"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                    <svg
                                        :class="[
                                            'transition-colors',
                                            localSortKey === col.key &&
                                            localSortOrder === 'desc'
                                                ? 'text-brand-500'
                                                : 'text-gray-300 dark:text-gray-700',
                                        ]"
                                        width="8"
                                        height="5"
                                        viewBox="0 0 8 5"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </th>

                        <th
                            class="border border-gray-100 px-4 py-3 dark:border-white/5"
                        >
                            <p
                                class="text-theme-xs font-medium text-gray-700 dark:text-gray-400"
                            >
                                {{ $t('Action') }}
                            </p>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(item, rowIndex) in data.data"
                        :key="(item as any).id ?? rowIndex"
                    >
                        <td
                            v-for="(col, colIndex) in columns"
                            :key="col.key"
                            :class="[
                                'text-theme-sm border border-gray-100 px-4 py-4 whitespace-nowrap dark:border-white/5',
                                colIndex === 0
                                    ? 'font-medium text-gray-800 dark:text-white'
                                    : 'font-normal text-gray-800 dark:text-gray-400',
                            ]"
                        >
                            <slot
                                :name="`cell-${col.key}`"
                                :item="item"
                                :value="getCellValue(item, col.key)"
                            >
                                {{ getCellValue(item, col.key) }}
                            </slot>
                        </td>

                        <td
                            class="text-theme-sm border border-gray-100 px-4 py-4 whitespace-nowrap text-gray-800 dark:border-white/5 dark:text-white/90"
                        >
                            <slot name="actions" :item="item">
                                <div class="flex items-center gap-2">
                                    <button
                                        class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                        @click="$emit('view', item)"
                                    >
                                        <EyeIcon />
                                    </button>
                                    <button
                                        class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                        @click="$emit('edit', item)"
                                    >
                                        <PencilIcon />
                                    </button>
                                    <button
                                        class="hover:text-error-500 dark:hover:text-error-500 text-gray-500 dark:text-gray-400"
                                        @click="$emit('delete', item)"
                                    >
                                        <TrashBinIcon />
                                    </button>
                                </div>
                            </slot>
                        </td>
                    </tr>

                    <tr v-if="data.data.length === 0">
                        <td
                            :colspan="columns.length + 1"
                            class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                        >
                            {{ $t('No results found.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            class="rounded-b-xl border border-t-0 border-gray-100 py-4 pr-4 pl-4.5 dark:border-white/5"
        >
            <div
                class="flex flex-col xl:flex-row xl:items-center xl:justify-between"
            >
                <PaginationWithButton
                    :total-pages="data.meta.last_page"
                    :initial-page="data.meta.current_page"
                    @page-change="onPageChange"
                />
                <div class="pt-3 xl:pt-0">
                    <p
                        class="border-t border-gray-100 pt-3 text-center text-sm font-medium text-gray-500 xl:border-t-0 xl:pt-0 xl:text-left dark:border-gray-800 dark:text-gray-400"
                    >
                        {{
                            $t('Showing :from to :to of :total entries', {
                                from: String(data.meta.from ?? 0),
                                to: String(data.meta.to ?? 0),
                                total: String(data.meta.total),
                            })
                        }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {
    ChevronDownIcon,
    EyeIcon,
    PencilIcon,
    SearchIcon,
    TrashBinIcon,
} from '@/Components/manage/icons';
import type { PaginatedResponse } from '@/types';
import { router } from '@inertiajs/vue3';
import { ComputedRef, ref, watch } from 'vue';
import PaginationWithButton from './PaginationWithButton.vue';

export interface TableColumn {
    key: string;
    label: string | ComputedRef<string>;
    sortable?: boolean;
}

type SortOrder = 'asc' | 'desc';

const props = defineProps<{
    columns: TableColumn[];
    data: PaginatedResponse<Record<string, unknown>>;
    initialSearch?: string;
    initialLimit?: number;
    initialSortKey?: string;
    initialSortOrder?: SortOrder;
    routeName?: string;
}>();

defineEmits<{
    (e: 'view', item: Record<string, unknown>): void;
    (e: 'edit', item: Record<string, unknown>): void;
    (e: 'delete', item: Record<string, unknown>): void;
}>();

const localSearch = ref(props.initialSearch ?? '');
const localLimit = ref(props.initialLimit ?? 10);
const localSortKey = ref(props.initialSortKey ?? '');
const localSortOrder = ref<SortOrder>(props.initialSortOrder ?? 'asc');

function isSortable(col: TableColumn): boolean {
    return col.sortable !== false;
}

function getCellValue(item: Record<string, unknown>, key: string): unknown {
    return key.split('.').reduce<unknown>((obj, part) => {
        if (obj !== null && typeof obj === 'object') {
            return (obj as Record<string, unknown>)[part];
        }
        return undefined;
    }, item);
}

function fetchData(
    overrides: {
        page?: number;
        limit?: number;
        searchTerm?: string;
        sortKey?: string;
        sortOrder?: SortOrder;
    } = {},
): void {
    const raw: Record<string, unknown> = {
        page: overrides.page ?? props.data.meta.current_page,
        limit: overrides.limit ?? localLimit.value,
        searchTerm: overrides.searchTerm ?? localSearch.value,
        sortKey: overrides.sortKey ?? localSortKey.value,
        sortOrder: overrides.sortOrder ?? localSortOrder.value,
    };

    for (const k of Object.keys(raw)) {
        if (raw[k] === '' || raw[k] === null || raw[k] === undefined) {
            delete raw[k];
        }
    }

    const params = raw as Record<string, string>;

    router.get(
        props.routeName ? route(props.routeName) : window.location.pathname,
        params,
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}

let searchTimer: ReturnType<typeof setTimeout> | null = null;

watch(localSearch, (value) => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        fetchData({ searchTerm: value, page: 1 });
    }, 400);
});

function onLimitChange(): void {
    fetchData({ limit: localLimit.value, page: 1 });
}

function onPageChange(page: number): void {
    fetchData({ page });
}

function handleSort(key: string): void {
    const newOrder: SortOrder =
        localSortKey.value === key && localSortOrder.value === 'asc'
            ? 'desc'
            : 'asc';

    localSortKey.value = key;
    localSortOrder.value = newOrder;

    fetchData({ sortKey: key, sortOrder: newOrder, page: 1 });
}
</script>
