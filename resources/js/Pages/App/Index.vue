<template>
    <Head title="Home page" />

    <header class="bg-brand-500">
        <nav class="h-container py-7 font-semibold text-white">
            <div class="flex items-center justify-between">
                <Link href="/">
                    <img
                        src="/images/logo/logo-light.svg"
                        alt="logo light"
                        class="h-7.5 w-auto"
                    />
                </Link>

                <div
                    class="hidden md:flex md:flex-1 md:items-center md:justify-center"
                >
                    <ul
                        class="mx-auto hidden flex-col space-x-6 md:flex md:flex-row lg:space-x-7 xl:space-x-8 2xl:space-x-9"
                    >
                        <li>
                            <a href="#about">О нас</a>
                        </li>
                        <li>
                            <a href="#">Наши производители</a>
                        </li>
                        <li>
                            <a href="#">Маркет</a>
                        </li>
                        <li>
                            <a href="#">Ассортимент продукции</a>
                        </li>
                        <li>
                            <a href="#">Наши клиенты</a>
                        </li>
                        <li>
                            <a href="#">Контакты</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <a href="#" class="flex flex-row items-center space-x-2">
                        <span>Ru</span>

                        <svg
                            width="12"
                            height="7"
                            viewBox="0 0 12 7"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M11.3075 1.05375L5.65375 6.7075L6.74203e-08 1.05375L1.05375 1.25659e-08L5.65375 4.6L10.2538 1.22275e-07L11.3075 1.05375Z"
                                fill="currentColor"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section id="banner">
            <div class="h-190">
                <img
                    src="/uploads/banners/banner2.jpg"
                    alt="banner 2"
                    class="h-full w-full object-cover object-center"
                />
            </div>
        </section>

        <section id="counter" class="h-container py-15">
            <div
                class="grid grid-cols-4 justify-items-center divide-x divide-[#b2b2b2]/30 text-center"
            >
                <div
                    v-for="counter in counters.data"
                    :key="counter.id"
                    class="text-brand-500 space-y-5 px-10"
                >
                    <div class="text-6xl font-bold">
                        <span>{{ counter.number }}</span>
                        <span v-if="counter.symbol">{{ counter.symbol }}</span>
                    </div>
                    <div class="text-off-black text-[1.75rem] leading-tight">
                        {{ t(counter.title) }}
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="h-container">
            <Header :title="t(about.data.title)" />

            <div class="bg-brand-500 rounded-xl px-10 py-16 text-white">
                <div class="grid grid-cols-2 lg:gap-11 xl:gap-16">
                    <div class="content-center">
                        <div
                            class="mb-10 text-[2.5rem] leading-[1.2] font-semibold"
                        >
                            {{ t(about.data.body) }}
                        </div>
                        <div class="text-2xl leading-tight font-medium">
                            {{ t(about.data.context) }}
                        </div>
                    </div>
                    <div class="h-108 overflow-hidden rounded-xl">
                        <img
                            :src="about.data.image"
                            alt="About image"
                            class="full-object"
                        />
                    </div>
                </div>
            </div>
        </section>

        <section class="h-container text-brand-500 py-18">
            <div class="grid grid-cols-2 gap-5 lg:gap-6 xl:gap-8">
                <div
                    v-for="service in services.data"
                    :key="service.id"
                    class="bg-brand-25 relative h-87.5 content-end rounded-xl p-8"
                >
                    <div class="w-full lg:w-125">
                        <div
                            v-html="service.icon"
                            class="absolute top-8 left-8"
                        ></div>
                        <div>
                            <div
                                class="mb-4 text-[2rem] leading-[1.15] font-bold"
                            >
                                {{ t(service.title) }}
                            </div>
                            <div class="text-xl leading-tight">
                                {{ t(service.description) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="manufacturers" class="h-container py-18">
            <Header :title="$t('Our manufacturers')" />

            <div class="grid grid-cols-4 gap-x-25 gap-y-6">
                <div v-for="manufacturer in manufacturers.data">
                    <img
                        :src="manufacturer.image"
                        alt="Manufacturer image"
                        class="h-15 w-auto"
                    />
                </div>
            </div>
        </section>

        <section id="market" class="h-container py-18">
            <Header :title="t(about.data.market_title)" />

            <div class="h-190 overflow-hidden rounded-xl">
                <img
                    :src="about.data.market_image"
                    alt="Market image"
                    class="full-object"
                />
            </div>
        </section>

        <section id="product_range" class="h-container py-18">
            <Header :title="$t('Product range')" />

            <div class="mb-13 grid grid-cols-2 content-end">
                <div class="text-off-black text-2xl font-medium">
                    {{ t(about.data.product_range) }}
                </div>
                <div>a</div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div
                    class="relative h-113.5 overflow-hidden rounded-xl"
                    v-for="product in products.data"
                >
                    <img
                        :src="product.image"
                        alt="product image"
                        class="full-object"
                    />
                </div>
            </div>
        </section>

        <section id="clients" class="h-container py-18">
            <Header :title="$t('Our clients')" />

            <div class="grid grid-cols-4 gap-x-25 gap-y-6">
                <div v-for="client in clients.data">
                    <img
                        :src="client.image"
                        alt="Client image"
                        class="h-15 w-auto"
                    />
                </div>
            </div>
        </section>
    </main>

    <footer id="contact" class="bg-brand-500 py-23.75 text-white">
        <div class="h-container">
            <h1 class="mb-17.5 text-[2.75rem] font-bold uppercase">
                {{ $t('Get in touch') }}
            </h1>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="text-xl font-medium">
                    <div class="space-y-16.5">
                        <div
                            class="space-y-3"
                            v-for="contact in contacts.data"
                            :key="contact.id"
                        >
                            <div class="text-light-gray">
                                {{ $t(contact.slug) }}
                            </div>
                            <div class="font-semibold">
                                <a
                                    v-if="contact.slug === 'email'"
                                    :href="'mailto:' + contact.value"
                                    target="_blank"
                                    >{{ contact.value }}</a
                                >

                                <a
                                    v-else-if="contact.slug === 'phone_number'"
                                    :href="'tel:' + contact.value"
                                    target="_blank"
                                    >{{ contact.value }}</a
                                >
                                <span v-else class="block w-full lg:w-89.5">{{
                                    t(contact.locale_value)
                                }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="text-light-gray"></div>
                            <div class="flex space-x-4">
                                <a
                                    v-for="social in socials.data"
                                    :key="social.id"
                                    :href="String(social.value)"
                                >
                                    <span
                                        v-html="social.icon"
                                        class="bg-brand-25 block rounded-full p-3"
                                    ></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <form action="">
                        <div class="space-y-7">
                            <div>
                                <InputLabel
                                    for="email"
                                    :value="$t('Email address')"
                                />
                                <InputField
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    v-model="form.email"
                                    :error="form.errors.email"
                                    :placeholder="$t('Your email')"
                                />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div>
                                <InputLabel
                                    for="message"
                                    :value="$t('Message')"
                                />
                                <InputField
                                    id="message"
                                    name="message"
                                    required
                                    :multiline="true"
                                    v-model="form.message"
                                    :error="form.errors.message"
                                    :placeholder="$t('Write your message...')"
                                />
                                <InputError :message="form.errors.message" />
                            </div>

                            <div>
                                <button
                                    class="bg-brand-25 text-brand-500 block w-full cursor-pointer rounded-xl p-5 text-xl font-semibold"
                                >
                                    {{ $t('Send message') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
import InputError from '@/Components/app/forms/InputError.vue';
import InputField from '@/Components/app/forms/InputField.vue';
import InputLabel from '@/Components/app/forms/InputLabel.vue';
import Header from '@/Components/app/Header.vue';
import { useTranslatable } from '@/composables/useLocale';
import {
    About,
    Client,
    Contact,
    Counter,
    Manufacturer,
    Product,
    ResourceResponse,
    Service,
} from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const { t } = useTranslatable();
const form = useForm({
    email: '',
    message: '',
});

defineProps<{
    counters: ResourceResponse<Counter[]>;
    about: ResourceResponse<About>;
    products: ResourceResponse<Product[]>;
    manufacturers: ResourceResponse<Manufacturer[]>;
    services: ResourceResponse<Service[]>;
    clients: ResourceResponse<Client[]>;
    contacts: ResourceResponse<Contact[]>;
    socials: ResourceResponse<Contact[]>;
}>();
</script>
