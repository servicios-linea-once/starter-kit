<script setup>
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { useLanguage } from '@/lib/i18n';

defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    tab: { type: String, default: '' },
});

const { t } = useLanguage();
</script>

<template>
    <div class="kit-auth-shell text-slate-100 app-dark">
        <div class="kit-content mx-auto flex min-h-screen max-w-7xl flex-col px-5 py-7">
            <header class="kit-showcase-header">
                <div class="flex w-full justify-end">
                    <LanguageSwitcher />
                </div>
                <div class="kit-brand-lockup">
                    <span class="kit-brand-mark h-14 w-14 rounded-lg">
                        <span class="kit-brand-letter text-3xl">S</span>
                    </span>
                    <h1 class="kit-brand-title">{{ t('brandFull') }}</h1>
                </div>
                <p class="kit-brand-subtitle">{{ t('experienceTagline') }}</p>
                <span class="kit-brand-underline" />
            </header>

            <main class="grid flex-1 items-center gap-5 py-8 lg:grid-cols-[0.95fr_1.05fr]">
                <section class="kit-panel relative rounded-lg p-7 pt-12 backdrop-blur md:p-9 md:pt-12">
                    <span class="kit-panel-tab">{{ tab || title }}</span>
                    <div class="mb-7 flex items-center gap-3">
                        <span class="kit-brand-mark h-10 w-10 rounded-lg">
                            <span class="kit-brand-letter text-xl">S</span>
                        </span>
                        <div>
                            <h2 class="text-2xl font-black tracking-[0]">{{ title }}</h2>
                            <p v-if="subtitle" class="mt-1 text-sm leading-6 text-slate-400">{{ subtitle }}</p>
                        </div>
                    </div>

                    <slot />
                </section>

                <aside class="kit-visual-card hidden lg:block">
                    <div class="kit-portal" />
                </aside>
            </main>
        </div>
    </div>
</template>
