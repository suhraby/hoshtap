<template>
    <aside
        :class="[
            'fixed top-0 left-0 z-99999 mt-16 flex h-screen flex-col border-r border-gray-200 bg-white px-5 text-gray-900 transition-all duration-300 ease-in-out lg:mt-0 dark:border-gray-800 dark:bg-gray-900',
            {
                'lg:w-72.5': isExpanded || isMobileOpen || isHovered,
                'lg:w-22.5': !isExpanded && !isHovered,
                'w-72.5 translate-x-0': isMobileOpen,
                '-translate-x-full': !isMobileOpen,
                'lg:translate-x-0': true,
            },
        ]"
        @mouseenter="!isExpanded && (isHovered = true)"
        @mouseleave="isHovered = false"
    >
        <div
            :class="[
                'flex py-8',
                !isExpanded && !isHovered
                    ? 'lg:justify-center'
                    : 'justify-start',
            ]"
        >
            <Link :href="route('manage.index')">
                <img
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="dark:hidden"
                    src="/images/logo/logo.svg"
                    alt="Logo"
                    width="150"
                    height="40"
                />
                <img
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="hidden dark:block"
                    src="/images/logo/logo-dark.svg"
                    alt="Logo"
                    width="150"
                    height="40"
                />
                <img
                    v-else
                    src="/images/logo/logo-icon.svg"
                    alt="Logo"
                    width="32"
                    height="32"
                />
            </Link>
        </div>

        <div
            class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear"
        >
            <nav class="mb-6">
                <div class="flex flex-col gap-4">
                    <div
                        v-for="(menuGroup, groupIndex) in menuGroups"
                        :key="groupIndex"
                    >
                        <h2
                            :class="[
                                'mb-4 flex text-xs leading-5 text-gray-400 uppercase',
                                !isExpanded && !isHovered
                                    ? 'lg:justify-center'
                                    : 'justify-start',
                            ]"
                        >
                            <template
                                v-if="isExpanded || isHovered || isMobileOpen"
                            >
                                {{ menuGroup.title }}
                            </template>
                            <HorizontalDots v-else />
                        </h2>

                        <ul class="flex flex-col gap-4">
                            <li
                                v-for="(item, index) in menuGroup.items"
                                :key="index"
                            >
                                <!-- Item with subItems -->
                                <button
                                    v-if="item.subItems"
                                    @click="toggleSubmenu(groupIndex, index)"
                                    :class="[
                                        'menu-item group w-full',
                                        isSubmenuOpen(groupIndex, index)
                                            ? 'menu-item-active'
                                            : 'menu-item-inactive',
                                        !isExpanded && !isHovered
                                            ? 'lg:justify-center'
                                            : 'lg:justify-start',
                                    ]"
                                >
                                    <span
                                        :class="
                                            isSubmenuOpen(groupIndex, index)
                                                ? 'menu-item-icon-active'
                                                : 'menu-item-icon-inactive'
                                        "
                                    >
                                        <component :is="item.icon" />
                                    </span>
                                    <span
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        class="menu-item-text"
                                    >
                                        {{ item.name }}
                                    </span>
                                    <ChevronDownIcon
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        :class="[
                                            'ml-auto h-5 w-5 transition-transform duration-200',
                                            isSubmenuOpen(groupIndex, index)
                                                ? 'text-brand-500 rotate-180'
                                                : '',
                                        ]"
                                    />
                                </button>

                                <!-- Regular link item -->
                                <Link
                                    v-else-if="item.path"
                                    :href="item.path"
                                    :class="[
                                        'menu-item group',
                                        isActive(item.path, item.exact)
                                            ? 'menu-item-active'
                                            : 'menu-item-inactive',
                                        !isExpanded && !isHovered
                                            ? 'lg:justify-center'
                                            : 'lg:justify-start',
                                    ]"
                                >
                                    <span
                                        :class="
                                            isActive(item.path, item.exact)
                                                ? 'menu-item-icon-active'
                                                : 'menu-item-icon-inactive'
                                        "
                                    >
                                        <component :is="item.icon" />
                                    </span>
                                    <span
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        class="menu-item-text"
                                    >
                                        {{ item.name }}
                                    </span>
                                </Link>

                                <!-- Submenu dropdown -->
                                <transition
                                    @enter="startTransition"
                                    @after-enter="endTransition"
                                    @before-leave="startTransition"
                                    @after-leave="endTransition"
                                >
                                    <div
                                        v-show="
                                            isSubmenuOpen(groupIndex, index) &&
                                            (isExpanded ||
                                                isHovered ||
                                                isMobileOpen)
                                        "
                                    >
                                        <ul class="mt-2 ml-9 space-y-1">
                                            <li
                                                v-for="(
                                                    subItem, index
                                                ) in item.subItems"
                                                :key="index"
                                            >
                                                <Link
                                                    :href="subItem.path"
                                                    :class="[
                                                        'menu-dropdown-item',
                                                        isActive(
                                                            subItem.path,
                                                            subItem.exact,
                                                        )
                                                            ? 'menu-dropdown-item-active'
                                                            : 'menu-dropdown-item-inactive',
                                                    ]"
                                                >
                                                    {{ subItem.name }}
                                                </Link>
                                            </li>
                                        </ul>
                                    </div>
                                </transition>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h2
                            :class="[
                                'mb-4 flex text-xs leading-5 text-gray-400 uppercase',
                                !isExpanded && !isHovered
                                    ? 'lg:justify-center'
                                    : 'justify-start',
                            ]"
                        >
                            <template
                                v-if="isExpanded || isHovered || isMobileOpen"
                            >
                                Language
                            </template>
                            <HorizontalDots v-else />
                        </h2>
                        <ul class="flex flex-col gap-4">
                            <li>
                                <button
                                    @click="isOpen = !isOpen"
                                    :class="[
                                        'menu-item group w-full',
                                        isOpen
                                            ? 'menu-item-active'
                                            : 'menu-item-inactive',
                                        !isExpanded && !isHovered
                                            ? 'lg:justify-center'
                                            : 'lg:justify-start',
                                    ]"
                                >
                                    <span
                                        :class="
                                            isOpen
                                                ? 'menu-item-icon-active'
                                                : 'menu-item-icon-inactive'
                                        "
                                    >
                                        <GlobeIcon />
                                    </span>
                                    <span
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        class="menu-item-text"
                                    >
                                        {{ currentLocale?.label }}
                                    </span>
                                    <ChevronDownIcon
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        :class="[
                                            'ml-auto h-5 w-5 transition-transform duration-200',
                                            isOpen
                                                ? 'text-brand-500 rotate-180'
                                                : '',
                                        ]"
                                    />
                                </button>

                                <transition
                                    @enter="startTransition"
                                    @after-enter="endTransition"
                                    @before-leave="startTransition"
                                    @after-leave="endTransition"
                                >
                                    <div
                                        v-show="
                                            isOpen &&
                                            (isExpanded ||
                                                isHovered ||
                                                isMobileOpen)
                                        "
                                    >
                                        <ul class="mt-2 ml-9 space-y-1">
                                            <li
                                                v-for="locale in otherLocales"
                                                :key="locale.code"
                                            >
                                                <button
                                                    :disabled="isSwitching"
                                                    :class="[
                                                        'menu-dropdown-item w-full text-left',
                                                        'menu-dropdown-item-inactive',
                                                        isSwitching
                                                            ? 'cursor-wait opacity-60'
                                                            : '',
                                                    ]"
                                                    @click="
                                                        switchLocale(
                                                            locale.code,
                                                        )
                                                    "
                                                >
                                                    {{ locale.label }}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </transition>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </aside>
</template>

<script lang="ts" setup>
import { computed, ComputedRef, ref, type Component } from 'vue';

import {
    BoxIcon,
    BuildingIcon,
    ChevronDownIcon,
    GalleryIcon,
    GlobeIcon,
    GridIcon,
    HorizontalDots,
    ListIcon,
    PlugInIcon,
    StopwatchIcon,
} from '@/Components/manage/icons';
import { useLocale } from '@/composables/useLocale';
import { useSidebar } from '@/composables/useSidebar';
import { Locale, LocaleKey } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { loadLanguageAsync, wTrans } from 'laravel-vue-i18n';

interface SubMenuItem {
    name: ComputedRef<string>;
    path: string;
    exact?: boolean;
    pro?: boolean;
    new?: boolean;
}

interface MenuItem {
    name: ComputedRef<string>;
    icon?: Component;
    path?: string;
    exact?: boolean;
    subItems?: SubMenuItem[];
}

interface MenuGroup {
    title: string;
    items: MenuItem[];
}

const { lang, setLang } = useLocale();
const { isExpanded, isMobileOpen, isHovered, openSubmenu } = useSidebar();
const page = usePage();
const currentPath = computed((): string => page.url.split('?')[0]);

const locales: Locale[] = [
    { code: 'en', label: 'English' },
    { code: 'tm', label: 'Turkmençe' },
    { code: 'ru', label: 'Русский' },
];

const isSwitching = ref(false);
const isOpen = ref(false);

const currentLocale = computed((): Locale | undefined =>
    locales.find((loc) => loc.code === lang.value),
);

const otherLocales = computed((): Locale[] =>
    locales.filter((loc) => loc.code !== lang.value),
);

const menuGroups: MenuGroup[] = [
    {
        title: 'Menu',
        items: [
            {
                icon: GridIcon,
                name: wTrans('Dashboard'),
                path: '/manage',
                exact: true,
            },
            {
                icon: GalleryIcon,
                name: wTrans('Banners'),
                path: '/manage/banners',
                exact: true,
            },
            {
                icon: BuildingIcon,
                name: wTrans('About us'),
                path: '/manage/about',
                exact: true,
            },
            {
                icon: StopwatchIcon,
                name: wTrans('Counters'),
                path: '/manage/counters',
                exact: true,
            },
            {
                icon: PlugInIcon,
                name: wTrans('Services'),
                path: '/manage/services',
                exact: true,
            },
            {
                icon: BoxIcon,
                name: wTrans('Products'),
                path: '/manage/products',
                exact: true,
            },
            {
                name: wTrans('Forms'),
                icon: ListIcon,
                subItems: [
                    {
                        name: wTrans('Form Elements'),
                        path: '/manage/forms',
                        exact: true,
                        pro: false,
                    },
                ],
            },
        ],
    },
];

async function switchLocale(code: string): Promise<void> {
    if (isSwitching.value || code === lang.value) return;
    isSwitching.value = true;
    try {
        await loadLanguageAsync(code);
        setLang(code as LocaleKey);
        await axios.post(route('locale.switch'), { locale: code });
        isOpen.value = false;
    } finally {
        isSwitching.value = false;
    }
}

function isActive(path: string, exact: boolean = false): boolean {
    if (exact) return currentPath.value === path;
    return (
        currentPath.value === path || currentPath.value.startsWith(path + '/')
    );
}

function toggleSubmenu(groupIndex: number, itemIndex: number): void {
    const key = `${groupIndex}-${itemIndex}`;
    openSubmenu.value = openSubmenu.value === key ? null : key;
}

const isAnySubmenuRouteActive = computed((): boolean =>
    menuGroups.some((group) =>
        group.items.some((item) =>
            item.subItems?.some((subItem) =>
                isActive(subItem.path, subItem.exact),
            ),
        ),
    ),
);

function isSubmenuOpen(groupIndex: number, itemIndex: number): boolean {
    const key = `${groupIndex}-${itemIndex}`;
    return (
        openSubmenu.value === key ||
        (isAnySubmenuRouteActive.value &&
            (menuGroups[groupIndex].items[itemIndex].subItems?.some((subItem) =>
                isActive(subItem.path, subItem.exact),
            ) ??
                false))
    );
}

function startTransition(el: Element): void {
    const htmlEl = el as HTMLElement;
    htmlEl.style.height = 'auto';
    const height = htmlEl.scrollHeight;
    htmlEl.style.height = '0px';
    htmlEl.offsetHeight;
    htmlEl.style.height = `${height}px`;
}

function endTransition(el: Element): void {
    (el as HTMLElement).style.height = '';
}
</script>
