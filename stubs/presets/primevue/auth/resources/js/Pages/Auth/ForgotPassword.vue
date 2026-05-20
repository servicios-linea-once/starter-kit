<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import StatusMessage from '@/Components/StatusMessage.vue';
import { useLanguage } from '@/lib/i18n';

defineProps({ status: String });

const form = useForm({ email: '' });
const { t } = useLanguage();
</script>

<template>
    <Head :title="t('forgotTitle')" />

    <AuthCard :title="t('forgotTitle')" :subtitle="t('forgotSubtitle')" :tab="t('forgotPasswordTab')">
        <StatusMessage :message="status ? t(status) : ''" />

        <form class="space-y-5" @submit.prevent="form.post(route('password.email'))">
            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('email') }}</label>
                <InputText v-model="form.email" type="email" class="w-full" autofocus />
                <FormError :message="form.errors.email" />
            </div>

            <Button type="submit" :label="t('sendLink')" icon="pi pi-send" class="w-full" :loading="form.processing" />
        </form>

        <Link :href="route('login')" class="mt-6 block text-center text-sm font-semibold text-cyan-200 hover:text-cyan-100">{{ t('backToLogin') }}</Link>
    </AuthCard>
</template>
