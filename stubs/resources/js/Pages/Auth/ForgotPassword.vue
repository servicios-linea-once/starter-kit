<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import StatusMessage from '@/Components/StatusMessage.vue';

defineProps({ status: String });

const form = useForm({ email: '' });
</script>

<template>
    <Head title="Recuperar contraseña" />

    <AuthCard title="Recuperar contraseña" subtitle="Te enviaremos un enlace para crear una nueva contraseña.">
        <StatusMessage :message="status" />

        <form class="space-y-5" @submit.prevent="form.post(route('password.email'))">
            <div>
                <label class="mb-2 block text-sm font-semibold">Email</label>
                <InputText v-model="form.email" type="email" class="w-full" autofocus />
                <FormError :message="form.errors.email" />
            </div>

            <Button type="submit" label="Enviar enlace" icon="pi pi-send" class="w-full" :loading="form.processing" />
        </form>

        <Link :href="route('login')" class="mt-6 block text-center text-sm font-semibold text-cyan-200 hover:text-cyan-100">Volver al login</Link>
    </AuthCard>
</template>
