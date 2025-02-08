import { usePage } from '@inertiajs/vue3';

export function useAcl() {
    const permissions = usePage().props.auth?.permissions || [];
    const can = (permission) => {
        return permissions.includes(permission);
    };

    return { can };
}
