import { Head, Link, useForm } from '@inertiajs/react';
import { Send } from 'lucide-react';
import FormError from '@/Components/FormError';
import StatusMessage from '@/Components/StatusMessage';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AuthLayout from '@/Layouts/AuthLayout';
import { useLanguage } from '@/lib/i18n';

export default function ForgotPassword({ status }) {
    const { t } = useLanguage();
    const form = useForm({ email: '' });

    const submit = (event) => {
        event.preventDefault();
        form.post(route('password.email'));
    };

    return (
        <AuthLayout title={t('forgotTitle')} subtitle={t('forgotSubtitle')} tab={t('forgotPasswordTab')}>
            <Head title={t('forgotTitle')} />
            <StatusMessage message={status} />
            <form className="space-y-5" onSubmit={submit}>
                <div>
                    <Label htmlFor="email">{t('email')}</Label>
                    <Input id="email" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} autoFocus />
                    <FormError message={form.errors.email} />
                </div>
                <Button type="submit" block disabled={form.processing}>
                    <Send size={17} />
                    {t('sendLink')}
                </Button>
            </form>
            <Link href={route('login')} className="kit-link mt-6 block text-center text-sm">{t('backToLogin')}</Link>
        </AuthLayout>
    );
}
