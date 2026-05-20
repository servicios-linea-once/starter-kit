import { Head, router, useForm, usePage } from '@inertiajs/react';
import { Check, KeyRound, LogOut, QrCode, RefreshCw, Save, ShieldOff, Trash2 } from 'lucide-react';
import FormError from '@/Components/FormError';
import StatusMessage from '@/Components/StatusMessage';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import AppLayout from '@/Layouts/AppLayout';
import { useLanguage } from '@/lib/i18n';

export default function Profile({ sessions = [], status }) {
    const page = usePage();
    const { t } = useLanguage();
    const user = page.props.auth.user;
    const twoFactorSetup = page.props.flash.two_factor_setup;
    const recoveryCodes = page.props.flash.recovery_codes || [];

    const profileForm = useForm({ name: user.name, email: user.email });
    const passwordForm = useForm({ current_password: '', password: '', password_confirmation: '' });
    const confirm2faForm = useForm({ code: '' });
    const disable2faForm = useForm({ password: '' });
    const sessionsForm = useForm({ password: '' });
    const deleteForm = useForm({ password: '' });
    const recoveryForm = useForm({ password: '' });

    const updateProfile = (event) => {
        event.preventDefault();
        profileForm.patch(route('profile.update'));
    };

    const updatePassword = (event) => {
        event.preventDefault();
        passwordForm.put(route('password.update'), {
            onSuccess: () => passwordForm.reset(),
        });
    };

    const confirmTwoFactor = (event) => {
        event.preventDefault();
        confirm2faForm.post(route('two-factor.confirm'), {
            onSuccess: () => confirm2faForm.reset(),
        });
    };

    const disableTwoFactor = (event) => {
        event.preventDefault();
        disable2faForm.delete(route('two-factor.disable'), {
            onSuccess: () => disable2faForm.reset(),
        });
    };

    const regenerateCodes = (event) => {
        event.preventDefault();
        recoveryForm.post(route('two-factor.recovery-codes'), {
            onSuccess: () => recoveryForm.reset(),
        });
    };

    const logoutOtherSessions = (event) => {
        event.preventDefault();
        sessionsForm.delete(route('sessions.destroy'), {
            onSuccess: () => sessionsForm.reset(),
        });
    };

    const deleteAccount = (event) => {
        event.preventDefault();
        deleteForm.delete(route('profile.destroy'));
    };

    return (
        <AppLayout>
            <Head title={t('profile')} />
            <section className="mx-auto max-w-6xl px-6 py-10">
                <div className="mb-8">
                    <p className="text-sm font-semibold uppercase tracking-[0.18em] text-cyan-200">{t('settings')}</p>
                    <h1 className="mt-2 text-4xl font-black tracking-[0]">{t('profileSecurity')}</h1>
                </div>

                <StatusMessage message={status ? t(status) : ''} />

                <div className="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                    <div className="space-y-6">
                        <Card>
                            <CardHeader>
                                <CardTitle>{t('profileData')}</CardTitle>
                            </CardHeader>
                            <form className="space-y-5" onSubmit={updateProfile}>
                                <div>
                                    <Label htmlFor="name">{t('name')}</Label>
                                    <Input id="name" value={profileForm.data.name} onChange={(e) => profileForm.setData('name', e.target.value)} />
                                    <FormError message={profileForm.errors.name} />
                                </div>
                                <div>
                                    <Label htmlFor="email">{t('email')}</Label>
                                    <Input id="email" value={profileForm.data.email} onChange={(e) => profileForm.setData('email', e.target.value)} />
                                    <FormError message={profileForm.errors.email} />
                                </div>
                                <Button type="submit" disabled={profileForm.processing}>
                                    <Save size={17} />
                                    {t('saveProfile')}
                                </Button>
                            </form>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>{t('changePassword')}</CardTitle>
                            </CardHeader>
                            <form className="space-y-5" onSubmit={updatePassword}>
                                <div>
                                    <Label htmlFor="current_password">{t('currentPassword')}</Label>
                                    <Input id="current_password" type="password" value={passwordForm.data.current_password} onChange={(e) => passwordForm.setData('current_password', e.target.value)} />
                                    <FormError message={passwordForm.errors.current_password} />
                                </div>
                                <div>
                                    <Label htmlFor="new_password">{t('newPassword')}</Label>
                                    <Input id="new_password" type="password" value={passwordForm.data.password} onChange={(e) => passwordForm.setData('password', e.target.value)} />
                                    <FormError message={passwordForm.errors.password} />
                                </div>
                                <div>
                                    <Label htmlFor="password_confirmation">{t('confirmPassword')}</Label>
                                    <Input id="password_confirmation" type="password" value={passwordForm.data.password_confirmation} onChange={(e) => passwordForm.setData('password_confirmation', e.target.value)} />
                                </div>
                                <Button type="submit" disabled={passwordForm.processing}>
                                    <KeyRound size={17} />
                                    {t('updatePassword')}
                                </Button>
                            </form>
                        </Card>
                    </div>

                    <div className="space-y-6">
                        <Card>
                            <CardHeader className="flex flex-row items-center justify-between gap-3">
                                <CardTitle>{t('twoFactor')}</CardTitle>
                                <Badge variant={user.two_factor_enabled ? 'success' : 'warning'}>
                                    {user.two_factor_enabled ? t('enabled') : t('disabled')}
                                </Badge>
                            </CardHeader>

                            {! user.two_factor_enabled ? (
                                <div className="space-y-4">
                                    <p className="text-sm leading-6 text-slate-400">{t('twoFactorBody')}</p>
                                    {! twoFactorSetup ? (
                                        <Button type="button" onClick={() => router.post(route('two-factor.enable'))}>
                                            <QrCode size={17} />
                                            {t('generateQr')}
                                        </Button>
                                    ) : null}

                                    {twoFactorSetup ? (
                                        <div className="space-y-4">
                                            <div className="inline-block rounded-lg bg-white p-4" dangerouslySetInnerHTML={{ __html: twoFactorSetup.qr_code_svg }} />
                                            <p className="break-all rounded-lg bg-slate-900 p-3 font-mono text-xs text-cyan-100">{twoFactorSetup.secret}</p>
                                            <form className="space-y-3" onSubmit={confirmTwoFactor}>
                                                <Input value={confirm2faForm.data.code} onChange={(e) => confirm2faForm.setData('code', e.target.value)} className="tracking-[0.3em]" placeholder={t('sixDigitCode')} />
                                                <FormError message={confirm2faForm.errors.code} />
                                                <Button type="submit" disabled={confirm2faForm.processing}>
                                                    <Check size={17} />
                                                    {t('confirm2fa')}
                                                </Button>
                                            </form>
                                        </div>
                                    ) : null}
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    <form className="space-y-3" onSubmit={disableTwoFactor}>
                                        <Input type="password" value={disable2faForm.data.password} onChange={(e) => disable2faForm.setData('password', e.target.value)} placeholder={t('disable2faPlaceholder')} />
                                        <FormError message={disable2faForm.errors.password} />
                                        <Button type="submit" variant="dangerSecondary" disabled={disable2faForm.processing}>
                                            <ShieldOff size={17} />
                                            {t('disable2fa')}
                                        </Button>
                                    </form>

                                    <form className="space-y-3" onSubmit={regenerateCodes}>
                                        <Input type="password" value={recoveryForm.data.password} onChange={(e) => recoveryForm.setData('password', e.target.value)} placeholder={t('regenerateCodesPlaceholder')} />
                                        <Button type="submit" variant="secondary" disabled={recoveryForm.processing}>
                                            <RefreshCw size={17} />
                                            {t('regenerateCodes')}
                                        </Button>
                                    </form>
                                </div>
                            )}

                            {recoveryCodes.length ? (
                                <Textarea className="mt-5 font-mono text-sm" rows={8} value={recoveryCodes.join('\n')} readOnly />
                            ) : null}
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>{t('activeSessions')}</CardTitle>
                            </CardHeader>
                            <div className="mb-4 space-y-3">
                                {sessions.map((session) => (
                                    <div key={session.id} className="rounded-lg border border-white/10 p-3">
                                        <p className="text-sm font-semibold">{session.ip_address || t('unknownIp')}</p>
                                        <p className="line-clamp-2 text-xs text-slate-400">{session.user_agent || t('unknownAgent')}</p>
                                        {session.is_current_device ? <Badge className="mt-2" variant="success">{t('current')}</Badge> : null}
                                    </div>
                                ))}
                            </div>
                            <form className="space-y-3" onSubmit={logoutOtherSessions}>
                                <Input type="password" value={sessionsForm.data.password} onChange={(e) => sessionsForm.setData('password', e.target.value)} placeholder={t('password')} />
                                <FormError message={sessionsForm.errors.password} />
                                <Button type="submit" variant="secondary" disabled={sessionsForm.processing}>
                                    <LogOut size={17} />
                                    {t('closeOtherSessions')}
                                </Button>
                            </form>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>{t('deleteAccount')}</CardTitle>
                            </CardHeader>
                            <form className="space-y-3" onSubmit={deleteAccount}>
                                <p className="text-sm leading-6 text-slate-400">{t('deleteAccountBody')}</p>
                                <Input type="password" value={deleteForm.data.password} onChange={(e) => deleteForm.setData('password', e.target.value)} placeholder={t('password')} />
                                <FormError message={deleteForm.errors.password} />
                                <Button type="submit" variant="danger" disabled={deleteForm.processing}>
                                    <Trash2 size={17} />
                                    {t('deleteAccount')}
                                </Button>
                            </form>
                        </Card>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
