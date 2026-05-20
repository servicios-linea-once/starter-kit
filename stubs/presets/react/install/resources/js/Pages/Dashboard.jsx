import { Head, Link, usePage } from '@inertiajs/react';
import { Shield } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import { Button } from '@/Components/ui/button';
import { useLanguage } from '@/lib/i18n';

export default function Dashboard() {
    const page = usePage();
    const { t } = useLanguage();
    const user = page.props.auth.user;

    return (
        <AppLayout>
            <Head title={t('dashboard')} />
            <section className="mx-auto max-w-6xl px-6 py-10">
                <div className="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                    <div>
                        <p className="text-sm font-semibold uppercase text-cyan-200">{t('dashboardTab')}</p>
                        <h1 className="mt-2 text-4xl font-black tracking-[0]">{t('dashboardGreeting', { name: user.name })}</h1>
                        <p className="mt-3 text-slate-400">{t('dashboardBody')}</p>
                    </div>
                    <Button asChild>
                        <Link href={route('profile.edit')}>
                            <Shield size={17} />
                            {t('manageSecurity')}
                        </Link>
                    </Button>
                </div>

                <div className="grid gap-4 md:grid-cols-4">
                    {[
                        [t('totalSales'), 'S/ 45,680.00', '+12.6%'],
                        [t('orders'), '1,250', '+8.2%'],
                        [t('customers'), '850', '+6.4%'],
                        [t('conversion'), '3.24%', '+1.2%'],
                    ].map(([label, value, delta]) => (
                        <div key={label} className="kit-stat-card p-5">
                            <p className="text-sm font-bold text-slate-400">{label}</p>
                            <h2 className="mt-3 text-2xl font-bold text-white">{value}</h2>
                            <p className="mt-2 text-xs text-emerald-300">{delta}</p>
                        </div>
                    ))}
                </div>

                <div className="mt-5 grid gap-5 lg:grid-cols-[1.4fr_0.8fr]">
                    <div className="kit-panel rounded-lg p-6">
                        <h2 className="font-bold text-white">{t('sales')}</h2>
                        <div className="kit-chart-line mt-4" />
                    </div>

                    <div className="kit-panel rounded-lg p-6">
                        <h2 className="font-bold text-white">{t('salesChannel')}</h2>
                        <div className="mt-5 flex justify-center">
                            <div className="kit-donut" />
                        </div>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
