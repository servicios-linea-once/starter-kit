import { Head, useForm } from '@inertiajs/react';
import { Shield } from 'lucide-react';
import FormError from '@/Components/FormError';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AuthLayout from '@/Layouts/AuthLayout';
import { useLanguage } from '@/lib/i18n';

export default function ConfirmPassword() {
    const { t } = useLanguage();
    const form = useForm({ password: '' });

    const submit = (event) => {
        event.preventDefault();
        form.post(route('password.confirm.store'));
    };

    return (
        <AuthLayout title={t('confirmPasswordTitle')} subtitle={t('confirmPasswordSubtitle')} tab={t('confirmPasswordTab')}>
            <Head title={t('confirmPasswordTitle')} />
            <form className="space-y-5" onSubmit={submit}>
                <div>
                    <Label htmlFor="password">{t('password')}</Label>
                    <Input id="password" type="password" value={form.data.password} onChange={(e) => form.setData('password', e.target.value)} autoFocus autoComplete="current-password" />
                    <FormError message={form.errors.password} />
                </div>
                <Button type="submit" block disabled={form.processing}>
                    <Shield size={17} />
                    {t('confirm')}
                </Button>
            </form>
        </AuthLayout>
    );
}
