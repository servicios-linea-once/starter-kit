import { cn } from '@/lib/utils';

export function Card({ className, ...props }) {
    return <section className={cn('kit-panel rounded-lg p-6', className)} {...props} />;
}

export function CardHeader({ className, ...props }) {
    return <div className={cn('mb-5', className)} {...props} />;
}

export function CardTitle({ className, ...props }) {
    return <h2 className={cn('text-lg font-black tracking-[0]', className)} {...props} />;
}

export function CardContent({ className, ...props }) {
    return <div className={cn(className)} {...props} />;
}
