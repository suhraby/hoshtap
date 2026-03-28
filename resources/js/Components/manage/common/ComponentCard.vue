<template>
    <div
        :class="[
            'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3',
            className,
        ]"
    >
        <div v-if="title || desc" class="px-6 py-5">
            <div class="flex items-center justify-between">
                <h3
                    v-if="title"
                    class="text-base font-medium text-gray-800 dark:text-white/90"
                >
                    {{ title }}
                </h3>

                <div class="flex items-center justify-end space-x-3">
                    <slot name="action" />
                </div>
            </div>
            <p
                v-if="desc"
                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
            >
                {{ desc }}
            </p>
        </div>

        <div
            :class="[
                'p-4 sm:p-6',
                title || desc
                    ? 'border-t border-gray-100 dark:border-gray-800'
                    : '',
            ]"
        >
            <div class="space-y-5">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
interface Props {
    title?: string;
    className?: string;
    desc?: string;
}

defineProps<Props>();
</script>
