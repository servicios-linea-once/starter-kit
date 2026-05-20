import { Head, Link, useForm } from '@inertiajs/react';
import { UserPlus } from 'lucide-react';
import FormError from '@/Components/FormError';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AuthLayout from '@/Layouts/AuthLayout';
import { useLanguage } from '@/lib/i18n';

export default function Register() {
    const { t } = useLanguage();
    const form = useForm({ name: '', email: '', password: '', password_confirmation: '' });

    const submit = (event) => {
        event.preventDefault();
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout title={t('register')} subtitle={t('registerSubtitle')} tab={t('registerTab')}>
            <Head title={t('register')} />
            <form className="space-y-5" onSubmit={submit}>
                <div>
                    <Label htmlFor="name">{t('name')}</Label>
                    <Input id="name" value={form.data.name} onChange={(e) => form.setData('name', e.target.value)} autoComplete="name" autoFocus />
                    <FormError message={form.errors.name} />
                </div>
                <div>
                    <Label htmlFor="email">{t('email')}</Label>
                    <Input id="email" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} autoComplete="username" />
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
                    <UserPlus size={17} />
                    {t('register')}
                </Button>
            </form>
            <p className="mt-6 text-center text-sm text-slate-400">
                {t('alreadyAccount')} <Link href={route('login')} className="kit-link">{t('login')}</Link>
            </p>
        </AuthLayout>
    );
}
