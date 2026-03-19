<template>
    <template v-if="isMobile">
        <div class="text-lg font-semibold">{{ $t('Language') }}</div>
        <div class="space-y-2">
            <a
                v-for="loc in locales"
                :key="loc.code"
                @click="switchLocale(loc.code, true)"
                :class="[
                    'bg-off-white w-full rounded-[10px] px-3 py-3 font-medium lg:w-auto',
                    currentLocale?.label === loc.label
                        ? 'text-red-brand flex flex-row justify-between'
                        : 'text-charcoal block',
                ]"
            >
                <span>{{ loc.label }}</span>
                <CheckIconRed v-show="currentLocale?.label === loc.label" />
            </a>
        </div>
    </template>

    <template v-else>
        <DropdownMenu :modal="false">
            <DropdownMenuTrigger
                class="inline-flex w-full cursor-pointer items-center justify-center space-x-1 py-3 font-medium transition duration-300"
            >
                <GlobeIcon v-if="showIcon" />
                <span>{{ currentLocale?.label }}</span>
                <ChevronDownIcon />
            </DropdownMenuTrigger>
            <DropdownMenuContent
                class="rounded-xl border-none bg-white/20 px-2 font-medium backdrop-blur-md"
            >
                <DropdownMenuItem
                    v-for="loc in locales"
                    :key="loc.code"
                    @click="switchLocale(loc.code, false)"
                    :class="{
                        'font-bold': getActiveLanguage() === loc.code,
                        'cursor-pointer': getActiveLanguage() !== loc.code,
                    }"
                    class="language-item"
                >
                    {{ loc.label }}
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </template>
</template>

<script lang="ts" setup>
import CheckIconRed from '@/Components/Icons/CheckIconRed.vue';
import ChevronDownIcon from '@/Components/Icons/ChevronDownIcon.vue';
import GlobeIcon from '@/Components/Icons/GlobeIcon.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { useLocale } from '@/composables/useLocale';
import { getActiveLanguage } from 'laravel-vue-i18n';

const { lang, setLang } = useLocale();

withDefaults(
    defineProps<{
        showIcon?: boolean;
        isMobile?: boolean;
    }>(),
    {
        showIcon: true,
        isMobile: false,
    },
);

const emit = defineEmits<{
    switched: [];
}>();
</script>
