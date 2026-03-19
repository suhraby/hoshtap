<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>

<template>
    <div v-show="message">
        <p class="text-theme-xs text-error-500 mt-1.5">
            {{ message }}
        </p>
    </div>
</template>
