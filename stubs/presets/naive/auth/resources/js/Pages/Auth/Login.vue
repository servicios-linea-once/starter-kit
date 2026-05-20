<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { LockOpen } from '@lucide/vue';
import { NButton, NCheckbox, NInput } from 'naive-ui';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import StatusMessage from '@/Components/StatusMessage.vue';
import { useLanguage } from '@/lib/i18n';

const props = defineProps({
    canResetPassword: Boolean,
    canRegister: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const { t } = useLanguage();

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head :title="t('login')" />

    <AuthCard :title="t('login')" :subtitle="t('loginSubtitle')" :tab="t('loginTab')">
        <StatusMessage :message="props.status ? t(props.status) : ''" />

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <label class="kit-label">{{ t('email') }}</label>
                <NInput v-model:value="form.email" autocomplete="username" autofocus />
                <FormError :message="form.errors.email" />
            </div>

            <div>
                <label class="kit-label">{{ t('password') }}</label>
                <NInput v-model:value="form.password" type="password" show-password-on="click" autocomplete="current-password" />
                <FormError :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between gap-4">
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <NCheckbox v-model:checked="form.remember" />
                    {{ t('rememberMe') }}
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')" class="kit-link text-sm">
                    {{ t('forgotPassword') }}
                </Link>
            </div>

            <NButton type="primary" attr-type="submit" block :loading="form.processing">
                <template #icon><LockOpen :size="17" /></template>
                {{ t('login') }}
            </NButton>
        </form>

        <p v-if="canRegister" class="mt-6 text-center text-sm text-slate-400">
            {{ t('noAccount') }}
            <Link :href="route('register')" class="kit-link">{{ t('register') }}</Link>
        </p>
    </AuthCard>
</template>
