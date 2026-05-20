import LanguageSwitcher from '@/Components/LanguageSwitcher';
import { useLanguage } from '@/lib/i18n';

export default function AuthLayout({ title, subtitle, tab, children }) {
    const { t } = useLanguage();

    return (
        <div className="kit-auth-shell text-slate-100 app-dark">
            <div className="kit-content mx-auto flex min-h-screen max-w-7xl flex-col px-5 py-7">
                <header className="kit-showcase-header">
                    <div className="flex w-full justify-end">
                        <LanguageSwitcher />
                    </div>
                    <div className="kit-brand-lockup">
                        <span className="kit-brand-mark h-14 w-14 rounded-lg">
                            <span className="kit-brand-letter text-3xl">S</span>
                        </span>
                        <h1 className="kit-brand-title">{t('brandFull')}</h1>
                    </div>
                    <p className="kit-brand-subtitle">{t('experienceTagline')}</p>
                    <span className="kit-brand-underline" />
                </header>

                <main className="grid flex-1 items-center gap-5 py-8 lg:grid-cols-[0.95fr_1.05fr]">
                    <section className="kit-panel relative rounded-lg p-7 pt-12 backdrop-blur md:p-9 md:pt-12">
                        <span className="kit-panel-tab">{tab || title}</span>
                        <div className="mb-7 flex items-center gap-3">
                            <span className="kit-brand-mark h-10 w-10 rounded-lg">
                                <span className="kit-brand-letter text-xl">S</span>
                            </span>
                            <div>
                                <h2 className="text-2xl font-black tracking-[0]">{title}</h2>
                                {subtitle ? <p className="mt-1 text-sm leading-6 text-slate-400">{subtitle}</p> : null}
                            </div>
                        </div>

                        {children}
                    </section>

                    <aside className="kit-visual-card hidden lg:block">
                        <div className="kit-portal" />
                    </aside>
                </main>
            </div>
        </div>
    );
}
