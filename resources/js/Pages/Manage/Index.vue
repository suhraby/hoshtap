<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div>
            {{ $t('Dashboard') }}
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>
