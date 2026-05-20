import { Slot } from '@radix-ui/react-slot';
import { cva } from 'class-variance-authority';
import { cn } from '@/lib/utils';

const buttonVariants = cva(
    'inline-flex h-10 items-center justify-center gap-2 whitespace-nowrap rounded-lg px-4 text-sm font-bold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-cyan-300 disabled:pointer-events-none disabled:opacity-50',
    {
        variants: {
            variant: {
                default: 'bg-cyan-300 text-slate-950 hover:bg-cyan-200',
                secondary: 'border border-white/10 bg-white/5 text-slate-100 hover:bg-white/10',
                ghost: 'text-slate-300 hover:bg-white/5 hover:text-white',
                danger: 'bg-rose-500 text-white hover:bg-rose-400',
                dangerSecondary: 'border border-rose-400/40 bg-rose-400/10 text-rose-100 hover:bg-rose-400/20',
            },
            size: {
                default: 'h-10 px-4',
                sm: 'h-8 px-3 text-xs',
                lg: 'h-11 px-5',
                icon: 'h-9 w-9 px-0',
            },
            block: {
                true: 'w-full',
                false: '',
            },
        },
        defaultVariants: {
            variant: 'default',
            size: 'default',
            block: false,
        },
    },
);

export function Button({ className, variant, size, block, asChild = false, ...props }) {
    const Comp = asChild ? Slot : 'button';

    return <Comp className={cn(buttonVariants({ variant, size, block }), className)} {...props} />;
}
