<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { LogIn, Send, UserPlus } from '@lucide/vue';
import { NButton, NCard, NDivider } from 'naive-ui';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useLanguage } from '@/lib/i18n';

const page = usePage();
const { t } = useLanguage();

const stack = () => [
    ['Laravel', t('stackLaravel')],
    ['Inertia', t('stackInertia')],
    ['Naive UI', t('stackUi')],
    ['Ziggy', t('stackZiggy')],
    ['Tailwind CSS 4', t('stackTailwind')],
];
</script>

<template>
    <Head :title="t('starterKit')" />

    <AppLayout>
        <section class="mx-auto grid max-w-6xl gap-8 px-6 py-12 lg:grid-cols-[1.1fr_0.9fr] lg:py-16">
            <div class="flex flex-col justify-center">
                <p class="mb-4 text-sm font-semibold uppercase tracking-[0.18em] text-cyan-200">
                    {{ t('welcomeKicker') }}
                </p>
                <h1 class="max-w-3xl text-4xl font-black leading-tight tracking-[0] text-slate-50 md:text-6xl">
                    {{ t('welcomeTitle') }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-400">
                    {{ t('welcomeBody', { ui: 'Naive UI' }) }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <NButton v-if="page.props.auth.user" tag="a" :href="route('dashboard')" type="primary">
                        <template #icon><Send :size="17" /></template>
                        {{ t('viewDashboard') }}
                    </NButton>
                    <NButton v-else tag="a" :href="route('login')" type="primary">
                        <template #icon><LogIn :size="17" /></template>
                        {{ t('login') }}
                    </NButton>
                    <NButton v-if="!page.props.auth.user" tag="a" :href="route('register')" secondary>
                        <template #icon><UserPlus :size="17" /></template>
                        {{ t('register') }}
                    </NButton>
                </div>
            </div>

            <NCard class="kit-panel">
                <template #title>
                    <span class="text-xl font-bold">{{ t('installedStack') }}</span>
                </template>

                <div class="space-y-1">
                    <div v-for="[name, description] in stack()" :key="name" class="rounded-md px-2 py-3 hover:bg-white/[0.04]">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 h-2.5 w-2.5 rounded-full bg-cyan-300" />
                            <div>
                                <h2 class="font-semibold text-slate-50">{{ name }}</h2>
                                <p class="text-sm leading-6 text-slate-400">{{ description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <NDivider />

                <div class="rounded-md border border-cyan-300/20 bg-cyan-300/10 p-4 text-sm leading-6 text-cyan-50">
                    {{ t('installedBy') }}
                </div>
            </NCard>
        </section>
    </AppLayout>
</template>
