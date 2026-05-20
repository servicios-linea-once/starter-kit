<script setup>
import { Languages } from '@lucide/vue';
import { NButton } from 'naive-ui';
import { useLanguage } from '@/lib/i18n';

const { locale, setLocale, t } = useLanguage();
</script>

<template>
    <div class="flex items-center gap-1" :aria-label="t('language')">
        <Languages :size="16" class="text-slate-400" />
        <NButton size="small" :type="locale === 'es' ? 'primary' : 'default'" :quaternary="locale !== 'es'" @click="setLocale('es')">ES</NButton>
        <NButton size="small" :type="locale === 'en' ? 'primary' : 'default'" :quaternary="locale !== 'en'" @click="setLocale('en')">EN</NButton>
    </div>
</template>
