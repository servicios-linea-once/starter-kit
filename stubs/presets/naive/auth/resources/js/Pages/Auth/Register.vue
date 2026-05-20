<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UserPlus } from '@lucide/vue';
import { NButton, NInput } from 'naive-ui';
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
                <label class="kit-label">{{ t('name') }}</label>
                <NInput v-model:value="form.name" autocomplete="name" autofocus />
                <FormError :message="form.errors.name" />
            </div>

            <div>
                <label class="kit-label">{{ t('email') }}</label>
                <NInput v-model:value="form.email" autocomplete="username" />
                <FormError :message="form.errors.email" />
            </div>

            <div>
                <label class="kit-label">{{ t('password') }}</label>
                <NInput v-model:value="form.password" type="password" show-password-on="click" autocomplete="new-password" />
                <FormError :message="form.errors.password" />
            </div>

            <div>
                <label class="kit-label">{{ t('confirmPassword') }}</label>
                <NInput v-model:value="form.password_confirmation" type="password" show-password-on="click" autocomplete="new-password" />
                <FormError :message="form.errors.password_confirmation" />
            </div>

            <NButton type="primary" attr-type="submit" block :loading="form.processing">
                <template #icon><UserPlus :size="17" /></template>
                {{ t('register') }}
            </NButton>
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            {{ t('alreadyAccount') }}
            <Link :href="route('login')" class="kit-link">{{ t('login') }}</Link>
        </p>
    </AuthCard>
</template>
