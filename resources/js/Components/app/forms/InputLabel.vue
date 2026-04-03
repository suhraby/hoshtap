<script setup lang="ts">
defineProps<{
    value?: string;
    required?: boolean;
}>();
</script>

<template>
    <label
        class="mb-1.5 block text-lg font-semibold text-white lg:mb-2 lg:text-xl"
    >
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <span v-if="required" class="text-error-500"> *</span>
    </label>
</template>
