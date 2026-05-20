<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { NButton, NInput } from 'naive-ui';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import { useLanguage } from '@/lib/i18n';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const { t } = useLanguage();

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="t('resetTitle')" />

    <AuthCard :title="t('resetTitle')" :subtitle="t('resetSubtitle')" :tab="t('resetPasswordTab')">
        <form class="space-y-5" @submit.prevent="submit">
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
                <template #icon><Check :size="17" /></template>
                {{ t('savePassword') }}
            </NButton>
        </form>
    </AuthCard>
</template>
