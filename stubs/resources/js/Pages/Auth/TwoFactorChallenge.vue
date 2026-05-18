<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';

const form = useForm({
    code: '',
    recovery_code: '',
});

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
    <Head title="Two-factor challenge" />

    <AuthCard title="Verificación 2FA" subtitle="Ingresa el código de tu autenticador o usa un código de recuperación.">
        <form class="space-y-4" @submit.prevent="submitCode">
            <div>
                <label class="mb-2 block text-sm font-semibold">Código de autenticación</label>
                <InputText v-model="form.code" class="w-full tracking-[0.3em]" autofocus inputmode="numeric" />
                <FormError :message="form.errors.code" />
            </div>
            <Button type="submit" label="Verificar código" icon="pi pi-shield" class="w-full" :loading="form.processing" />
        </form>

        <div class="my-6 h-px bg-white/10" />

        <form class="space-y-4" @submit.prevent="submitRecovery">
            <div>
                <label class="mb-2 block text-sm font-semibold">Código de recuperación</label>
                <InputText v-model="form.recovery_code" class="w-full uppercase" />
                <FormError :message="form.errors.recovery_code" />
            </div>
            <Button type="submit" label="Usar recuperación" icon="pi pi-key" severity="secondary" outlined class="w-full" :loading="form.processing" />
        </form>

        <Button label="Volver al login" icon="pi pi-arrow-left" severity="secondary" text class="mt-5 w-full" @click="cancelChallenge" />
    </AuthCard>
</template>
