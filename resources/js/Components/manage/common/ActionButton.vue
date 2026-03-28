<template>
    <Link
        :href="href"
        class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/5 dark:hover:text-gray-300"
    >
        <slot v-if="$slots.default" />
        <PlusIcon v-else-if="defaultIcon" />

        {{ label }}
    </Link>
</template>

<script lang="ts" setup>
import { PlusIcon } from '@/Components/manage/icons';
import { Link } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { ComputedRef } from 'vue';

withDefaults(
    defineProps<{
        href: string;
        label?: ComputedRef<string> | string;
        defaultIcon?: boolean;
    }>(),
    {
        label: () => wTrans('Create new'),
        defaultIcon: true,
    },
);
</script>
