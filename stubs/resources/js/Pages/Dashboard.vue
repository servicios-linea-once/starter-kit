<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Tag from 'primevue/tag';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <section class="mx-auto max-w-6xl px-6 py-10">
            <div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-cyan-200">Dashboard</p>
                    <h1 class="mt-2 text-4xl font-black tracking-[0]">Hola, {{ page.props.auth.user.name }}</h1>
                    <p class="mt-3 text-slate-400">Tu sesión está protegida por Laravel, Inertia y PrimeVue.</p>
                </div>
                <Button as="a" :href="route('profile.edit')" label="Gestionar seguridad" icon="pi pi-shield" />
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <Card>
                    <template #title>Email</template>
                    <template #content>
                        <p class="text-sm text-slate-400">{{ page.props.auth.user.email }}</p>
                        <Tag class="mt-4" severity="success" value="Verificado" />
                    </template>
                </Card>

                <Card>
                    <template #title>2FA</template>
                    <template #content>
                        <p class="text-sm text-slate-400">Autenticación de dos factores</p>
                        <Tag class="mt-4" :severity="page.props.auth.user.two_factor_enabled ? 'success' : 'warn'" :value="page.props.auth.user.two_factor_enabled ? 'Activo' : 'Inactivo'" />
                    </template>
                </Card>

                <Card>
                    <template #title>Acceso</template>
                    <template #content>
                        <p class="text-sm text-slate-400">Rutas protegidas por sesión y email verificado.</p>
                        <Link :href="route('profile.edit')" class="mt-4 inline-block text-sm font-bold text-cyan-200">Abrir perfil</Link>
                    </template>
                </Card>
            </div>
        </section>
    </AppLayout>
</template>
