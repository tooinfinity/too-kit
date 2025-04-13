import ColumnHeader from '@/components/data-table/column-header';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useTranslation } from '@/hooks/use-translation';
import { cn } from '@/lib/utils';
import { User } from '@/types';
import { router } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { Edit, MoreHorizontal, Trash } from 'lucide-react';
import { useMemo } from 'react';
import { toast } from 'react-hot-toast';

const ROLE_STYLES = {
    admin: 'bg-red-100 text-red-700',
    manager: 'bg-blue-100 text-blue-700',
    cashier: 'bg-orange-100 text-orange-700',
    default: 'bg-gray-100 text-gray-700',
};
const RoleBadge = ({ role }: { role: string[] }) => {
    if (!role?.length) {
        return <p className="rounded-md bg-gray-100 px-2 py-1 text-sm font-medium text-gray-700">No role</p>;
    }

    const roleText = role[0];
    const styleClass = ROLE_STYLES[roleText as keyof typeof ROLE_STYLES] || ROLE_STYLES.default;

    return <p className={cn('rounded-md px-2 py-1 text-sm font-medium', styleClass)}>{roleText}</p>;
};

const UserActions = ({ user }: { user: User }) => {
    const { __ } = useTranslation();

    const handleEdit = () => {
        router.get(`/users/${user.id}/edit`);
    };
    const handleDelete = () => {
        if (confirm(__('Are you sure you want to delete this user?'))) {
            router.delete(`/users/${user.id}`, {
                onSuccess: () => toast.success(__('User deleted successfully')),
            });
        }
    };

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant={'ghost'} className="h-8 w-8 p-0">
                    <span className="sr-only">{__('Open menu')}</span>
                    <MoreHorizontal className="h-4 w-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuLabel>{__('Actions')}</DropdownMenuLabel>
                <DropdownMenuItem onClick={handleEdit}>
                    <Edit className="mr-2 h-4 w-4" /> {__('Edit')}
                </DropdownMenuItem>
                <DropdownMenuItem onClick={handleDelete} className="text-red-600">
                    <Trash className="mr-2 h-4 w-4" /> {__('Delete')}
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    );
};

const UsersColumns = () => {
    const { __ } = useTranslation();

    return useMemo<ColumnDef<User>[]>(
        () => [
            {
                id: 'select',
                header: ({ table }) => (
                    <Checkbox
                        checked={table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate')}
                        onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
                        aria-label="Select all"
                    />
                ),
                cell: ({ row }) => (
                    <Checkbox checked={row.getIsSelected()} onCheckedChange={(value) => row.toggleSelected(!!value)} aria-label="Select row" />
                ),
                enableSorting: false,
                enableHiding: false,
            },
            {
                header: ({ column }) => <ColumnHeader column={column} title={__('Name')} />,
                accessorKey: 'name',
                enableSorting: true,
                enableColumnFilter: true,
            },
            {
                header: ({ column }) => <ColumnHeader column={column} title={__('Email')} />,
                accessorKey: 'email',
                enableSorting: true,
                enableColumnFilter: true,
            },
            {
                header: ({ column }) => <ColumnHeader column={column} title={__('Role')} />,
                accessorKey: 'roles',
                enableSorting: false,
                enableColumnFilter: false,
                cell: ({ row }) => (
                    <div className="flex gap-1">
                        <RoleBadge role={row.original.roles} />
                    </div>
                ),
            },
            {
                header: ({ column }) => <ColumnHeader column={column} title={__('Created')} />,
                accessorKey: 'created_at',
                enableSorting: true,
                enableColumnFilter: false,
            },
            {
                id: 'actions',
                header: ({ column }) => <ColumnHeader column={column} title={__('Actions')} />,
                enableSorting: false,
                enableColumnFilter: false,
                cell: ({ row }) => <UserActions user={row.original} />,
            },
        ],
        [__],
    );
};

export default UsersColumns;
