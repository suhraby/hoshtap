<template>
    <div class="relative" ref="dropdownRef">
        <button
            class="flex items-center text-gray-700 dark:text-gray-400"
            @click.prevent="toggleDropdown"
        >
            <span
                class="menu-item-active mr-3 flex h-11 w-11 items-center justify-center overflow-hidden rounded-full"
            >
                {{ userInitials }}
            </span>

            <span class="text-theme-sm mr-1 block font-medium"
                >{{ $page.props.auth.user.name }}
            </span>

            <ChevronDownIcon :class="{ 'rotate-180': dropdownOpen }" />
        </button>

        <!-- Dropdown Start -->
        <div
            v-if="dropdownOpen"
            class="shadow-theme-lg dark:bg-gray-dark absolute right-0 mt-4.25 flex w-65 flex-col rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800"
        >
            <div>
                <span
                    class="text-theme-sm block font-medium text-gray-700 dark:text-gray-400"
                >
                    {{ $page.props.auth.user.name }}
                    {{ $page.props.auth.user.surname }}
                </span>
                <span
                    class="text-theme-xs mt-0.5 block text-gray-500 dark:text-gray-400"
                >
                    {{ $page.props.auth.user.username }}
                </span>
            </div>

            <ul
                class="flex flex-col gap-1 border-b border-gray-200 pt-4 pb-3 dark:border-gray-800"
            >
                <li v-for="item in menuItems" :key="item.href">
                    <Link
                        :href="item.href"
                        class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                    >
                        <component
                            :is="item.icon"
                            class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
                        />
                        {{ item.text }}
                    </Link>
                </li>
            </ul>

            <Link
                :href="route('manage.logout')"
                method="post"
                as="button"
                class="group text-theme-sm mt-3 flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
            >
                <LogoutIcon
                    class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
                />
                {{ $t('Log Out') }}
            </Link>
            <a> </a>
        </div>
        <!-- Dropdown End -->
    </div>
</template>

<script setup>
import {
    ChevronDownIcon,
    LogoutIcon,
    UserCircleIcon,
} from '@/Components/manage/icons';
import { Link, usePage } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const page = usePage();

const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const menuItems = [
    {
        href: '/manage/profile',
        icon: UserCircleIcon,
        text: wTrans('Edit profile'),
    },
];

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const closeDropdown = () => {
    dropdownOpen.value = false;
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        closeDropdown();
    }
};

const userInitials = computed(() => {
    const user = page.props.auth.user;
    const first = user.name?.[0]?.toUpperCase() ?? '';
    const last = user.surname?.[0]?.toUpperCase() ?? '';

    return first + last;
});

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>
