import { Alert } from '@/Components/ui/alert';

export default function StatusMessage({ message, variant = 'success' }) {
    if (! message) {
        return null;
    }

    return <Alert variant={variant}>{message}</Alert>;
}
