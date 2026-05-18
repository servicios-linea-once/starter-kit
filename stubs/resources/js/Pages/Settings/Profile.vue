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

const props = defineProps({
    sessions: { type: Array, default: () => [] },
    status: String,
});

const page = usePage();
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
    <Head title="Perfil" />

    <AppLayout>
        <section class="mx-auto max-w-6xl px-6 py-10">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-cyan-200">Settings</p>
                <h1 class="mt-2 text-4xl font-black tracking-[0]">Perfil y seguridad</h1>
            </div>

            <StatusMessage :message="props.status" />

            <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                <div class="space-y-6">
                    <Card>
                        <template #title>Datos de perfil</template>
                        <template #content>
                            <form class="space-y-5" @submit.prevent="updateProfile">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">Nombre</label>
                                    <InputText v-model="profileForm.name" class="w-full" />
                                    <FormError :message="profileForm.errors.name" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">Email</label>
                                    <InputText v-model="profileForm.email" type="email" class="w-full" />
                                    <FormError :message="profileForm.errors.email" />
                                </div>
                                <Button type="submit" label="Guardar perfil" icon="pi pi-save" :loading="profileForm.processing" />
                            </form>
                        </template>
                    </Card>

                    <Card>
                        <template #title>Cambiar contraseña</template>
                        <template #content>
                            <form class="space-y-5" @submit.prevent="updatePassword">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">Contraseña actual</label>
                                    <Password v-model="passwordForm.current_password" class="w-full" input-class="w-full" :feedback="false" toggle-mask />
                                    <FormError :message="passwordForm.errors.current_password" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">Nueva contraseña</label>
                                    <Password v-model="passwordForm.password" class="w-full" input-class="w-full" toggle-mask />
                                    <FormError :message="passwordForm.errors.password" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">Confirmar contraseña</label>
                                    <Password v-model="passwordForm.password_confirmation" class="w-full" input-class="w-full" :feedback="false" toggle-mask />
                                </div>
                                <Button type="submit" label="Actualizar contraseña" icon="pi pi-key" :loading="passwordForm.processing" />
                            </form>
                        </template>
                    </Card>
                </div>

                <div class="space-y-6">
                    <Card>
                        <template #title>
                            <div class="flex items-center justify-between gap-3">
                                <span>Two-factor authentication</span>
                                <Tag :severity="user.two_factor_enabled ? 'success' : 'warn'" :value="user.two_factor_enabled ? 'Activo' : 'Inactivo'" />
                            </div>
                        </template>
                        <template #content>
                            <div v-if="!user.two_factor_enabled" class="space-y-4">
                                <p class="text-sm leading-6 text-slate-400">Activa TOTP para proteger tu cuenta con una app autenticadora.</p>
                                <Button v-if="!twoFactorSetup" label="Generar QR" icon="pi pi-qrcode" @click="enableTwoFactor" />

                                <div v-if="twoFactorSetup" class="space-y-4">
                                    <div class="inline-block rounded-2xl bg-white p-4" v-html="twoFactorSetup.qr_code_svg" />
                                    <p class="break-all rounded-xl bg-slate-900 p-3 font-mono text-xs text-cyan-100">{{ twoFactorSetup.secret }}</p>
                                    <form class="space-y-3" @submit.prevent="confirmTwoFactor">
                                        <InputText v-model="confirm2faForm.code" class="w-full tracking-[0.3em]" placeholder="Código de 6 dígitos" />
                                        <FormError :message="confirm2faForm.errors.code" />
                                        <Button type="submit" label="Confirmar 2FA" icon="pi pi-check" :loading="confirm2faForm.processing" />
                                    </form>
                                </div>
                            </div>

                            <div v-else class="space-y-4">
                                <form class="space-y-3" @submit.prevent="disableTwoFactor">
                                    <Password v-model="disable2faForm.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask placeholder="Contraseña para desactivar" />
                                    <FormError :message="disable2faForm.errors.password" />
                                    <Button type="submit" label="Desactivar 2FA" icon="pi pi-times" severity="danger" outlined :loading="disable2faForm.processing" />
                                </form>

                                <form class="space-y-3" @submit.prevent="regenerateCodes">
                                    <Password v-model="recoveryForm.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask placeholder="Contraseña para regenerar códigos" />
                                    <Button type="submit" label="Regenerar códigos" icon="pi pi-refresh" severity="secondary" outlined :loading="recoveryForm.processing" />
                                </form>
                            </div>

                            <Textarea v-if="recoveryCodes.length" :model-value="recoveryCodes.join('\n')" class="mt-5 w-full font-mono text-sm" rows="8" readonly />
                        </template>
                    </Card>

                    <Card>
                        <template #title>Sesiones activas</template>
                        <template #content>
                            <div class="mb-4 space-y-3">
                                <div v-for="session in sessions" :key="session.id" class="rounded-2xl border border-white/10 p-3">
                                    <p class="text-sm font-semibold">{{ session.ip_address || 'IP desconocida' }}</p>
                                    <p class="line-clamp-2 text-xs text-slate-400">{{ session.user_agent || 'Agente desconocido' }}</p>
                                    <Tag v-if="session.is_current_device" class="mt-2" severity="success" value="Actual" />
                                </div>
                            </div>
                            <form class="space-y-3" @submit.prevent="logoutOtherSessions">
                                <Password v-model="sessionsForm.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask placeholder="Contraseña" />
                                <FormError :message="sessionsForm.errors.password" />
                                <Button type="submit" label="Cerrar otras sesiones" icon="pi pi-sign-out" severity="secondary" outlined :loading="sessionsForm.processing" />
                            </form>
                        </template>
                    </Card>

                    <Card>
                        <template #title>Eliminar cuenta</template>
                        <template #content>
                            <form class="space-y-3" @submit.prevent="deleteAccount">
                                <p class="text-sm leading-6 text-slate-400">Esta acción elimina tu usuario y cierra la sesión actual.</p>
                                <Password v-model="deleteForm.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask placeholder="Contraseña" />
                                <FormError :message="deleteForm.errors.password" />
                                <Button type="submit" label="Eliminar cuenta" icon="pi pi-trash" severity="danger" :loading="deleteForm.processing" />
                            </form>
                        </template>
                    </Card>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
