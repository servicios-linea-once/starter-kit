import { cn } from '@/lib/utils';

export function Input({ className, ...props }) {
    return (
        <input
            className={cn(
                'flex h-10 w-full rounded-lg border border-white/10 bg-slate-950/80 px-3 text-sm text-slate-100 outline-none transition-colors placeholder:text-slate-500 focus:border-cyan-300 disabled:cursor-not-allowed disabled:opacity-50',
                className,
            )}
            {...props}
        />
    );
}
