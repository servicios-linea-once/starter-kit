import { Languages } from 'lucide-react';
import { Button } from '@/Components/ui/button';
import { useLanguage } from '@/lib/i18n';

export default function LanguageSwitcher() {
    const { locale, setLocale, t } = useLanguage();

    return (
        <div className="flex items-center gap-1" aria-label={t('language')}>
            <Languages size={16} className="text-slate-400" />
            <Button
                type="button"
                variant={locale === 'es' ? 'default' : 'ghost'}
                size="sm"
                onClick={() => setLocale('es')}
            >
                ES
            </Button>
            <Button
                type="button"
                variant={locale === 'en' ? 'default' : 'ghost'}
                size="sm"
                onClick={() => setLocale('en')}
            >
                EN
            </Button>
        </div>
    );
}
