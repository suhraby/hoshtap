<template>
    <Head :title="currentPageTitle" />
    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />

        <ComponentCard>
            <div
                class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800"
            >
                <div
                    class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between"
                >
                    <div>
                        <h4
                            class="text-lg font-semibold text-gray-800 lg:mb-6 dark:text-white/90"
                        >
                            {{ $t('Profile information') }}
                        </h4>

                        <div
                            class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32"
                        >
                            <div>
                                <p
                                    class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400"
                                >
                                    {{ $t('Full name') }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-800 dark:text-white/90"
                                >
                                    {{ user.data.name }}
                                </p>
                            </div>

                            <div>
                                <p
                                    class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400"
                                >
                                    {{ $t('Username') }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-800 dark:text-white/90"
                                >
                                    {{ user.data.username }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button class="edit-button" @click="isOpen = true">
                        <PencilIcon />
                        {{ $t('Edit') }}
                    </button>
                </div>
                <Modal
                    :is-open="isOpen"
                    class-name="max-w-[584px] p-5 lg:p-10"
                    @close="closeModal"
                >
                    <form @submit.prevent="onSubmit">
                        <h4
                            class="mb-6 text-lg font-medium text-gray-800 dark:text-white/90"
                        >
                            {{ $t('Profile information') }}
                        </h4>

                        <div class="space-6 grid grid-cols-1 gap-6">
                            <div>
                                <InputLabel
                                    for="name"
                                    :value="$t('Full name')"
                                    required
                                />
                                <InputField
                                    id="name"
                                    type="text"
                                    name="name"
                                    required
                                    v-model="form.name"
                                    :error="form.errors.name"
                                    :placeholder="$t('Enter the full name')"
                                    autocomplete="name"
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div>
                                <InputLabel
                                    for="username"
                                    :value="$t('Username')"
                                    required
                                />
                                <InputField
                                    id="username"
                                    type="username"
                                    name="username"
                                    required
                                    v-model="form.username"
                                    :error="form.errors.username"
                                    :placeholder="$t('Enter the username')"
                                    autocomplete="username"
                                />
                                <InputError :message="form.errors.username" />
                            </div>

                            <div class="mb-4">
                                <InputLabel
                                    for="password"
                                    :value="$t('Password')"
                                />
                                <InputField
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    :error="form.errors.password"
                                    :placeholder="$t('Enter the password')"
                                />
                                <InputError :message="form.errors.password" />
                            </div>
                        </div>

                        <Button type="submit" size="sm" class="mt-4">
                            {{ $t('Save') }}
                        </Button>
                    </form>
                </Modal>
            </div>
        </ComponentCard>
    </AdminLayout>
</template>

<script setup lang="ts">
import ComponentCard from '@/Components/manage/common/ComponentCard.vue';
import PageBreadcrumb from '@/Components/manage/common/PageBreadcrumb.vue';
import InputError from '@/Components/manage/forms/InputError.vue';
import InputField from '@/Components/manage/forms/InputField.vue';
import InputLabel from '@/Components/manage/forms/InputLabel.vue';
import { PencilIcon } from '@/Components/manage/icons';
import Button from '@/Components/manage/ui/Button.vue';
import Modal from '@/Components/manage/ui/Modal.vue';
import { useModal } from '@/composables/useModal';
import AdminLayout from '@/Layouts/manage/AdminLayout.vue';
import { ResourceResponse, User } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';

const props = defineProps<{
    user: ResourceResponse<User>;
}>();

const currentPageTitle = wTrans('Update profile');

const { isOpen, closeModal } = useModal();

const form = useForm({
    name: props.user.data.name,
    username: props.user.data.username,
    password: '',
});

const onSubmit = () => {
    form.patch(route('manage.profile.update', props.user.data.id), {
        onFinish: () => {
            form.reset('password');
        },
    });

    isOpen.value = false;
};
</script>
