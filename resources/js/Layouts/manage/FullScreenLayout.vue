<template>
    <ThemeProvider>
        <SidebarProvider>
            <div class="min-h-screen">
                <main>
                    <slot></slot>
                </main>
            </div>
        </SidebarProvider>
    </ThemeProvider>
</template>

<script setup lang="ts">
import SidebarProvider from '@/Layouts/manage/SidebarProvider.vue';
import ThemeProvider from '@/Layouts/manage/ThemeProvider.vue';
</script>
