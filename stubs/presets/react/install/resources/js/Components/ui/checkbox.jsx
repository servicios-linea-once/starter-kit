import { Check } from 'lucide-react';
import { cn } from '@/lib/utils';

export function Checkbox({ className, checked, onCheckedChange, ...props }) {
    return (
        <button
            type="button"
            role="checkbox"
            aria-checked={checked}
            className={cn(
                'grid h-4 w-4 place-items-center rounded border border-white/20 bg-slate-950 text-slate-950 transition-colors',
                checked && 'border-cyan-300 bg-cyan-300',
                className,
            )}
            onClick={() => onCheckedChange?.(! checked)}
            {...props}
        >
            {checked ? <Check size={12} strokeWidth={4} /> : null}
        </button>
    );
}
