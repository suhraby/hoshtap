<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4"
        >
            <SmallStat
                v-for="(item, index) in data"
                :key="index"
                :title="$t(item.title)"
                :count="item.count"
                :to="item.to"
            />
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import SmallStat from '@/Components/manage/common/SmallStat.vue';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    data: {
        title: string;
        count: number;
        to: string;
    }[];
}>();
</script>
