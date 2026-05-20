<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Send } from '@lucide/vue';
import { NButton, NInput } from 'naive-ui';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import StatusMessage from '@/Components/StatusMessage.vue';
import { useLanguage } from '@/lib/i18n';

defineProps({ status: String });

const form = useForm({ email: '' });
const { t } = useLanguage();
</script>

<template>
    <Head :title="t('forgotTitle')" />

    <AuthCard :title="t('forgotTitle')" :subtitle="t('forgotSubtitle')" :tab="t('forgotPasswordTab')">
        <StatusMessage :message="status ? t(status) : ''" />

        <form class="space-y-5" @submit.prevent="form.post(route('password.email'))">
            <div>
                <label class="kit-label">{{ t('email') }}</label>
                <NInput v-model:value="form.email" autofocus />
                <FormError :message="form.errors.email" />
            </div>

            <NButton type="primary" attr-type="submit" block :loading="form.processing">
                <template #icon><Send :size="17" /></template>
                {{ t('sendLink') }}
            </NButton>
        </form>

        <Link :href="route('login')" class="kit-link mt-6 block text-center text-sm">{{ t('backToLogin') }}</Link>
    </AuthCard>
</template>
