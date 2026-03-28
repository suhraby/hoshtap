<template>
    <Head :title="currentPageTitle" />

    <AdminLayout>
        <PageBreadcrumb :page-title="currentPageTitle" />

        <ComponentCard>
            <form @submit.prevent="onSubmit">
                <ul class="space-y-3" ref="listRef">
                    <li
                        v-for="(service, index) in sortedServices"
                        :key="service.id"
                        :id="`service-${service.id}`"
                        draggable="true"
                        @dragstart="onDragStart($event, index)"
                        @dragover.prevent="onDragOver($event, index)"
                        @dragend="onDragEnd"
                        :class="[
                            'flex items-center gap-4 rounded-xl border bg-white p-4 transition-all duration-150 dark:bg-white/5',
                            draggingIndex === index
                                ? 'border-brand-300 dark:border-brand-700 opacity-40'
                                : 'border-gray-200 dark:border-gray-800',
                            overIndex === index && draggingIndex !== index
                                ? 'border-brand-400 bg-brand-50 dark:border-brand-600 dark:bg-brand-500/10'
                                : '',
                        ]"
                    >
                        <span
                            class="cursor-grab text-gray-400 active:cursor-grabbing dark:text-gray-600"
                            title="Drag to reorder"
                        >
                            <MenuIcon class="size-5" />
                        </span>

                        <span
                            class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-500 dark:bg-white/10 dark:text-gray-400"
                        >
                            {{ index + 1 }}
                        </span>

                        <span v-html="normalizeSvg(service.icon)"></span>

                        <p
                            class="flex-1 text-sm font-medium text-gray-800 dark:text-white/90"
                        >
                            {{ t(service.title) }}
                        </p>
                    </li>
                </ul>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <Link
                        :href="route('manage.services.index')"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5"
                    >
                        {{ $t('Cancel') }}
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-brand-500 hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            form.processing ? $t('Saving') + '...' : $t('Save')
                        }}
                    </button>
                </div>
            </form>
        </ComponentCard>
    </AdminLayout>
</template>

<script setup lang="ts">
import ComponentCard from '@/Components/manage/common/ComponentCard.vue';
import PageBreadcrumb from '@/Components/manage/common/PageBreadcrumb.vue';
import { MenuIcon } from '@/Components/manage/icons';
import { useTranslatable } from '@/composables/useLocale';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import type { PaginatedResponse, Service } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { ref } from 'vue';

const props = defineProps<{
    services: PaginatedResponse<Service>;
}>();

const { t } = useTranslatable();
const currentPageTitle = wTrans('Sort services');

const sortedServices = ref<Service[]>([...props.services.data]);

const draggingIndex = ref<number | null>(null);
const overIndex = ref<number | null>(null);
const listRef = ref<HTMLUListElement | null>(null);

function onDragStart(e: DragEvent, index: number): void {
    draggingIndex.value = index;
    e.dataTransfer?.setData('text/plain', String(index));
}

function onDragOver(_e: DragEvent, index: number): void {
    if (draggingIndex.value === null || draggingIndex.value === index) return;
    overIndex.value = index;

    const items = [...sortedServices.value];
    const dragged = items.splice(draggingIndex.value, 1)[0];
    items.splice(index, 0, dragged);

    sortedServices.value = items;
    draggingIndex.value = index;
}

function onDragEnd(): void {
    draggingIndex.value = null;
    overIndex.value = null;
}

const form = useForm<{ ids: number[] }>({ ids: [] });

function onSubmit(): void {
    form.ids = sortedServices.value.map((b) => b.id);

    form.patch(route('manage.services.sortOrder'), {
        preserveScroll: true,
    });
}

function normalizeSvg(svg: string): string {
    return svg
        .replace(/width="[^"]*"/i, '')
        .replace(/height="[^"]*"/i, '')
        .replace('<svg', '<svg width="50" height="50"');
}
</script>
