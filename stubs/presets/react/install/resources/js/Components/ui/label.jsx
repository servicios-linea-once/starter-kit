import * as LabelPrimitive from '@radix-ui/react-label';
import { cn } from '@/lib/utils';

export function Label({ className, ...props }) {
    return (
        <LabelPrimitive.Root
            className={cn('mb-2 block text-sm font-bold text-slate-300', className)}
            {...props}
        />
    );
}
