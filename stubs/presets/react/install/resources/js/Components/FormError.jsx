export default function FormError({ message }) {
    if (! message) {
        return null;
    }

    return <small className="mt-1 block text-sm font-semibold text-rose-300">{message}</small>;
}
