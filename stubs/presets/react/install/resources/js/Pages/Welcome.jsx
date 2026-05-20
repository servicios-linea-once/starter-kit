import { Head, Link, usePage } from '@inertiajs/react';
import { LogIn, Send, UserPlus } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle } from '@/Components/ui/card';
import { useLanguage } from '@/lib/i18n';

export default function Welcome() {
    const page = usePage();
    const { t } = useLanguage();
    const stack = [
        ['Laravel', t('stackLaravel')],
        ['Inertia', t('stackInertia')],
        ['React', t('stackUi')],
        ['Ziggy', t('stackZiggy')],
        ['Tailwind CSS 4', t('stackTailwind')],
    ];

    return (
        <AppLayout>
            <Head title={t('starterKit')} />
            <section className="mx-auto grid max-w-6xl gap-8 px-6 py-12 lg:grid-cols-[1.1fr_0.9fr] lg:py-16">
                <div className="flex flex-col justify-center">
                    <p className="mb-4 text-sm font-semibold uppercase tracking-[0.18em] text-cyan-200">
                        {t('welcomeKicker')}
                    </p>
                    <h1 className="max-w-3xl text-4xl font-black leading-tight tracking-[0] text-slate-50 md:text-6xl">
                        {t('welcomeTitle')}
                    </h1>
                    <p className="mt-6 max-w-2xl text-lg leading-8 text-slate-400">{t('welcomeBody')}</p>

                    <div className="mt-8 flex flex-wrap gap-3">
                        {page.props.auth.user ? (
                            <Button asChild>
                                <Link href={route('dashboard')}>
                                    <Send size={17} />
                                    {t('viewDashboard')}
                                </Link>
                            </Button>
                        ) : (
                            <Button asChild>
                                <Link href={route('login')}>
                                    <LogIn size={17} />
                                    {t('login')}
                                </Link>
                            </Button>
                        )}
                        {! page.props.auth.user ? (
                            <Button asChild variant="secondary">
                                <Link href={route('register')}>
                                    <UserPlus size={17} />
                                    {t('register')}
                                </Link>
                            </Button>
                        ) : null}
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>{t('installedStack')}</CardTitle>
                    </CardHeader>
                    <div className="space-y-1">
                        {stack.map(([name, description]) => (
                            <div key={name} className="rounded-md px-2 py-3 hover:bg-white/[0.04]">
                                <div className="flex items-start gap-3">
                                    <span className="mt-1 h-2.5 w-2.5 rounded-full bg-cyan-300" />
                                    <div>
                                        <h2 className="font-semibold text-slate-50">{name}</h2>
                                        <p className="text-sm leading-6 text-slate-400">{description}</p>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                    <div className="my-5 h-px bg-white/10" />
                    <div className="rounded-md border border-cyan-300/20 bg-cyan-300/10 p-4 text-sm leading-6 text-cyan-50">
                        {t('installedBy')}
                    </div>
                </Card>
            </section>
        </AppLayout>
    );
}
