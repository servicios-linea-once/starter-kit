import { Head, router, useForm } from '@inertiajs/react';
import { ArrowLeft, KeyRound, Shield } from 'lucide-react';
import FormError from '@/Components/FormError';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AuthLayout from '@/Layouts/AuthLayout';
import { useLanguage } from '@/lib/i18n';

export default function TwoFactorChallenge() {
    const { t } = useLanguage();
    const codeForm = useForm({ code: '' });
    const recoveryForm = useForm({ recovery_code: '' });

    const submitCode = (event) => {
        event.preventDefault();
        codeForm.post(route('two-factor.verify'));
    };

    const submitRecovery = (event) => {
        event.preventDefault();
        recoveryForm.post(route('two-factor.verify'));
    };

    return (
        <AuthLayout title={t('twoFactorTitle')} subtitle={t('twoFactorSubtitle')} tab={t('twoFactorTab')}>
            <Head title={t('twoFactorTitle')} />
            <form className="space-y-4" onSubmit={submitCode}>
                <div>
                    <Label htmlFor="code">{t('authCode')}</Label>
                    <Input id="code" value={codeForm.data.code} onChange={(e) => codeForm.setData('code', e.target.value)} className="tracking-[0.3em]" autoFocus inputMode="numeric" />
                    <FormError message={codeForm.errors.code} />
                </div>
                <Button type="submit" block disabled={codeForm.processing}>
                    <Shield size={17} />
                    {t('verifyCode')}
                </Button>
            </form>

            <div className="my-6 h-px bg-white/10" />

            <form className="space-y-4" onSubmit={submitRecovery}>
                <div>
                    <Label htmlFor="recovery_code">{t('recoveryCode')}</Label>
                    <Input id="recovery_code" value={recoveryForm.data.recovery_code} onChange={(e) => recoveryForm.setData('recovery_code', e.target.value)} className="uppercase" />
                    <FormError message={recoveryForm.errors.recovery_code || recoveryForm.errors.code} />
                </div>
                <Button type="submit" block variant="secondary" disabled={recoveryForm.processing}>
                    <KeyRound size={17} />
                    {t('useRecovery')}
                </Button>
            </form>

            <Button type="button" block variant="ghost" className="mt-5" onClick={() => router.delete(route('two-factor.cancel'))}>
                <ArrowLeft size={17} />
                {t('backToLogin')}
            </Button>
        </AuthLayout>
    );
}
