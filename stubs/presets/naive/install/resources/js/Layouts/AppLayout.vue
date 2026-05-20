<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { LogIn, LogOut } from '@lucide/vue';
import { NButton, NTag } from 'naive-ui';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { useLanguage } from '@/lib/i18n';

const page = usePage();
const { t } = useLanguage();

const logout = () => router.post(route('logout'));
</script>

<template>
    <div class="kit-shell text-slate-100 app-dark">
        <header class="relative z-10 border-b border-white/10 bg-slate-950/80 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <Link :href="route('home')" class="flex items-center gap-3">
                    <span class="kit-brand-mark h-9 w-9 rounded-lg">
                        <span class="kit-brand-letter text-lg">S</span>
                    </span>
                    <span class="font-semibold tracking-[0]">Servicio Linea Once</span>
                </Link>

                <nav class="hidden items-center gap-2 md:flex">
                    <Link v-if="page.props.auth.user" :href="route('dashboard')" class="text-sm text-slate-300 hover:text-white">{{ t('dashboard') }}</Link>
                    <Link v-if="page.props.auth.user" :href="route('profile.edit')" class="text-sm text-slate-300 hover:text-white">{{ t('profile') }}</Link>
                    <NTag type="info" size="small" :bordered="false">Laravel 13</NTag>
                    <NTag type="success" size="small" :bordered="false">2FA</NTag>
                </nav>

                <div class="flex items-center gap-2">
                    <LanguageSwitcher />
                    <NButton v-if="page.props.auth.user" secondary size="small" @click="logout">
                        <template #icon><LogOut :size="16" /></template>
                        {{ t('logout') }}
                    </NButton>
                    <NButton v-else tag="a" :href="route('login')" type="primary" size="small">
                        <template #icon><LogIn :size="16" /></template>
                        {{ t('login') }}
                    </NButton>
                </div>
            </div>
        </header>

        <main class="kit-content">
            <slot />
        </main>
    </div>
</template>
