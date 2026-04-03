<template>
    <textarea
        v-if="multiline"
        :class="inputClass"
        v-model="model"
        ref="input"
        v-bind="$attrs"
    />
    <input
        v-else
        :class="inputClass"
        v-model="model"
        ref="input"
        v-bind="$attrs"
        class=""
    />
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';

const model = defineModel<string | number | null>({
    required: false,
    default: null,
});

const props = withDefaults(
    defineProps<{
        error?: string;
        multiline?: boolean;
    }>(),
    {
        multiline: false,
    },
);

defineExpose({ focus: () => input.value?.focus() });

const input = ref<HTMLInputElement | HTMLTextAreaElement | null>(null);

const inputClass = computed(() => [
    'text-brand-700 bg-brand-25 placeholder:text-nickel w-full rounded-xl border px-6 py-4.5 text-base font-medium focus:ring-2 focus:outline-hidden',
    props.multiline ? 'h-65' : '',
    props.error
        ? 'border-error-300 focus:border-error-300 focus:ring-error-500/10'
        : 'focus:border-brand-200 focus:ring-brand-200 border-white',
]);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
</script>
