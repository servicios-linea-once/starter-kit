<script setup>
import Button from 'primevue/button';
import { useLanguage } from '@/lib/i18n';

const { locale, setLocale, t } = useLanguage();
</script>

<template>
    <div class="flex items-center gap-1" :aria-label="t('language')">
        <Button label="ES" size="small" :severity="locale === 'es' ? 'primary' : 'secondary'" :outlined="locale !== 'es'" @click="setLocale('es')" />
        <Button label="EN" size="small" :severity="locale === 'en' ? 'primary' : 'secondary'" :outlined="locale !== 'en'" @click="setLocale('en')" />
    </div>
</template>
