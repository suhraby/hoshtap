<template>
    <div class="relative inline-block">
        <!-- Trigger -->
        <!-- class="bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] dark:hover:text-gray-300" -->
        <button
            class="dropdown-toggle bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] dark:hover:text-gray-300"
            @click="toggleDropdown"
        >
            Account Menu
            <svg
                :class="[
                    'stroke-current duration-200 ease-in-out',
                    isOpen ? 'rotate-180' : '',
                ]"
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M4.79199 7.396L10.0003 12.6043L15.2087 7.396"
                    stroke=""
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </button>

        <!-- Dropdown -->
        <Dropdown
            :is-open="isOpen"
            class-name="left-0 top-full w-full min-w-[260px] rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-[#1E2635]"
            @close="closeDropdown"
        >
            <ul class="flex flex-col gap-1">
                <li v-for="item in menuItems" :key="item">
                    <DropdownItem
                        class-name="flex rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5"
                        @item-click="closeDropdown"
                    >
                        {{ item }}
                    </DropdownItem>
                </li>
            </ul>
        </Dropdown>
    </div>
</template>

<script lang="ts" setup>
import Dropdown from '@/Components/manage/dropdown/Dropdown.vue';
import DropdownItem from '@/Components/manage/dropdown/DropdownItem.vue';
import { ref } from 'vue';

const isOpen = ref(false);

const menuItems = ['Edit Profile', 'Account Settings', 'License', 'Support'];

function toggleDropdown(): void {
    isOpen.value = !isOpen.value;
}
function closeDropdown(): void {
    isOpen.value = false;
}
</script>
