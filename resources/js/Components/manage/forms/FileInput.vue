<template>
    <input
        type="file"
        v-bind="$attrs"
        :class="[
            'shadow-theme-xs focus:border-ring-brand-300 focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:py-3 file:pr-3 file:pl-3.5 file:text-sm placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400',
            error
                ? 'border-error-300 file:border-error-200 dark:border-error-700 dark:file:border-error-800'
                : 'border-gray-300 file:border-gray-200 file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 dark:border-gray-700 dark:file:border-gray-800',
        ]"
        @change="onFileChange"
    />
</template>

<script lang="ts" setup>
defineOptions({ inheritAttrs: false });

defineProps<{
    error?: string;
}>();

const emit = defineEmits<{
    (e: 'change', file: File | null): void;
}>();

function onFileChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    emit('change', input.files?.[0] ?? null);
}
</script>
