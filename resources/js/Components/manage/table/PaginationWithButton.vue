<template>
    <div class="flex items-center justify-center gap-4 xl:justify-start">
        <button
            @click="handlePageChange(currentPage - 1)"
            :disabled="currentPage === 1"
            class="shadow-theme-xs flex h-10 items-center gap-2 rounded-lg border border-gray-300 bg-white p-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-800 disabled:cursor-not-allowed disabled:opacity-50 sm:p-2.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3 dark:hover:text-gray-200"
        >
            Previous
        </button>

        <ul class="flex items-center gap-1">
            <template v-for="(item, index) in pageItems" :key="index">
                <li v-if="item === '...'">
                    <span
                        class="flex h-10 w-10 items-center justify-center text-sm font-medium text-gray-700 dark:text-gray-400"
                    >
                        ...
                    </span>
                </li>
                <li v-else>
                    <button
                        @click="handlePageChange(item as number)"
                        :class="[
                            'hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/8',
                            currentPage === item
                                ? 'bg-brand-500 text-white'
                                : 'text-gray-700 dark:text-gray-400',
                        ]"
                    >
                        {{ item }}
                    </button>
                </li>
            </template>
        </ul>

        <button
            @click="handlePageChange(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="shadow-theme-xs flex h-10 items-center gap-2 rounded-lg border border-gray-300 bg-white p-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-800 disabled:cursor-not-allowed disabled:opacity-50 sm:p-2.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3 dark:hover:text-gray-200"
        >
            Next
        </button>
    </div>
</template>

<script lang="ts" setup>
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    totalPages: number;
    initialPage?: number;
}>();

const emit = defineEmits<{
    (e: 'page-change', page: number): void;
}>();

const currentPage = ref(props.initialPage ?? 1);

watch(
    () => props.initialPage,
    (incoming) => {
        if (incoming !== undefined && incoming !== currentPage.value) {
            currentPage.value = incoming;
        }
    },
);

function handlePageChange(page: number): void {
    if (page < 1 || page > props.totalPages) return;
    currentPage.value = page;
    emit('page-change', page);
}

const pageItems = computed((): (number | '...')[] => {
    const pagesToShow = 5;
    const startPage = Math.max(
        1,
        currentPage.value - Math.floor(pagesToShow / 2),
    );
    const endPage = Math.min(props.totalPages, startPage + pagesToShow - 1);

    const pages: (number | '...')[] = [];
    if (startPage > 1) pages.push('...');
    for (let i = startPage; i <= endPage; i++) pages.push(i);
    if (endPage < props.totalPages) pages.push('...');

    return pages;
});
</script>
