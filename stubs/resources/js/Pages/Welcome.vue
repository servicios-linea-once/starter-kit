<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Divider from 'primevue/divider';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();

const stack = [
    ['Laravel', 'Rutas, controladores y servicios en PHP 8.4.'],
    ['Inertia', 'Páginas Vue sin crear una API artificial.'],
    ['PrimeVue', 'Componentes listos para formularios y paneles.'],
    ['Ziggy', 'Rutas Laravel disponibles en JavaScript.'],
    ['Tailwind CSS 4', 'Tokens rápidos para layout y acabado visual.'],
];
</script>

<template>
    <Head title="Starter Kit" />

    <AppLayout>
        <section class="mx-auto grid max-w-6xl gap-8 px-6 py-12 lg:grid-cols-[1.1fr_0.9fr] lg:py-16">
            <div class="flex flex-col justify-center">
                <p class="mb-4 text-sm font-semibold uppercase tracking-[0.18em] text-emerald-700">
                    KitLauncher starter kit
                </p>
                <h1 class="max-w-3xl text-4xl font-black leading-tight tracking-[0] text-gray-950 md:text-6xl">
                    Laravel 13 con una base Vue seria desde el primer commit.
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-gray-600">
                    Este proyecto ya está conectado con Inertia, PrimeVue, Ziggy y Tailwind CSS 4 desde un paquete local instalable.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <Button v-if="page.props.auth.user" as="a" :href="route('dashboard')" label="Ver dashboard" icon="pi pi-send" />
                    <Button v-else as="a" :href="route('login')" label="Entrar" icon="pi pi-sign-in" />
                    <Button v-if="!page.props.auth.user" as="a" :href="route('register')" label="Crear cuenta" icon="pi pi-user-plus" outlined severity="secondary" />
                </div>
            </div>

            <Card class="border border-gray-200 shadow-sm">
                <template #title>
                    <span class="text-xl font-bold">Stack instalado</span>
                </template>

                <template #content>
                    <div class="space-y-1">
                        <div v-for="[name, description] in stack" :key="name" class="rounded-md px-2 py-3 hover:bg-gray-50">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500" />
                                <div>
                                    <h2 class="font-semibold text-gray-950">{{ name }}</h2>
                                    <p class="text-sm leading-6 text-gray-600">{{ description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Divider />

                    <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-sm leading-6 text-emerald-950">
                        El comando <code class="font-mono">kitlauncher:install</code> dejó esta pantalla como prueba de integración.
                    </div>
                </template>
            </Card>
        </section>
    </AppLayout>
</template>
