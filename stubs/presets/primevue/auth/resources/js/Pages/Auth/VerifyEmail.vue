<script setup>
import { Head, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
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
            <Button :label="t('resendVerification')" icon="pi pi-envelope" @click="router.post(route('verification.send'))" />
            <Button :label="t('logout')" icon="pi pi-sign-out" severity="secondary" outlined @click="router.post(route('logout'))" />
        </div>
    </AuthCard>
</template>
