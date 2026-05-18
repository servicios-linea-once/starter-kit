<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import StatusMessage from '@/Components/StatusMessage.vue';

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

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

    <AuthCard title="Entrar" subtitle="Accede con tu email y contraseña.">
        <StatusMessage :message="props.status" />

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <label class="mb-2 block text-sm font-semibold">Email</label>
                <InputText v-model="form.email" type="email" class="w-full" autocomplete="username" autofocus />
                <FormError :message="form.errors.email" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Contraseña</label>
                <Password v-model="form.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask autocomplete="current-password" />
                <FormError :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between gap-4">
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <Checkbox v-model="form.remember" binary />
                    Recordarme
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-semibold text-cyan-200 hover:text-cyan-100">
                    Olvidé mi contraseña
                </Link>
            </div>

            <Button type="submit" label="Entrar" icon="pi pi-lock-open" class="w-full" :loading="form.processing" />
        </form>

        <p v-if="canRegister" class="mt-6 text-center text-sm text-slate-400">
            ¿No tienes cuenta?
            <Link :href="route('register')" class="font-semibold text-cyan-200 hover:text-cyan-100">Crear cuenta</Link>
        </p>
    </AuthCard>
</template>
