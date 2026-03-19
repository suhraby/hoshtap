<template>
    <!-- Inertia Link variant -->
    <Link
        v-if="tag === 'a' && to"
        :href="to"
        :class="combinedClasses"
        @click="handleClick"
    >
        <slot />
    </Link>

    <!-- Button variant (default) -->
    <button
        v-else
        type="button"
        :class="combinedClasses"
        @click="handleClick"
    >
        <slot />
    </button>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        tag?:           'a' | 'button';
        to?:            string;
        baseClassName?: string;
        className?:     string;
    }>(),
    {
        tag:           'button',
        baseClassName: 'block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900',
        className:     '',
    },
);

const emit = defineEmits<{
    (e: 'click'):      void;
    (e: 'itemClick'): void;
}>();

const combinedClasses = computed((): string =>
    `${props.baseClassName} ${props.className}`.trim(),
);

function handleClick(): void {
    emit('click');
    emit('itemClick');
}
</script>
