<template>
    <h5 class="text-brand-500 mb-13 text-lg font-bold">{{ title }}</h5>
</template>

<script lang="ts" setup>
defineProps<{
    title: string;
}>();
</script>
