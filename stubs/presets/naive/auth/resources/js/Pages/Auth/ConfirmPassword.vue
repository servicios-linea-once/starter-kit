<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Shield } from '@lucide/vue';
import { NButton, NInput } from 'naive-ui';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import { useLanguage } from '@/lib/i18n';

const form = useForm({ password: '' });
const { t } = useLanguage();
</script>

<template>
    <Head :title="t('confirmPasswordTitle')" />

    <AuthCard :title="t('confirmPasswordTitle')" :subtitle="t('confirmPasswordSubtitle')" :tab="t('confirmPasswordTab')">
        <form class="space-y-5" @submit.prevent="form.post(route('password.confirm.store'))">
            <div>
                <label class="kit-label">{{ t('password') }}</label>
                <NInput v-model:value="form.password" type="password" show-password-on="click" autofocus autocomplete="current-password" />
                <FormError :message="form.errors.password" />
            </div>

            <NButton type="primary" attr-type="submit" block :loading="form.processing">
                <template #icon><Shield :size="17" /></template>
                {{ t('confirm') }}
            </NButton>
        </form>
    </AuthCard>
</template>
