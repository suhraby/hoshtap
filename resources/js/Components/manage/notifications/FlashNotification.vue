<template>
    <div class="fixed right-4 bottom-4 z-9999 flex flex-col gap-3">
        <Notification
            v-if="flash.success"
            variant="success"
            :title="flash.success"
            :key="flash.success"
        />
        <Notification
            v-if="flash.error"
            variant="error"
            :title="flash.error"
            :key="flash.error"
        />
        <Notification
            v-if="flash.warning"
            variant="warning"
            :title="flash.warning"
            :key="flash.warning"
        />
        <Notification
            v-if="flash.info"
            variant="info"
            :title="flash.info"
            :key="flash.info"
        />
    </div>
</template>

<script lang="ts" setup>
import Notification from '@/Components/manage/notifications/Notification.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const flash = computed(
    () =>
        (page.props.flash as {
            success?: string;
            error?: string;
            warning?: string;
            info?: string;
        }) ?? {},
);
</script>
