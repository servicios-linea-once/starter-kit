import { Head, router } from '@inertiajs/react';
import { LogOut, Mail } from 'lucide-react';
import StatusMessage from '@/Components/StatusMessage';
import { Button } from '@/Components/ui/button';
import AuthLayout from '@/Layouts/AuthLayout';
import { useLanguage } from '@/lib/i18n';

export default function VerifyEmail({ status }) {
    const { t } = useLanguage();

    return (
        <AuthLayout title={t('verifyEmailTitle')} subtitle={t('verifyEmailSubtitle')} tab={t('verifyEmailTab')}>
            <Head title={t('verifyEmailTitle')} />
            <StatusMessage message={status === 'verification-link-sent' ? t('verificationSent') : ''} />
            <div className="flex flex-col gap-3">
                <Button type="button" onClick={() => router.post(route('verification.send'))}>
                    <Mail size={17} />
                    {t('resendVerification')}
                </Button>
                <Button type="button" variant="secondary" onClick={() => router.post(route('logout'))}>
                    <LogOut size={17} />
                    {t('logout')}
                </Button>
            </div>
        </AuthLayout>
    );
}
