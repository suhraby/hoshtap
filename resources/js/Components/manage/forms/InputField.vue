<template>
    <textarea
        v-if="multiline"
        :class="inputClass"
        v-model="model"
        ref="input"
        v-bind="$attrs"
    />
    <div v-if="isPasswordField" class="relative">
        <input
            :class="inputClass"
            v-model="model"
            ref="input"
            v-bind="$attrs"
            :type="showPassword ? 'text' : 'password'"
        />

        <span
            v-show="isPasswordField"
            @click="togglePasswordVisibility"
            class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500 dark:text-gray-400"
        >
            <EyeCloseIcon v-if="!showPassword" class="text-gray-400" />
            <EyeIcon v-else class="text-gray-400" />
        </span>
    </div>
    <input
        v-else
        :class="inputClass"
        v-model="model"
        ref="input"
        v-bind="$attrs"
    />
</template>

<script setup lang="ts">
import { EyeCloseIcon, EyeIcon } from '@/Components/manage/icons';
import { computed, onMounted, ref } from 'vue';

const model = defineModel<string>({ required: true });

const props = withDefaults(
    defineProps<{
        error?: string;
        multiline?: boolean;
        isPasswordField?: boolean;
    }>(),
    {
        multiline: false,
        isPasswordField: false,
    },
);

defineExpose({ focus: () => input.value?.focus() });

const showPassword = ref(false);
const input = ref<HTMLInputElement | HTMLTextAreaElement | null>(null);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const inputClass = computed(() => [
    'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30',
    props.multiline ? '' : 'h-11',
    props.error
        ? 'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800'
        : 'focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 border-gray-300 dark:border-gray-700',
]);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
</script>
