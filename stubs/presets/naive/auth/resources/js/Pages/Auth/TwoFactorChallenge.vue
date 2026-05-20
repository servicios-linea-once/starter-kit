<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, KeyRound, Shield } from '@lucide/vue';
import { NButton, NInput } from 'naive-ui';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import { useLanguage } from '@/lib/i18n';

const form = useForm({
    code: '',
    recovery_code: '',
});

const { t } = useLanguage();

const submitCode = () => {
    form.recovery_code = '';
    form.post(route('two-factor.verify'));
};

const submitRecovery = () => {
    form.code = '';
    form.post(route('two-factor.verify'));
};

const cancelChallenge = () => router.delete(route('two-factor.cancel'));
</script>

<template>
    <Head :title="t('twoFactorChallengeTitle')" />

    <AuthCard :title="t('twoFactorTitle')" :subtitle="t('twoFactorSubtitle')" :tab="t('twoFactorTab')">
        <form class="space-y-4" @submit.prevent="submitCode">
            <div>
                <label class="kit-label">{{ t('authCode') }}</label>
                <NInput v-model:value="form.code" autofocus inputmode="numeric" class="tracking-[0.3em]" />
                <FormError :message="form.errors.code" />
            </div>
            <NButton type="primary" attr-type="submit" block :loading="form.processing">
                <template #icon><Shield :size="17" /></template>
                {{ t('verifyCode') }}
            </NButton>
        </form>

        <div class="my-6 h-px bg-white/10" />

        <form class="space-y-4" @submit.prevent="submitRecovery">
            <div>
                <label class="kit-label">{{ t('recoveryCode') }}</label>
                <NInput v-model:value="form.recovery_code" class="uppercase" />
                <FormError :message="form.errors.recovery_code" />
            </div>
            <NButton secondary attr-type="submit" block :loading="form.processing">
                <template #icon><KeyRound :size="17" /></template>
                {{ t('useRecovery') }}
            </NButton>
        </form>

        <NButton quaternary block class="mt-5" @click="cancelChallenge">
            <template #icon><ArrowLeft :size="17" /></template>
            {{ t('backToLogin') }}
        </NButton>
    </AuthCard>
</template>
