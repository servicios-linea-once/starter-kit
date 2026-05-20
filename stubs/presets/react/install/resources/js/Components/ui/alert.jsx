import { cn } from '@/lib/utils';

export function Alert({ className, variant = 'success', ...props }) {
    return (
        <div
            className={cn(
                'mb-5 rounded-lg border px-4 py-3 text-sm font-semibold',
                variant === 'danger'
                    ? 'border-rose-400/30 bg-rose-400/10 text-rose-100'
                    : 'border-emerald-400/30 bg-emerald-400/10 text-emerald-100',
                className,
            )}
            {...props}
        />
    );
}
