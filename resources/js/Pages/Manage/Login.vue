<template>
    <Head title="Login" />

    <FullScreenLayout>
        <div class="relative z-1 bg-white p-6 sm:p-0 dark:bg-gray-900">
            <div
                class="relative flex h-screen w-full flex-col justify-center lg:flex-row dark:bg-gray-900"
            >
                <div class="flex w-full flex-1 flex-col lg:w-1/2">
                    <div
                        class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center"
                    >
                        <div>
                            <div class="mb-5 sm:mb-8">
                                <h1
                                    class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90"
                                >
                                    Sign In
                                </h1>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Enter your email and password to sign in!
                                </p>
                            </div>
                            <div>
                                <form @submit.prevent="handleSubmit">
                                    <div class="space-y-5">
                                        <div>
                                            <InputLabel
                                                for="username"
                                                value="Username"
                                                required
                                            />
                                            <InputField
                                                id="username"
                                                type="text"
                                                name="username"
                                                required
                                                v-model="form.username"
                                                :error="form.errors.username"
                                                placeholder="Enter your username"
                                                autocomplete="username"
                                                autofocus="username"
                                            />
                                            <InputError
                                                :message="form.errors.username"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel
                                                for="password"
                                                value="Password"
                                                required
                                            />
                                            <InputField
                                                id="password"
                                                type="password"
                                                name="password"
                                                required
                                                v-model="form.password"
                                                :error="form.errors.password"
                                                :is-password-field="true"
                                                placeholder="Enter your password"
                                                autocomplete="password"
                                            />
                                            <InputError
                                                :message="form.errors.password"
                                            />
                                        </div>

                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <Checkbox
                                                    name="remember"
                                                    v-model:checked="
                                                        form.remember
                                                    "
                                                >
                                                    <span
                                                        >Keep me logged in</span
                                                    >
                                                </Checkbox>
                                            </div>
                                        </div>
                                        <!-- Button -->
                                        <div>
                                            <button
                                                type="submit"
                                                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition"
                                            >
                                                Sign In
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-brand-950 relative hidden h-full w-full items-center lg:grid lg:w-1/2 dark:bg-white/5"
                >
                    <div class="z-1 flex items-center justify-center">
                        <CommonGridShape />
                        <div class="flex max-w-xs flex-col items-center">
                            <a class="mb-4 block" href="/">
                                <img
                                    width="{231}"
                                    height="{48}"
                                    src="/images/logo/auth-logo.svg"
                                    alt="Logo"
                                />
                            </a>
                            <p
                                class="text-center text-gray-400 dark:text-white/60"
                            >
                                Free and Open-Source Tailwind CSS Admin
                                Dashboard Template
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FullScreenLayout>
</template>

<script setup lang="ts">
import CommonGridShape from '@/Components/manage/common/CommonGridShape.vue';
import Checkbox from '@/Components/manage/forms/Checkbox.vue';
import InputError from '@/Components/manage/forms/InputError.vue';
import InputField from '@/Components/manage/forms/InputField.vue';
import InputLabel from '@/Components/manage/forms/InputLabel.vue';
import FullScreenLayout from '@/Layouts/manage/FullScreenLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const handleSubmit = () => {
    form.post(route('manage.login.store'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>
