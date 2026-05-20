import { Head, Link, useForm } from '@inertiajs/react';
import { LockOpen } from 'lucide-react';
import FormError from '@/Components/FormError';
import StatusMessage from '@/Components/StatusMessage';
import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AuthLayout from '@/Layouts/AuthLayout';
import { useLanguage } from '@/lib/i18n';

export default function Login({ canResetPassword, canRegister, status }) {
    const { t } = useLanguage();
    const form = useForm({ email: '', password: '', remember: false });

    const submit = (event) => {
        event.preventDefault();
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };

    return (
        <AuthLayout title={t('login')} subtitle={t('loginSubtitle')} tab={t('loginTab')}>
            <Head title={t('login')} />
            <StatusMessage message={status} />
            <form className="space-y-5" onSubmit={submit}>
                <div>
                    <Label htmlFor="email">{t('email')}</Label>
                    <Input id="email" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} autoComplete="username" autoFocus />
                    <FormError message={form.errors.email} />
                </div>

                <div>
                    <Label htmlFor="password">{t('password')}</Label>
                    <Input id="password" type="password" value={form.data.password} onChange={(e) => form.setData('password', e.target.value)} autoComplete="current-password" />
                    <FormError message={form.errors.password} />
                </div>

                <div className="flex items-center justify-between gap-4">
                    <label className="flex items-center gap-2 text-sm text-slate-300">
                        <Checkbox checked={form.data.remember} onCheckedChange={(value) => form.setData('remember', value)} />
                        {t('rememberMe')}
                    </label>
                    {canResetPassword ? <Link href={route('password.request')} className="kit-link text-sm">{t('forgotPassword')}</Link> : null}
                </div>

                <Button type="submit" block disabled={form.processing}>
                    <LockOpen size={17} />
                    {t('login')}
                </Button>
            </form>

            {canRegister ? (
                <p className="mt-6 text-center text-sm text-slate-400">
                    {t('noAccount')} <Link href={route('register')} className="kit-link">{t('register')}</Link>
                </p>
            ) : null}
        </AuthLayout>
    );
}
