import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
export function usePermissions() {
    const { auth } = usePage<SharedData>().props;
    const hasRole = (name: string) => ((auth.user?.roles as string[]) || []).includes(name);
    const hasPermission = (name: string) => ((auth.user?.permissions as string[]) || []).includes(name);

    return { hasRole, hasPermission };
}
