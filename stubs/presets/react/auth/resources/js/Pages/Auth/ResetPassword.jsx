import { Head, useForm } from '@inertiajs/react';
import { Check } from 'lucide-react';
import FormError from '@/Components/FormError';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AuthLayout from '@/Layouts/AuthLayout';
import { useLanguage } from '@/lib/i18n';

export default function ResetPassword({ email, token }) {
    const { t } = useLanguage();
    const form = useForm({ token, email, password: '', password_confirmation: '' });

    const submit = (event) => {
        event.preventDefault();
        form.post(route('password.store'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout title={t('resetTitle')} subtitle={t('resetSubtitle')} tab={t('resetPasswordTab')}>
            <Head title={t('resetTitle')} />
            <form className="space-y-5" onSubmit={submit}>
                <div>
                    <Label htmlFor="email">{t('email')}</Label>
                    <Input id="email" value={form.data.email || ''} onChange={(e) => form.setData('email', e.target.value)} autoComplete="username" />
                    <FormError message={form.errors.email} />
                </div>
                <div>
                    <Label htmlFor="password">{t('password')}</Label>
                    <Input id="password" type="password" value={form.data.password} onChange={(e) => form.setData('password', e.target.value)} autoComplete="new-password" />
                    <FormError message={form.errors.password} />
                </div>
                <div>
                    <Label htmlFor="password_confirmation">{t('confirmPassword')}</Label>
                    <Input id="password_confirmation" type="password" value={form.data.password_confirmation} onChange={(e) => form.setData('password_confirmation', e.target.value)} autoComplete="new-password" />
                    <FormError message={form.errors.password_confirmation} />
                </div>
                <Button type="submit" block disabled={form.processing}>
                    <Check size={17} />
                    {t('savePassword')}
                </Button>
            </form>
        </AuthLayout>
    );
}
