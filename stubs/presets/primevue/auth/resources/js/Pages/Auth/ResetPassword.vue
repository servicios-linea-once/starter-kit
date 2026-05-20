<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import AuthCard from '@/Components/AuthCard.vue';
import FormError from '@/Components/FormError.vue';
import { useLanguage } from '@/lib/i18n';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const { t } = useLanguage();

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="t('resetTitle')" />

    <AuthCard :title="t('resetTitle')" :subtitle="t('resetSubtitle')" :tab="t('resetPasswordTab')">
        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('email') }}</label>
                <InputText v-model="form.email" type="email" class="w-full" autocomplete="username" />
                <FormError :message="form.errors.email" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('password') }}</label>
                <Password v-model="form.password" class="w-full" input-class="w-full" toggle-mask autocomplete="new-password" />
                <FormError :message="form.errors.password" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">{{ t('confirmPassword') }}</label>
                <Password v-model="form.password_confirmation" class="w-full" input-class="w-full" :feedback="false" toggle-mask autocomplete="new-password" />
                <FormError :message="form.errors.password_confirmation" />
            </div>

            <Button type="submit" :label="t('savePassword')" icon="pi pi-check" class="w-full" :loading="form.processing" />
        </form>
    </AuthCard>
</template>
