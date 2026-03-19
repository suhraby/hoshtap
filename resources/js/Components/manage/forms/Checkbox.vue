<template>
    <label
        :for="id"
        class="flex cursor-pointer items-center text-sm font-normal text-gray-700 select-none dark:text-gray-400"
    >
        <div class="relative">
            <input
                type="checkbox"
                :id="id"
                :name="name"
                :value="value"
                v-model="proxyChecked"
                class="sr-only"
            />
            <div
                :class="[
                    'hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] transition-colors',
                    proxyChecked
                        ? 'border-brand-500 bg-brand-500'
                        : 'border-gray-300 bg-transparent dark:border-gray-700',
                ]"
            >
                <span :class="proxyChecked ? '' : 'opacity-0'">
                    <svg
                        width="14"
                        height="14"
                        viewBox="0 0 14 14"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
                            stroke="white"
                            stroke-width="1.94437"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>
            </div>
        </div>
        <slot />
    </label>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    checked: boolean;
    value?: any;
    name?: string;
    id?: string;
}>();

const emit = defineEmits(['update:checked']);

const proxyChecked = computed({
    get() {
        return props.checked;
    },
    set(val) {
        emit('update:checked', val);
    },
});
</script>
