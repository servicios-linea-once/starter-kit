<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import StatusMessage from '@/Components/StatusMessage.vue';
import { useLanguage } from '@/lib/i18n';

const props = defineProps({
    canResetPassword: Boolean,
    canRegister: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const { t } = useLanguage();

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head :title="t('login')" />

    <AuthCard :title="t('login')" :subtitle="t('loginSubtitle')" :tab="t('loginTab')">
        <StatusMessage :message="props.status ? t(props.status) : ''" />

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('email') }}</label>
                <InputText v-model="form.email" type="email" class="w-full" autocomplete="username" autofocus />
                <FormError :message="form.errors.email" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('password') }}</label>
                <Password v-model="form.password" class="w-full" input-class="w-full" :feedback="false" toggle-mask autocomplete="current-password" />
                <FormError :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between gap-4">
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <Checkbox v-model="form.remember" binary />
                    {{ t('rememberMe') }}
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-semibold text-cyan-200 hover:text-cyan-100">
                    {{ t('forgotPassword') }}
                </Link>
            </div>

            <Button type="submit" :label="t('login')" icon="pi pi-lock-open" class="w-full" :loading="form.processing" />
        </form>

        <p v-if="canRegister" class="mt-6 text-center text-sm text-slate-400">
            {{ t('noAccount') }}
            <Link :href="route('register')" class="font-semibold text-cyan-200 hover:text-cyan-100">{{ t('register') }}</Link>
        </p>
    </AuthCard>
</template>
