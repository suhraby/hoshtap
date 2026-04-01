<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3"
    >
        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
            {{ title }}
        </p>

        <div class="mt-3 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ count }}
                </h4>
            </div>

            <div class="flex items-center gap-1">
                <Link
                    :href="to"
                    class="bg-success-50 text-theme-xs text-success-600 dark:bg-success-500/15 dark:text-success-500 border-success-50 dark:border-success-500/15 hover:border-success-600 dark:hover:border-success-500 flex items-center gap-1 rounded-full border px-2 py-0.5 font-medium"
                >
                    {{ $t('More') }}
                </Link>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
    count: number;
    to: string;
}>();
</script>
