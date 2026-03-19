<template>
    <ThemeProvider>
        <div class="min-h-screen xl:flex">
            <AppSidebar />

            <Backdrop />
            <div
                class="flex-1 transition-all duration-300 ease-in-out"
                :class="[isExpanded || isHovered ? 'lg:ml-72.5' : 'lg:ml-22.5']"
            >
                <AppHeader />
                <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
                    <slot></slot>
                </div>
            </div>
        </div>
    </ThemeProvider>

    <FlashNotification />
</template>

<script setup>
import FlashNotification from '@/Components/manage/notifications/FlashNotification.vue';
import { useSidebarProvider } from '@/composables/useSidebar';
import AppHeader from '@/Layouts/manage/AppHeader.vue';
import AppSidebar from '@/Layouts/manage/AppSidebar.vue';
import Backdrop from '@/Layouts/manage/Backdrop.vue';
import ThemeProvider from '@/Layouts/manage/ThemeProvider.vue';
const { isExpanded, isHovered } = useSidebarProvider();
</script>
