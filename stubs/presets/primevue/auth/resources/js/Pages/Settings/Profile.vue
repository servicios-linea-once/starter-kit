<script setup>
import { computed } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Tag from 'primevue/tag';
import Textarea from 'primevue/textarea';
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
                    <Card>
                        <template #title>{{ t('profileData') }}</template>
                        <template #content>
                            <form class="space-y-5" @submit.prevent="updateProfile">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">{{ t('name') }}</label>
                                    <InputText v-model="profileForm.name" class="w-full" />
                                    <FormError :message="profileForm.errors.name" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">{{ t('email') }}</label>
                                    <InputText v-model="profileForm.email" type="email" class="w-full" />
                                    <FormError :message="profileForm.errors.email" />
                                </div>
                                <Button type="submit" :label="t('saveProfile')" icon="pi pi-save" :loading="profileForm.processing" />
                            </form>
                        </template>
                    </Card>

                    <Card>
                        <template #title>{{ t('changePassword') }}</template>
                        <template #content>
                            <form class="space-y-5" @submit.prevent="updatePassword">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">{{ t('currentPassword') }}</label>
                                    <Password v-model="passwordForm.current_password" class="w-full" input-class="w-full" :feedback="false" toggle-mask />
                                    <FormError :message="passwordForm.errors.current_password" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">{{ t('newPassword') }}</label>
                                    <Password v-model="passwordForm.password" class="w-full" input-class="w-full" toggle-mask />
                                    <FormError :message="passwordForm.errors.password" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">{{ t('confirmPassword') }}</label>
                                    <Password v-model="passwordForm.password_confirmation" class="w-full" input-class="w-full" :feedback="false" toggle-mask />
                                </div>
                                <Button type="submit" :label="t('updatePassword')" icon="pi pi-key" :loading="passwordForm.processing" />
                            </form>
                        </template>
                    </Card>
                </div>

                <div class="space-y-6">
                    <Card>
                        <template #title>
                            <div class="flex items-center justify-between gap-3">
                                <span>{{ t('twoFactor') }}</span>
                                <Tag :severity="user.two_factor_enabled ? 'success' : 'warn'" :value="user.two_factor_enabled ? t('enabled') : t('disabled')" />
                            </div>
                        </template>
                        <template #content>
                            <div v-if="!user.two_factor_enabled" class="space-y-4">
                                <p class="text-sm leading-6 text-slate-400">{{ t('twoFactorBody') }}</p>
                                <Button v-if="!twoFactorSetup" :label="t('generateQr')" icon="pi pi-qrcode" @click="enableTwoFactor" />

                                <div v-if="twoFactorSetup" class="space-y-4">
                                    <div class="inline-block rounded-2xl bg-white p-4" v-html="twoFactorSetup.qr_code_svg" />
                                    <p class="break-all rounded-xl bg-slate-900 p-3 font-mono text-xs text-cyan-100">{{ twoFactorSetup.secret }}</p>
                                    <form class="space-y-3" @submit.prevent="confirmTwoFactor">
                                        <InputText v-model="confirm2faForm.code" class="w-full tracking-[0.3em]" :placeholder="t('sixDigitCode')" />
                                        <FormError :message="confirm2faForm.errors.code" />
                                        <Button type="submit" :label="t('confirm2fa')" icon="pi pi-check" :loading="confirm2faForm.processing" />
                                    </form>
                                </div>
                            </div>

                            <div v-else class="space-y-4">
                                <form class="space-y-3" @submit.prevent="disableTwoFactor">
                                    <Password v-model="disable2faForm.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask :placeholder="t('disable2faPlaceholder')" />
                                    <FormError :message="disable2faForm.errors.password" />
                                    <Button type="submit" :label="t('disable2fa')" icon="pi pi-times" severity="danger" outlined :loading="disable2faForm.processing" />
                                </form>

                                <form class="space-y-3" @submit.prevent="regenerateCodes">
                                    <Password v-model="recoveryForm.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask :placeholder="t('regenerateCodesPlaceholder')" />
                                    <Button type="submit" :label="t('regenerateCodes')" icon="pi pi-refresh" severity="secondary" outlined :loading="recoveryForm.processing" />
                                </form>
                            </div>

                            <Textarea v-if="recoveryCodes.length" :model-value="recoveryCodes.join('\n')" class="mt-5 w-full font-mono text-sm" rows="8" readonly />
                        </template>
                    </Card>

                    <Card>
                        <template #title>{{ t('activeSessions') }}</template>
                        <template #content>
                            <div class="mb-4 space-y-3">
                                <div v-for="session in sessions" :key="session.id" class="rounded-2xl border border-white/10 p-3">
                                    <p class="text-sm font-semibold">{{ session.ip_address || t('unknownIp') }}</p>
                                    <p class="line-clamp-2 text-xs text-slate-400">{{ session.user_agent || t('unknownAgent') }}</p>
                                    <Tag v-if="session.is_current_device" class="mt-2" severity="success" :value="t('current')" />
                                </div>
                            </div>
                            <form class="space-y-3" @submit.prevent="logoutOtherSessions">
                                <Password v-model="sessionsForm.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask :placeholder="t('password')" />
                                <FormError :message="sessionsForm.errors.password" />
                                <Button type="submit" :label="t('closeOtherSessions')" icon="pi pi-sign-out" severity="secondary" outlined :loading="sessionsForm.processing" />
                            </form>
                        </template>
                    </Card>

                    <Card>
                        <template #title>{{ t('deleteAccount') }}</template>
                        <template #content>
                            <form class="space-y-3" @submit.prevent="deleteAccount">
                                <p class="text-sm leading-6 text-slate-400">{{ t('deleteAccountBody') }}</p>
                                <Password v-model="deleteForm.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask :placeholder="t('password')" />
                                <FormError :message="deleteForm.errors.password" />
                                <Button type="submit" :label="t('deleteAccount')" icon="pi pi-trash" severity="danger" :loading="deleteForm.processing" />
                            </form>
                        </template>
                    </Card>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
