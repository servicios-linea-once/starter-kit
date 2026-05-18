<script setup>
import { Head, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import AuthCard from '@/Components/AuthCard.vue';
import StatusMessage from '@/Components/StatusMessage.vue';

defineProps({ status: String });
</script>

<template>
    <Head title="Verifica tu email" />

    <AuthCard title="Verifica tu email" subtitle="Antes de continuar, confirma tu dirección con el enlace que enviamos.">
        <StatusMessage v-if="status === 'verification-link-sent'" message="Enviamos un nuevo enlace de verificación." />

        <div class="flex flex-col gap-3">
            <Button label="Reenviar verificación" icon="pi pi-envelope" @click="router.post(route('verification.send'))" />
            <Button label="Cerrar sesión" icon="pi pi-sign-out" severity="secondary" outlined @click="router.post(route('logout'))" />
        </div>
    </AuthCard>
</template>
