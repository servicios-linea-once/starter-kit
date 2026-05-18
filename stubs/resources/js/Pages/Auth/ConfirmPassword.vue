<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Password from 'primevue/password';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';

const form = useForm({ password: '' });
</script>

<template>
    <Head title="Confirmar contraseña" />

    <AuthCard title="Confirmar contraseña" subtitle="Confirma tu contraseña para continuar con esta acción sensible.">
        <form class="space-y-5" @submit.prevent="form.post(route('password.confirm.store'))">
            <div>
                <label class="mb-2 block text-sm font-semibold">Contraseña</label>
                <Password v-model="form.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask autofocus autocomplete="current-password" />
                <FormError :message="form.errors.password" />
            </div>

            <Button type="submit" label="Confirmar" icon="pi pi-shield" class="w-full" :loading="form.processing" />
        </form>
    </AuthCard>
</template>
