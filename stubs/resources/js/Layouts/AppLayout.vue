<script setup>
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import { Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();

const logout = () => router.post(route('logout'));
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100 app-dark">
        <header class="border-b border-white/10 bg-slate-950/80 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <Link :href="route('home')" class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-cyan-300 text-sm font-black text-slate-950">
                        S
                    </span>
                    <span class="font-semibold tracking-[0]">{{ page.props.app.name }}</span>
                </Link>

                <nav class="hidden items-center gap-2 md:flex">
                    <Link v-if="page.props.auth.user" :href="route('dashboard')" class="text-sm text-slate-300 hover:text-white">Dashboard</Link>
                    <Link v-if="page.props.auth.user" :href="route('profile.edit')" class="text-sm text-slate-300 hover:text-white">Perfil</Link>
                    <Tag value="Laravel 13" severity="info" />
                    <Tag value="2FA" severity="success" />
                </nav>

                <div class="flex items-center gap-2">
                    <Button v-if="page.props.auth.user" label="Salir" icon="pi pi-sign-out" size="small" severity="secondary" outlined @click="logout" />
                    <Button v-else as="a" :href="route('login')" label="Entrar" icon="pi pi-sign-in" size="small" />
                </div>
            </div>
        </header>

        <main>
            <slot />
        </main>
    </div>
</template>
