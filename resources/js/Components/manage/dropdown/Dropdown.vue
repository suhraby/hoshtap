<template>
    <Transition
        enter-active-class="transition duration-150 ease-out"
        enter-from-class="-translate-y-1 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="-translate-y-1 opacity-0"
    >
        <div
            v-if="isOpen"
            ref="dropdownRef"
            :class="[
                'shadow-theme-lg dark:bg-gray-dark absolute right-0 z-40 mt-2 rounded-xl border border-gray-200 bg-white dark:border-gray-800',
                className,
            ]"
        >
            <slot />
        </div>
    </Transition>
</template>

<script lang="ts" setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const props = withDefaults(
    defineProps<{
        isOpen: boolean;
        className?: string;
    }>(),
    { className: '' },
);

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const dropdownRef = ref<HTMLDivElement | null>(null);

function handleClickOutside(event: MouseEvent): void {
    const target = event.target as HTMLElement;

    if (
        dropdownRef.value &&
        !dropdownRef.value.contains(target) &&
        !target.closest('.dropdown-toggle')
    ) {
        emit('close');
    }
}

onMounted(() => document.addEventListener('mousedown', handleClickOutside));
onBeforeUnmount(() =>
    document.removeEventListener('mousedown', handleClickOutside),
);
</script>
