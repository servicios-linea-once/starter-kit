<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { Shield } from '@lucide/vue';
import { NButton } from 'naive-ui';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useLanguage } from '@/lib/i18n';

const page = usePage();
const { t } = useLanguage();
</script>

<template>
    <Head :title="t('dashboard')" />

    <AppLayout>
        <section class="mx-auto max-w-6xl px-6 py-10">
            <div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-semibold uppercase text-cyan-200">{{ t('dashboardTab') }}</p>
                    <h1 class="mt-2 text-4xl font-black tracking-[0]">{{ t('dashboardGreeting', { name: page.props.auth.user.name }) }}</h1>
                    <p class="mt-3 text-slate-400">{{ t('dashboardBody', { ui: 'Naive UI' }) }}</p>
                </div>
                <NButton tag="a" :href="route('profile.edit')" type="primary">
                    <template #icon><Shield :size="17" /></template>
                    {{ t('manageSecurity') }}
                </NButton>
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <div class="kit-stat-card p-5">
                    <p class="text-sm font-bold text-slate-400">{{ t('totalSales') }}</p>
                    <h2 class="mt-3 text-2xl font-bold text-white">S/ 45,680.00</h2>
                    <p class="mt-2 text-xs text-emerald-300">+12.6%</p>
                </div>
                <div class="kit-stat-card p-5">
                    <p class="text-sm font-bold text-slate-400">{{ t('orders') }}</p>
                    <h2 class="mt-3 text-2xl font-bold text-white">1,250</h2>
                    <p class="mt-2 text-xs text-emerald-300">+8.2%</p>
                </div>
                <div class="kit-stat-card p-5">
                    <p class="text-sm font-bold text-slate-400">{{ t('customers') }}</p>
                    <h2 class="mt-3 text-2xl font-bold text-white">850</h2>
                    <p class="mt-2 text-xs text-emerald-300">+6.4%</p>
                </div>
                <div class="kit-stat-card p-5">
                    <p class="text-sm font-bold text-slate-400">{{ t('conversion') }}</p>
                    <h2 class="mt-3 text-2xl font-bold text-white">3.24%</h2>
                    <p class="mt-2 text-xs text-emerald-300">+1.2%</p>
                </div>
            </div>

            <div class="mt-5 grid gap-5 lg:grid-cols-[1.4fr_0.8fr]">
                <div class="kit-panel rounded-lg p-6">
                    <h2 class="font-bold text-white">{{ t('sales') }}</h2>
                    <div class="kit-chart-line mt-4" />
                </div>

                <div class="kit-panel rounded-lg p-6">
                    <h2 class="font-bold text-white">{{ t('salesChannel') }}</h2>
                    <div class="mt-5 flex justify-center">
                        <div class="kit-donut" />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
