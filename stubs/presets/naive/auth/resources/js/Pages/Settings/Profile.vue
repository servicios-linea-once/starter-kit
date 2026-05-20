<script setup>
import { computed } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import {
    Check,
    KeyRound,
    QrCode,
    RefreshCw,
    Save,
    ShieldOff,
    Trash2,
    LogOut,
} from '@lucide/vue';
import { NButton, NCard, NInput, NTag } from 'naive-ui';
import AppLayout from '@/Layouts/AppLayout.vue';
import FormError from '@/Components/FormError.vue';
import StatusMessage from '@/Components/StatusMessage.vue';
import { useLanguage } from '@/lib/i18n';

const props = defineProps({
    sessions: { type: Array, default: () => [] },
    status: String,
});

const page = usePage();
const { t } = useLanguage();
const user = computed(() => page.props.auth.user);
const twoFactorSetup = computed(() => page.props.flash.two_factor_setup);
const recoveryCodes = computed(() => page.props.flash.recovery_codes || []);

const profileForm = useForm({
    name: user.value.name,
    email: user.value.email,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const confirm2faForm = useForm({ code: '' });
const disable2faForm = useForm({ password: '' });
const sessionsForm = useForm({ password: '' });
const deleteForm = useForm({ password: '' });
const recoveryForm = useForm({ password: '' });

const updateProfile = () => profileForm.patch(route('profile.update'));

const updatePassword = () => {
    passwordForm.put(route('password.update'), {
        onSuccess: () => passwordForm.reset(),
    });
};

const enableTwoFactor = () => router.post(route('two-factor.enable'));

const confirmTwoFactor = () => {
    confirm2faForm.post(route('two-factor.confirm'), {
        onSuccess: () => confirm2faForm.reset(),
    });
};

const disableTwoFactor = () => {
    disable2faForm.delete(route('two-factor.disable'), {
        onSuccess: () => disable2faForm.reset(),
    });
};

const logoutOtherSessions = () => {
    sessionsForm.delete(route('sessions.destroy'), {
        onSuccess: () => sessionsForm.reset(),
    });
};

const regenerateCodes = () => {
    recoveryForm.post(route('two-factor.recovery-codes'), {
        onSuccess: () => recoveryForm.reset(),
    });
};

const deleteAccount = () => deleteForm.delete(route('profile.destroy'));
</script>

<template>
    <Head :title="t('profile')" />

    <AppLayout>
        <section class="mx-auto max-w-6xl px-6 py-10">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-cyan-200">{{ t('settings') }}</p>
                <h1 class="mt-2 text-4xl font-black tracking-[0]">{{ t('profileSecurity') }}</h1>
            </div>

            <StatusMessage :message="props.status ? t(props.status) : ''" />

            <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                <div class="space-y-6">
                    <NCard :title="t('profileData')">
                        <form class="space-y-5" @submit.prevent="updateProfile">
                            <div>
                                <label class="kit-label">{{ t('name') }}</label>
                                <NInput v-model:value="profileForm.name" />
                                <FormError :message="profileForm.errors.name" />
                            </div>
                            <div>
                                <label class="kit-label">{{ t('email') }}</label>
                                <NInput v-model:value="profileForm.email" />
                                <FormError :message="profileForm.errors.email" />
                            </div>
                            <NButton type="primary" attr-type="submit" :loading="profileForm.processing">
                                <template #icon><Save :size="17" /></template>
                                {{ t('saveProfile') }}
                            </NButton>
                        </form>
                    </NCard>

                    <NCard :title="t('changePassword')">
                        <form class="space-y-5" @submit.prevent="updatePassword">
                            <div>
                                <label class="kit-label">{{ t('currentPassword') }}</label>
                                <NInput v-model:value="passwordForm.current_password" type="password" show-password-on="click" />
                                <FormError :message="passwordForm.errors.current_password" />
                            </div>
                            <div>
                                <label class="kit-label">{{ t('newPassword') }}</label>
                                <NInput v-model:value="passwordForm.password" type="password" show-password-on="click" />
                                <FormError :message="passwordForm.errors.password" />
                            </div>
                            <div>
                                <label class="kit-label">{{ t('confirmPassword') }}</label>
                                <NInput v-model:value="passwordForm.password_confirmation" type="password" show-password-on="click" />
                            </div>
                            <NButton type="primary" attr-type="submit" :loading="passwordForm.processing">
                                <template #icon><KeyRound :size="17" /></template>
                                {{ t('updatePassword') }}
                            </NButton>
                        </form>
                    </NCard>
                </div>

                <div class="space-y-6">
                    <NCard>
                        <template #title>
                            <div class="flex items-center justify-between gap-3">
                                <span>{{ t('twoFactor') }}</span>
                                <NTag :type="user.two_factor_enabled ? 'success' : 'warning'" :bordered="false">
                                    {{ user.two_factor_enabled ? t('enabled') : t('disabled') }}
                                </NTag>
                            </div>
                        </template>

                        <div v-if="!user.two_factor_enabled" class="space-y-4">
                            <p class="text-sm leading-6 text-slate-400">{{ t('twoFactorBody') }}</p>
                            <NButton v-if="!twoFactorSetup" type="primary" @click="enableTwoFactor">
                                <template #icon><QrCode :size="17" /></template>
                                {{ t('generateQr') }}
                            </NButton>

                            <div v-if="twoFactorSetup" class="space-y-4">
                                <div class="inline-block rounded-lg bg-white p-4" v-html="twoFactorSetup.qr_code_svg" />
                                <p class="break-all rounded-lg bg-slate-900 p-3 font-mono text-xs text-cyan-100">{{ twoFactorSetup.secret }}</p>
                                <form class="space-y-3" @submit.prevent="confirmTwoFactor">
                                    <NInput v-model:value="confirm2faForm.code" class="tracking-[0.3em]" :placeholder="t('sixDigitCode')" />
                                    <FormError :message="confirm2faForm.errors.code" />
                                    <NButton type="primary" attr-type="submit" :loading="confirm2faForm.processing">
                                        <template #icon><Check :size="17" /></template>
                                        {{ t('confirm2fa') }}
                                    </NButton>
                                </form>
                            </div>
                        </div>

                        <div v-else class="space-y-4">
                            <form class="space-y-3" @submit.prevent="disableTwoFactor">
                                <NInput v-model:value="disable2faForm.password" type="password" show-password-on="click" :placeholder="t('disable2faPlaceholder')" />
                                <FormError :message="disable2faForm.errors.password" />
                                <NButton type="error" secondary attr-type="submit" :loading="disable2faForm.processing">
                                    <template #icon><ShieldOff :size="17" /></template>
                                    {{ t('disable2fa') }}
                                </NButton>
                            </form>

                            <form class="space-y-3" @submit.prevent="regenerateCodes">
                                <NInput v-model:value="recoveryForm.password" type="password" show-password-on="click" :placeholder="t('regenerateCodesPlaceholder')" />
                                <NButton secondary attr-type="submit" :loading="recoveryForm.processing">
                                    <template #icon><RefreshCw :size="17" /></template>
                                    {{ t('regenerateCodes') }}
                                </NButton>
                            </form>
                        </div>

                        <NInput
                            v-if="recoveryCodes.length"
                            :value="recoveryCodes.join('\n')"
                            type="textarea"
                            class="mt-5 font-mono text-sm"
                            :autosize="{ minRows: 8, maxRows: 8 }"
                            readonly
                        />
                    </NCard>

                    <NCard :title="t('activeSessions')">
                        <div class="mb-4 space-y-3">
                            <div v-for="session in sessions" :key="session.id" class="rounded-lg border border-white/10 p-3">
                                <p class="text-sm font-semibold">{{ session.ip_address || t('unknownIp') }}</p>
                                <p class="line-clamp-2 text-xs text-slate-400">{{ session.user_agent || t('unknownAgent') }}</p>
                                <NTag v-if="session.is_current_device" class="mt-2" type="success" size="small" :bordered="false">{{ t('current') }}</NTag>
                            </div>
                        </div>
                        <form class="space-y-3" @submit.prevent="logoutOtherSessions">
                            <NInput v-model:value="sessionsForm.password" type="password" show-password-on="click" :placeholder="t('password')" />
                            <FormError :message="sessionsForm.errors.password" />
                            <NButton secondary attr-type="submit" :loading="sessionsForm.processing">
                                <template #icon><LogOut :size="17" /></template>
                                {{ t('closeOtherSessions') }}
                            </NButton>
                        </form>
                    </NCard>

                    <NCard :title="t('deleteAccount')">
                        <form class="space-y-3" @submit.prevent="deleteAccount">
                            <p class="text-sm leading-6 text-slate-400">{{ t('deleteAccountBody') }}</p>
                            <NInput v-model:value="deleteForm.password" type="password" show-password-on="click" :placeholder="t('password')" />
                            <FormError :message="deleteForm.errors.password" />
                            <NButton type="error" attr-type="submit" :loading="deleteForm.processing">
                                <template #icon><Trash2 :size="17" /></template>
                                {{ t('deleteAccount') }}
                            </NButton>
                        </form>
                    </NCard>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
