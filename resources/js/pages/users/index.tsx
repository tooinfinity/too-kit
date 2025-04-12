import DataTable from '@/components/data-table/data-table';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/hooks/use-translation';
import AppLayout from '@/layouts/app-layout';
import UsersColumns from '@/pages/users/partials/users-columns';
import { BreadcrumbItem, User } from '@/types';
import { Head, router } from '@inertiajs/react';
import { useMemo } from 'react';

interface UsersProps {
    users: {
        data: User[];
        meta: {
            current_page: number;
            from: number;
            last_page: number;
            links: never[];
            path: string;
            per_page: number;
            to: number;
            total: number;
        };
        links: {
            first: string;
            last: string;
            next: string | null;
            prev: string | null;
        };
    };
}
export default function Index({ users }: UsersProps) {
    const { __ } = useTranslation();
    console.log(users);
    const columns = UsersColumns();

    const breadcrumbs = useMemo<BreadcrumbItem[]>(
        () => [
            {
                title: __('Users'),
                href: '/users',
            },
        ],
        [__],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={__('Users')} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="space-y-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-bold tracking-tight">{__('Users')}</h2>
                            <p className="text-muted-foreground">{__('Manage user accounts and their roles and permissions')}</p>
                        </div>
                        <div className="flex gap-2">
                            <Button onClick={() => router.get('/users/create')}>{__('Add User')}</Button>
                        </div>
                    </div>

                    <DataTable columns={columns} data={users.data} meta={users.meta} resourceName={'users'} />
                </div>
            </div>
        </AppLayout>
    );
}
