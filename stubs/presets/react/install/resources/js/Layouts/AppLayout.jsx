import { Link, router, usePage } from '@inertiajs/react';
import { LogIn, LogOut } from 'lucide-react';
import LanguageSwitcher from '@/Components/LanguageSwitcher';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { useLanguage } from '@/lib/i18n';

export default function AppLayout({ children }) {
    const page = usePage();
    const { t } = useLanguage();
    const user = page.props.auth.user;

    return (
        <div className="kit-shell text-slate-100 app-dark">
            <header className="relative z-10 border-b border-white/10 bg-slate-950/80 backdrop-blur">
                <div className="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-4">
                    <Link href={route('home')} className="flex items-center gap-3">
                        <span className="kit-brand-mark h-9 w-9 rounded-lg">
                            <span className="kit-brand-letter text-lg">S</span>
                        </span>
                        <span className="font-semibold tracking-[0]">Servicio Linea Once</span>
                    </Link>

                    <nav className="hidden items-center gap-2 md:flex">
                        {user ? <Link href={route('dashboard')} className="text-sm text-slate-300 hover:text-white">{t('dashboard')}</Link> : null}
                        {user ? <Link href={route('profile.edit')} className="text-sm text-slate-300 hover:text-white">{t('profile')}</Link> : null}
                        <Badge variant="info">Laravel 13</Badge>
                        <Badge variant="success">2FA</Badge>
                    </nav>

                    <div className="flex items-center gap-2">
                        <LanguageSwitcher />
                        {user ? (
                            <Button type="button" variant="secondary" size="sm" onClick={() => router.post(route('logout'))}>
                                <LogOut size={16} />
                                {t('logout')}
                            </Button>
                        ) : (
                            <Button asChild size="sm">
                                <Link href={route('login')}>
                                    <LogIn size={16} />
                                    {t('login')}
                                </Link>
                            </Button>
                        )}
                    </div>
                </div>
            </header>

            <main className="kit-content">{children}</main>
        </div>
    );
}
