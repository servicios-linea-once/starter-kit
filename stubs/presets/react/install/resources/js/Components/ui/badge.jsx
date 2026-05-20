import { cn } from '@/lib/utils';

const styles = {
    default: 'bg-white/10 text-slate-200',
    success: 'bg-emerald-400/15 text-emerald-200',
    warning: 'bg-amber-400/15 text-amber-200',
    info: 'bg-cyan-400/15 text-cyan-100',
};

export function Badge({ className, variant = 'default', ...props }) {
    return (
        <span
            className={cn('inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold', styles[variant], className)}
            {...props}
        />
    );
}
