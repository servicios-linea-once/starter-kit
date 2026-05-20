<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import { useLanguage } from '@/lib/i18n';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const { t } = useLanguage();

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="t('register')" />

    <AuthCard :title="t('register')" :subtitle="t('registerSubtitle')" :tab="t('registerTab')">
        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('name') }}</label>
                <InputText v-model="form.name" class="w-full" autocomplete="name" autofocus />
                <FormError :message="form.errors.name" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('email') }}</label>
                <InputText v-model="form.email" type="email" class="w-full" autocomplete="username" />
                <FormError :message="form.errors.email" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('password') }}</label>
                <Password v-model="form.password" class="w-full" input-class="w-full" toggle-mask autocomplete="new-password" />
                <FormError :message="form.errors.password" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('confirmPassword') }}</label>
                <Password v-model="form.password_confirmation" class="w-full" input-class="w-full" :feedback="false" toggle-mask autocomplete="new-password" />
                <FormError :message="form.errors.password_confirmation" />
            </div>

            <Button type="submit" :label="t('register')" icon="pi pi-user-plus" class="w-full" :loading="form.processing" />
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            {{ t('alreadyAccount') }}
            <Link :href="route('login')" class="font-semibold text-cyan-200 hover:text-cyan-100">{{ t('login') }}</Link>
        </p>
    </AuthCard>
</template>
