<script setup>
import { Head, router } from '@inertiajs/vue3';
import { LogOut, Mail } from '@lucide/vue';
import { NButton } from 'naive-ui';
import AuthCard from '@/Components/AuthCard.vue';
import StatusMessage from '@/Components/StatusMessage.vue';
import { useLanguage } from '@/lib/i18n';

defineProps({ status: String });
const { t } = useLanguage();
</script>

<template>
    <Head :title="t('verifyEmailTitle')" />

    <AuthCard :title="t('verifyEmailTitle')" :subtitle="t('verifyEmailSubtitle')" :tab="t('verifyEmailTab')">
        <StatusMessage v-if="status === 'verification-link-sent'" :message="t('verificationSent')" />

        <div class="flex flex-col gap-3">
            <NButton type="primary" @click="router.post(route('verification.send'))">
                <template #icon><Mail :size="17" /></template>
                {{ t('resendVerification') }}
            </NButton>
            <NButton secondary @click="router.post(route('logout'))">
                <template #icon><LogOut :size="17" /></template>
                {{ t('logout') }}
            </NButton>
        </div>
    </AuthCard>
</template>
