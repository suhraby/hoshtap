<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="isVisible"
            :class="[
                'shadow-theme-sm relative flex w-full items-center justify-between gap-3 overflow-hidden rounded-md border-b-4 bg-white p-3 sm:max-w-85 dark:bg-[#1E2634]',
                variantStyle.borderColor,
            ]"
        >
            <div class="flex items-center gap-4">
                <div
                    :class="[
                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                        variantStyle.iconBg,
                    ]"
                >
                    <component :is="variantStyle.icon" class="size-5" />
                </div>

                <div>
                    <h4
                        class="text-sm font-medium text-gray-800 sm:text-base dark:text-white/90"
                    >
                        {{ title }}
                    </h4>
                    <p
                        v-if="description"
                        class="mt-0.5 text-xs text-gray-600 sm:text-sm dark:text-white/70"
                    >
                        {{ description }}
                    </p>
                </div>
            </div>

            <button
                @click="dismiss"
                class="shrink-0 text-gray-400 hover:text-gray-800 dark:hover:text-white/90"
            >
                <CloseIcon class="size-4" />
            </button>

            <div
                v-if="hideDuration > 0"
                :class="[
                    'absolute bottom-0 left-0 h-0.5',
                    variantStyle.progressBg,
                ]"
                :style="{
                    width: `${progress}%`,
                    transition: `width ${hideDuration}ms linear`,
                }"
            />
        </div>
    </Transition>
</template>

<script lang="ts" setup>
import {
    AlertHexaIcon,
    CheckCircleIcon,
    CloseIcon,
    InfoHexaIcon,
    InfoIcon,
} from '@/Components/manage/icons';
import { onMounted, ref, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        variant: 'success' | 'info' | 'warning' | 'error';
        title: string;
        description?: string;
        hideDuration?: number;
    }>(),
    { hideDuration: 4000 },
);

const variantStyles = {
    success: {
        borderColor: 'border-success-500',
        iconBg: 'bg-success-50 text-success-500',
        progressBg: 'bg-success-500',
        icon: CheckCircleIcon,
    },
    info: {
        borderColor: 'border-blue-light-500',
        iconBg: 'bg-blue-light-50 text-blue-light-500',
        progressBg: 'bg-blue-light-500',
        icon: InfoIcon,
    },
    warning: {
        borderColor: 'border-warning-500',
        iconBg: 'bg-warning-50 text-warning-500',
        progressBg: 'bg-warning-500',
        icon: AlertHexaIcon,
    },
    error: {
        borderColor: 'border-error-500',
        iconBg: 'bg-error-50 text-error-500',
        progressBg: 'bg-error-500',
        icon: InfoHexaIcon,
    },
} as const;

const variantStyle = variantStyles[props.variant];

const isVisible = ref(true);
const progress = ref(100);

let hideTimer: ReturnType<typeof setTimeout> | null = null;
let progressTimer: ReturnType<typeof setTimeout> | null = null;

function startTimers(): void {
    if (props.hideDuration <= 0) return;

    progressTimer = setTimeout(() => {
        progress.value = 0;
    }, 50);
    hideTimer = setTimeout(() => {
        isVisible.value = false;
    }, props.hideDuration);
}

function clearTimers(): void {
    if (hideTimer) clearTimeout(hideTimer);
    if (progressTimer) clearTimeout(progressTimer);
}

function dismiss(): void {
    clearTimers();
    isVisible.value = false;
}

onMounted(startTimers);

watch(
    () => [props.title, props.variant] as const,
    () => {
        clearTimers();
        isVisible.value = true;
        progress.value = 100;
        setTimeout(startTimers, 50);
    },
);
</script>
